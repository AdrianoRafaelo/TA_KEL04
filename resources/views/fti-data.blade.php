@extends('layouts.app')

@section('content')
<style>
    /* ===================== */
    /* ANIMASI FILTER BUTTON */
    /* ===================== */
    .filter-buttons {
        position: relative;
        display: inline-flex;
        background-color: #e8f5e9;
        border-radius: 8px;
        overflow: hidden;
        margin-bottom: 20px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }

    .filter-buttons a {
        text-decoration: none;
        flex: 1;
    }

    .filter-buttons button {
        position: relative;
        padding: 10px 30px;
        border: none;
        background: transparent;
        cursor: pointer;
        color: #2e7d32;
        font-weight: 600;
        font-size: 16px;
        z-index: 2;
        transition: color 0.3s ease;
        width: 150px;
    }

    .filter-buttons button:hover {
        color: #1b5e20;
    }

    .filter-buttons::before {
        content: "";
        position: absolute;
        top: 0;
        bottom: 0;
        width: 50%;
        background-color: #4CAF50;
        border-radius: 8px;
        transition: transform 0.35s ease;
        z-index: 1;
    }

    .filter-buttons[data-active="lecturer"]::before {
        transform: translateX(100%);
    }

    .filter-buttons[data-active="student"] button:first-child,
    .filter-buttons[data-active="lecturer"] button:last-child {
        color: white;
    }

    /* ===================== */
    /* TOMBOL PERBARUI DATA */
    /* ===================== */
    .refresh-section {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 15px;
        flex-wrap: wrap;
    }

    .refresh-btn {
        background-color: #2196F3;
        color: white;
        border: none;
        padding: 10px 20px;
        font-size: 15px;
        font-weight: 600;
        border-radius: 6px;
        cursor: pointer;
        transition: background-color 0.3s ease, transform 0.2s ease;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .refresh-btn:hover {
        background-color: #1976D2;
        transform: translateY(-2px);
    }

    .refresh-btn svg {
        width: 18px;
        height: 18px;
        animation: spin 1.5s linear infinite;
    }

    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    /* ===================== */
    /* TABEL DATA */
    /* ===================== */
    table {
        width: 100%;
        border-collapse: collapse;
        background-color: #fff;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        font-size: 15px;
        animation: fadeIn 0.4s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    th, td {
        padding: 12px 16px;
        border-bottom: 1px solid #e0e0e0;
        text-align: left;
    }

    th {
        background-color: #4CAF50;
        color: white;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    tr:hover {
        background-color: #f5f5f5;
        transition: background-color 0.2s ease;
    }

    .status-aktif { color: green; font-weight: bold; }
    .status-nonaktif { color: red; font-weight: bold; }

    @media (max-width: 768px) {
        table { font-size: 13px; }
        .filter-buttons { flex-direction: column; }
        .filter-buttons::before {
            width: 100%;
            height: 50%;
        }
        .filter-buttons[data-active="lecturer"]::before {
            transform: translateY(100%);
        }
        .filter-buttons a { width: 100%; }
        .filter-buttons button { width: 100%; }
    }
</style>

<h1 class="mb-3">Data Mahasiswa dan Dosen FTI</h1>

@if (isset($message))
    <p class="text-warning">{{ $message }}</p>

@elseif (count($data) > 0)
    @php
        $activeRole = request()->input('role', 'student');
    @endphp

    {{-- 🔹 Tombol dan Filter --}}
    <div class="refresh-section">
        <div class="filter-buttons" data-active="{{ $activeRole }}">
            <a href="{{ route('fti-data', ['role' => 'student']) }}">
                <button>Mahasiswa</button>
            </a>
            <a href="{{ route('fti-data', ['role' => 'lecturer']) }}">
                <button>Dosen</button>
            </a>
        </div>

        {{-- Tombol Perbarui Data --}}
        <form action="{{ route('fti-data.refresh') }}" method="POST" onsubmit="return confirm('Yakin ingin memperbarui data dari API? Ini mungkin memakan waktu beberapa detik...')">
            @csrf
            <button type="submit" class="refresh-btn">
                🔄 Perbarui Data
            </button>
        </form>
    </div>

    {{-- 🔹 Tabel Data --}}
    <table class="table">
        <thead>
            <tr>
                <th>Nama</th>
                @if ($activeRole == 'lecturer')
                    <th>User ID</th>
                @endif
                @if (isset($data[0]['nim'])) <th>NIM</th> @endif
                <th>Role</th>
                <th>Prodi</th>
                @if (isset($data[0]['fakultas'])) <th>Fakultas</th> @endif
                @if ($activeRole == 'student')
                    <th>Status</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
                <tr>
                    <td>{{ $item['nama'] }}</td>
                    @if ($activeRole == 'lecturer')
                        <td>{{ $item['user_id'] ?? '-' }}</td>
                    @endif
                    @if (isset($item['nim'])) <td>{{ $item['nim'] }}</td> @endif
                    <td>{{ ucfirst($item['role']) }}</td>
                    <td>{{ $item['prodi'] }}</td>
                    @if (isset($item['fakultas'])) <td>{{ $item['fakultas'] }}</td> @endif
                    @if ($item['role'] == 'student' && isset($item['status']))
                        <td class="{{ $item['status'] === 'Aktif' ? 'status-aktif' : 'status-nonaktif' }}">
                            {{ $item['status'] }}
                        </td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>

@else
    <p class="text-warning mt-3">Tidak ada data yang ditemukan.</p>
@endif

@endsection
