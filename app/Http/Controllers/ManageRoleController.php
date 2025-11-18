<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\FtiData;

class ManageRoleController extends Controller
{
    protected $baseUrl = 'https://cis-dev.del.ac.id/api';
    protected $username = 'johannes';
    protected $password = 'Del@2022'; // sebaiknya simpan di .env

    private function getToken()
    {
        $response = Http::timeout(300)->asForm()->post("{$this->baseUrl}/jwt-api/do-auth", [
            'username' => $this->username,
            'password' => $this->password,
        ]);

        if ($response->successful()) {
            return $response->json()['token'];
        }

        throw new \Exception('Gagal autentikasi API CIS');
    }

    public function getFtiData()
    {
        try {
            // 🔹 Filter role (student / lecturer)
            $roleFilter = request()->input('role', 'student');

            // 🔹 Cek apakah data lokal sudah ada
            $localData = FtiData::where('role', $roleFilter)->get();

            if ($localData->count() > 0) {
                // ✅ Gunakan data lokal tanpa API
                return view('fti-data', ['data' => $localData]);
            }

            // 🔹 Jika belum ada → ambil dari API
            $token = $this->getToken();

            /** === AMBIL MAHASISWA === */
            $mahasiswaResponse = Http::withToken($token)
                ->timeout(300)
                ->get("{$this->baseUrl}/library-api/mahasiswa?limit=1000");

            if (!$mahasiswaResponse->successful()) {
                throw new \Exception('Gagal mengambil data mahasiswa');
            }

            $mahasiswaData = $mahasiswaResponse->json()['data']['mahasiswa'] ?? [];

            $filteredMahasiswa = collect($mahasiswaData)
                ->filter(fn($item) =>
                    ($item['prodi_name'] ?? '') === 'S1 Manajemen Rekayasa' &&
                    ($item['status'] ?? '') === 'Aktif'
                )
                ->map(fn($item) => [
                    'nama' => $item['nama'] ?? 'N/A',
                    'username' => $item['user_name'] ?? 'N/A',
                    'nim' => $item['nim'] ?? '-',
                    'role' => 'student',
                    'prodi' => $item['prodi_name'] ?? 'N/A',
                    'fakultas' => $item['fakultas'] ?? 'FTI',
                    'status' => $item['status'] ?? '-',
                ])
                ->values();

            /** === AMBIL SEMUA DOSEN TANPA FILTER === */
            $dosenResponse = Http::withToken($token)
                ->get("{$this->baseUrl}/library-api/dosen?limit=500");

            $dosenData = $dosenResponse->successful()
                ? ($dosenResponse->json()['data']['dosen'] ?? [])
                : [];

            $filteredDosen = collect($dosenData)
                ->filter(fn($item) => !empty($item['user_id']) && $item['user_id'] !== '-')
                ->map(fn($item) => [
                    'nama' => $item['nama'] ?? 'N/A',
                    'user_id' => $item['user_id'],
                    'username' => $item['username'] ?? null,
                    'nim' => null,
                    'role' => $item['role'] ?? 'Lecturer', 
                    'prodi' => $item['prodi'] ?? 'N/A',
                    'fakultas' => $item['fakultas'] ?? 'N/A',
                    'status' => '-',
                ])
                ->values();

            // 🔹 Gabungkan dan simpan ke DB
            $allFtiData = $filteredMahasiswa->merge($filteredDosen)->values()->toArray();

            // Simpan satu per satu untuk menangani duplikasi
            foreach ($allFtiData as $data) {
                FtiData::updateOrCreate(
                    ['nama' => $data['nama'], 'role' => $data['role']],
                    $data
                );
            }

            // 🔹 Tampilkan data sesuai filter
            $filteredLocalData = collect($allFtiData)
                ->where('role', $roleFilter)
                ->values()
                ->toArray();

            return view('fti-data', ['data' => $filteredLocalData]);

        } catch (\Exception $e) {
            \Log::error('Error: ' . $e->getMessage());
            return view('fti-data', ['data' => []])
                ->with('message', 'Error: ' . $e->getMessage());
        }
    }

    public function refreshData()
    {
        try {
            // Ambil data terbaru dari API dan simpan ke tabel fti_datas
            $token = $this->getToken();

            // Truncate dan insert ulang data
            \DB::table('fti_datas')->truncate();
            $allData = $this->fetchFtiData($token);
            foreach ($allData as $data) {
                FtiData::updateOrCreate(
                    ['nama' => $data['nama'], 'role' => $data['role']],
                    $data
                );
            }

            return redirect()->route('fti-data')->with('message', '✅ Data berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->route('fti-data')->with('message', '❌ Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    /** 🔹 Fungsi untuk ambil ulang data API */
    private function fetchFtiData($token)
    {
        /** === MAHASISWA === */
        $mahasiswaResponse = Http::withToken($token)
            ->timeout(300)
            ->get("{$this->baseUrl}/library-api/mahasiswa?limit=1000");

        $mahasiswaData = $mahasiswaResponse->successful()
            ? ($mahasiswaResponse->json()['data']['mahasiswa'] ?? [])
            : [];

        $filteredMahasiswa = collect($mahasiswaData)
            ->filter(fn($item) =>
                ($item['prodi_name'] ?? '') === 'S1 Manajemen Rekayasa' &&
                ($item['status'] ?? '') === 'Aktif'
            )
            ->map(fn($item) => [
                'nama' => $item['nama'] ?? 'N/A',
                'username' => $item['user_name'] ?? 'N/A',
                'nim' => $item['nim'] ?? '-',
                'role' => 'student',
                'prodi' => $item['prodi_name'] ?? 'N/A',
                'fakultas' => $item['fakultas'] ?? 'FTI',
                'status' => $item['status'] ?? '-',
            ])
            ->values();

        /** === DOSEN (SEMUA TANPA FILTER) === */
        $dosenResponse = Http::withToken($token)
            ->get("{$this->baseUrl}/library-api/dosen?limit=500");

        $dosenData = $dosenResponse->successful()
            ? ($dosenResponse->json()['data']['dosen'] ?? [])
            : [];

        $filteredDosen = collect($dosenData)
            ->filter(fn($item) => !empty($item['user_id']) && $item['user_id'] !== '-')
            ->map(fn($item) => [
                'nama' => $item['nama'] ?? 'N/A',
                'user_id' => $item['user_id'],
                'username' => $item['username'] ?? null,
                'nim' => null,
                'role' => $item['role'] ?? 'lecturer', 
                'prodi' => $item['prodi'] ?? 'N/A',
                'fakultas' => $item['fakultas'] ?? 'N/A',
                'status' => '-',
            ])
            ->values();

        return $filteredMahasiswa->merge($filteredDosen)->values()->toArray();
    }

    public function getMahasiswaData()
    {
        try {
            $mahasiswaData = FtiData::whereIn('role', ['student', 'mahasiswa'])
                ->select('nama', 'username', 'nim')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $mahasiswaData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data mahasiswa: ' . $e->getMessage()
            ], 500);
        }
    }
}
