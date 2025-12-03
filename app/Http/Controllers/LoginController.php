<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use App\Models\Role;
use App\Models\UserRole;
use App\Models\Pengumuman;
use App\Models\FtiData;
use App\Models\User;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

        public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // 🔹 ADMIN LOKAL
        if ($request->username === 'admin' && $request->password === 'admin@2022') {
            $request->session()->put('api_token', 'admin_token');
            $request->session()->put('username', $request->username);
            $request->session()->put('role', 'Admin');
            $request->session()->put('user', [
                'id' => 1,
                'username' => $request->username,
                'nama' => 'Administrator',
                'role' => 'Admin'
            ]);

            \Log::info('Login Success - Username: ' . $request->username . ', Role: Admin');
            return redirect()->route('welcome');
        }

        try {
            $client = new \GuzzleHttp\Client();
            $apiBaseUrl = env('API_BASE_URL', config('app.api_base_url'));
            $response = $client->post(rtrim($apiBaseUrl, '/') . '/api/jwt-api/do-auth', [
                'verify' => false,
                'multipart' => [
                    ['name' => 'username', 'contents' => $request->username],
                    ['name' => 'password', 'contents' => $request->password],
                ],
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
            $token = $data['token'] ?? null;
            $nama = $data['user']['nama'] ?? $request->username;

            if (!$token) {
                return back()->withErrors(['login' => 'Login gagal']);
            }

            // 🔹 Prioritas: Cek role dari fti_datas berdasarkan username atau user_id
            $ftiData = FtiData::where('username', $request->username)
                ->orWhere('user_id', $data['user']['user_id'] ?? null)
                ->first();

            if ($ftiData && $ftiData->role_id) {
                $role = Role::find($ftiData->role_id);
                $roleName = $role ? $role->name : 'Mahasiswa';
            } else {
                // 🔹 Fallback: Cari role user di tabel lokal user_roles
                $userRole = UserRole::where('username', $request->username)->first();

                if ($userRole) {
                    $role = Role::find($userRole->role_id);
                    $roleName = $role ? $role->name : 'Unknown';
                } else {
                    // Jika belum ada, ambil dari API (fallback terakhir)
                    $roleName = $data['user']['role'] ?? 'Mahasiswa';

                    // Simpan ke tabel jika belum ada
                    $role = Role::firstOrCreate(
                        ['name' => ucfirst($roleName)],
                        ['created_by' => $request->username, 'active' => 1]
                    );

                    UserRole::create([
                        'username' => $request->username,
                        'role_id' => $role->id,
                        'created_by' => $request->username,
                        'active' => 1,
                    ]);
                }
            }

            // 🔹 Simpan data ke tabel users
            $userId = $data['user']['user_id'] ?? null;
            $nim = $data['user']['nim'] ?? $request->username; // Gunakan username sebagai fallback jika nim tidak ada
            $email = $data['user']['email'] ?? null;

            User::updateOrCreate(
                ['nim' => $nim], // Cari berdasarkan nim
                [
                    'name' => $nama,
                    'email' => $email,
                    'password' => bcrypt('password'), // Default password
                    'created_by' => null,
                    'updated_by' => null,
                    'active' => '1', // Default active
                ]
            );

            // 🔹 Simpan sesi
            $request->session()->put('api_token', $token);
            $request->session()->put('username', $request->username);
            $request->session()->put('role', $roleName);
            $request->session()->put('user', [
                'id' => $userId,
                'username' => $request->username,
                'nama' => $nama,
                'role' => $roleName
            ]);

            // 🔹 Update username di fti_datas untuk dosen saat login berdasarkan user_id
            if (strtolower($roleName) === 'dosen') {
                // Cari user_id dari data API login
                $userId = $data['user']['user_id'] ?? null;
                if ($userId) {
                    FtiData::where('user_id', $userId)->update(['username' => $request->username]);
                }
            }

            \Log::info('Login Success - Username: ' . $request->username . ', Role: ' . $roleName);

            // 🔹 Arahkan berdasarkan role
            if (strtolower($roleName) === 'dosen') {
                return redirect()->route('welcome');
            } elseif (strtolower($roleName) === 'mahasiswa') {
                return redirect()->route('welcome');
            } elseif (strtolower($roleName) === 'koordinator') {
                return redirect()->route('welcome');
            } else {
                return redirect()->route('welcome');
            }

        } catch (\Exception $e) {
            \Log::error('Login error: ' . $e->getMessage());
            return back()->withErrors(['login' => 'Gagal terhubung ke API: ' . $e->getMessage()]);
        }
    }

    public function logout(Request $request)
    {
        $request->session()->forget('api_token');
        return redirect('/login')->with('success', 'Logout berhasil!');
    }

    public function home()
    {
        // Ambil data Pengumuman terbaru untuk setiap kategori
        $pengumuman_kompetisi = Pengumuman::where('kategori', 'Kompetisi')->latest()->take(2)->get();
        $pengumuman_magang = Pengumuman::where('kategori', 'Magang')->latest()->take(2)->get();
        $pengumuman_umum = Pengumuman::where('kategori', 'Umum')->latest()->take(1)->get(); 

        // Menggunakan view 'beranda' Anda, sesuai dengan konten dashboard
        return view('welcome', compact('pengumuman_kompetisi', 'pengumuman_magang', 'pengumuman_umum'));
    }
}