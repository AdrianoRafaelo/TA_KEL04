@extends('layouts.app')

@section('content')
    <style>
        /* ===================== */
        /* STYLING UMUM & ANIMASI */
        /* ===================== */
        body {
            background-color: #f9fafb;
            font-family: 'Inter', sans-serif;
        }

        h2 {
            color: #2e7d32;
            font-weight: 700;
            font-size: 22px;
            margin-bottom: 20px;
        }

        .page-container {
            width: 100%;
            padding: 0;
            margin: 0;
            animation: fadeIn 0.4s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ===================== */
        /* ALERT SUCCESS */
        /* ===================== */
        .alert-success {
            background-color: #e8f5e9;
            color: #256029;
            border-left: 4px solid #4CAF50;
            padding: 10px 15px;
            border-radius: 6px;
            margin-bottom: 15px;
            font-weight: 500;
            width: 98%;
            margin-left: auto;
            margin-right: auto;
        }

        /* ===================== */
        /* TABEL STYLING */
        /* ===================== */
        .table-container {
            width: 100%;
            overflow-x: auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1100px;
            border-radius: 8px;
            overflow: hidden;
        }

        th {
            background-color: #4CAF50;
            color: white;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.5px;
            padding: 14px;
            font-size: 14px;
            text-align: left;
            white-space: nowrap;
        }

        td {
            padding: 12px 16px;
            border-bottom: 1px solid #e0e0e0;
            font-size: 15px;
            white-space: nowrap;
        }

        tr:hover {
            background-color: #f5f5f5;
            transition: 0.2s ease;
        }

        /* ===================== */
        /* SELECT & BUTTON STYLE */
        /* ===================== */
        select {
            border: 1px solid #ccc;
            border-radius: 6px;
            padding: 6px 10px;
            font-size: 14px;
            outline: none;
            transition: border-color 0.3s ease;
        }

        select:focus {
            border-color: #4CAF50;
            box-shadow: 0 0 3px rgba(76, 175, 80, 0.4);
        }

        .btn-save {
            background-color: #2196F3;
            color: white;
            border: none;
            padding: 8px 16px;
            font-size: 14px;
            font-weight: 600;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-save:hover {
            background-color: #1976D2;
            transform: translateY(-2px);
        }

        /* ===================== */
        /* RESPONSIVE MOBILE */
        /* ===================== */
        @media (max-width: 768px) {
            .table-container {
                overflow-x: auto;
            }

            table,
            thead,
            tbody,
            th,
            td,
            tr {
                display: block;
            }

            tr {
                margin-bottom: 15px;
                box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
                border-radius: 6px;
            }

            th {
                display: none;
            }

            td {
                display: flex;
                justify-content: space-between;
                padding: 10px;
                border: none;
            }

            td::before {
                content: attr(data-label);
                font-weight: bold;
                color: #4CAF50;
            }
        }

        /* ===================== */
        /* Pagination */
        /* ===================== */

        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 6px;
            margin-top: 20px;
            font-size: 14px;
        }

        .pagination a,
        .pagination span {
            padding: 6px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            color: #6b7280;
            text-decoration: none;
            transition: all 0.2s;
        }

        .pagination a:hover {
            background-color: #f3f4f6;
            color: #111827;
        }

        .pagination .active {
            background-color: #8b5e34;
            /* coklat sesuai desainmu */
            color: white;
            border-color: #8b5e34;
        }
    </style>

    <div class="page-container">
        <div class="px-6 py-4">
            <h2>Kelola Role Mahasiswa & Dosen</h2>

            {{-- Alert sukses --}}
            @if(session('success'))
                <div class="alert-success">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Tabel Data --}}
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>NIM / Username</th>
                            <th>Prodi</th>
                            <th>Fakultas</th>
                            <th>Role</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($datas as $data)
                            <tr>
                                <td data-label="Nama">{{ $data->nama }}</td>
                                <td data-label="NIM / Username">{{ $data->nim ?? $data->user_name }}</td>
                                <td data-label="Prodi">{{ $data->prodi_name }}</td>
                                <td data-label="Fakultas">{{ $data->fakultas }}</td>
                                <td data-label="Role">
                                    <form action="{{ route('admin.fti_roles.update', $data->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <select name="role_id">
                                            <option value="">Pilih Role</option>
                                            @foreach($roles as $role)
                                                <option value="{{ $role->id }}" {{ $data->role_id == $role->id ? 'selected' : '' }}>
                                                    {{ $role->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                </td>
                                <td data-label="Aksi" class="text-center">
                                    <button type="submit" class="btn-save">Simpan</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $datas->links('pagination::default') }}
            </div>


        </div>
    </div>
@endsection