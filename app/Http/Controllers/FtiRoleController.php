<?php

namespace App\Http\Controllers;

use App\Models\FtiData;
use App\Models\Role;
use Illuminate\Http\Request;

class FtiRoleController extends Controller
{
    public function index()
    {
        $datas = FtiData::with('role')->get();
        $roles = Role::whereIn('name', ['Dosen', 'Koordinator', 'Mahasiswa'])->get();
        return view('admin.fti_roles.index', compact('datas', 'roles'));
    }

    public function updateRole(Request $request, $id)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        $data = FtiData::findOrFail($id);
        $data->role_id = $request->role_id;
        $data->save();

        // 🔹 Update juga role_id di tabel user_roles jika username ada
        if ($data->username) {
            \App\Models\UserRole::updateOrCreate(
                ['username' => $data->username],
                ['role_id' => $request->role_id, 'active' => 1, 'created_by' => auth()->user()->name ?? 'system']
            );
        }

        return redirect()->back()->with('success', 'Role berhasil diperbarui.');
    }
}
