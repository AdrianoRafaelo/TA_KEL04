@extends('layouts.app')

@section('content')
    <div class="bg-gray-50 min-h-screen py-6 px-8">

        <div class="container-fluid px-4">
            <!-- Breadcrumb & Title -->
            <div class="row mb-3">
                <div class="col-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Page</a></li>
                            <li class="breadcrumb-item"><a href="#">Tugas Akhir</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Mahasiswa TA</li>
                        </ol>
                    </nav>
                    <h4 class="mb-0">Tugas Akhir</h4>
                </div>
            </div>

            <!-- Tabs -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="kp-tabs d-flex flex-wrap gap-2">
                        <a href="{{ url('/koordinator-pendaftaran') }}" class="kp-tab">Pendaftaran Judul</a>
                        <a href="{{ route(name: 'koordinator.mahasiswa.ta') }}" class="kp-tab">Mahasiswa TA</a>
                        <a href="{{ route(name: 'koordinator.sempro') }}" class="kp-tab active">Seminar Proposal</a>
                        <button class="kp-tab" disabled>Seminar Hasil</button>
                        <button class="kp-tab" disabled>Sidang Akhir</button>
                        <button class="kp-tab" disabled>Unggah Skripsi</button>
                    </div>
                </div>
            </div>


            <!-- 
                {{-- Header --}}
                <div class="bg-[#2E2A78] text-black px-6 py-3 rounded-md inline-block mb-6 shadow-md">
                    <h2 class="text-sm font-semibold tracking-wide">Mahasiswa Tugas Akhir Angkatan 2020</h2>
                </div> -->
            <!-- INFORMASI UMUM — KOTAK KECIL + JARAK KE BAWAH -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-inline-flex align-items-center bg-purple rounded shadow-sm px-3 py-2 border"
                        style="box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                        <span class="me-2 text-success">
                            <i class="fas fa-pencil-alt"></i>
                        </span>
                        <span class="fw-bold text-dark" style="font-size: 0.95rem;">Mahasiswa Tugas Akhir Angkatan
                            2020</span>
                    </div>
                </div>
            </div>

            {{-- Table --}}
            <div class="bg-white rounded-md shadow-sm border border-gray-100 overflow-x-auto">
                <table class="w-full text-sm text-gray-700 border-separate border-spacing-0">
                    <thead class="bg-gray-100 text-gray-600 uppercase text-xs font-semibold border-b-4 border-[#C4A484]">
                        <tr>
                            <th class="py-3 px-4 text-left">No.</th>
                            <th class="py-3 px-4 text-left">Mahasiswa</th>
                            <th class="py-3 px-4 text-left">Judul</th>
                            <th class="py-3 px-4 text-left">Dosen Pembimbing</th>
                            <th class="py-3 px-4 text-left">Pengulas I</th>
                            <th class="py-3 px-4 text-left">Pengulas II</th>
                        </tr>
                    </thead>



                    <tbody class="divide-y divide-gray-100">
                        <tr class="hover:bg-gray-50">
                            <td class="py-3 px-4 text-gray-700">1.</td>
                            <td class="py-3 px-4 font-medium">Kevin Pakpahan</td>
                            <td class="py-3 px-4">Analisis Pengaruh Electronic Word of Mouth di daerah Pariwisata Kawasan
                                Danau Toba</td>
                            <td class="py-3 px-4">ISW</td>
                            <td class="py-3 px-4">HSS</td>
                            <td class="py-3 px-4">SAM</td>
                        </tr>

                        <tr class="bg-gray-50 hover:bg-gray-100">
                            <td class="py-3 px-4 text-gray-700">2.</td>
                            <td class="py-3 px-4 font-medium">Yosef Pakpahan</td>
                            <td class="py-3 px-4">Perancangan sistem pendukung keputusan pemilihan mata kuliah pilihan
                                program studi sarjana institut teknologi del dengan pengimplementasian berbasis
                                website/database</td>
                            <td class="py-3 px-4">ISW</td>
                            <td class="py-3 px-4">FIS</td>
                            <td class="py-3 px-4">WMS</td>
                        </tr>

                        <tr class="hover:bg-gray-50">
                            <td class="py-3 px-4 text-gray-700">3.</td>
                            <td class="py-3 px-4 font-medium">Sharon Ruth Esterina Simanjuntak</td>
                            <td class="py-3 px-4">Consumer Behavior in Digital Marketing</td>
                            <td class="py-3 px-4">SHT</td>
                            <td class="py-3 px-4">JBJ</td>
                            <td class="py-3 px-4">ANA</td>
                        </tr>

                        <tr class="bg-gray-50 hover:bg-gray-100">
                            <td class="py-3 px-4 text-gray-700">4.</td>
                            <td class="py-3 px-4 font-medium">Grace Vitani Pardosi</td>
                            <td class="py-3 px-4">Strategic Innovation in Social Media Marketing</td>
                            <td class="py-3 px-4">SHT</td>
                            <td class="py-3 px-4">ANA</td>
                            <td class="py-3 px-4">NSS</td>
                        </tr>

                        <tr class="hover:bg-gray-50">
                            <td class="py-3 px-4 text-gray-700">5.</td>
                            <td class="py-3 px-4 font-medium">Gabriel Sahat Nicolas</td>
                            <td class="py-3 px-4">Analisis Faktor Adopsi Kendaraan Listrik di Sumatera Utara dengan
                                Menggunakan Model UTAUT/TEM</td>
                            <td class="py-3 px-4">
                                <select
                                    class="border border-gray-300 rounded-md px-2 py-1 text-gray-700 focus:ring-1 focus:ring-[#2E2A78] focus:outline-none w-full">
                                    <option>Pilih Dosen</option>
                                    <option>ISW</option>
                                    <option>SHT</option>
                                    <option>ANA</option>
                                </select>
                            </td>
                            <td class="py-3 px-4">
                                <select
                                    class="border border-gray-300 rounded-md px-2 py-1 text-gray-700 focus:ring-1 focus:ring-[#2E2A78] focus:outline-none w-full">
                                    <option>Pilih Dosen</option>
                                    <option>HSS</option>
                                    <option>JBJ</option>
                                    <option>ANA</option>
                                </select>
                            </td>
                            <td class="py-3 px-4">
                                <select
                                    class="border border-gray-300 rounded-md px-2 py-1 text-gray-700 focus:ring-1 focus:ring-[#2E2A78] focus:outline-none w-full">
                                    <option>Pilih Dosen</option>
                                    <option>SAM</option>
                                    <option>WMS</option>
                                    <option>NSS</option>
                                </select>
                            </td>
                        </tr>

                        <tr class="bg-gray-50 hover:bg-gray-100">
                            <td class="py-3 px-4 text-gray-700">6.</td>
                            <td class="py-3 px-4 font-medium">Gomgom G. S. Tua Marpaung</td>
                            <td class="py-3 px-4">Analisis Hubungan Storescape (Faktor Lingkungan Fisik dan Social Retail’s)
                                dengan Loyalitas Pelanggan</td>
                            <td class="py-3 px-4">ANA</td>
                            <td class="py-3 px-4">SAM</td>
                            <td class="py-3 px-4">NSS</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
@endsection