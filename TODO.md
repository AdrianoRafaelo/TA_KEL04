# TODO untuk Fitur Kurikulum

## Informasi yang Dikumpulkan:
- Controller TugasAkhirController: Method indexMahasiswa() mengembalikan view 'tugasakhir.mahasiswa' dengan data TA. Tidak ada logika kurikulum, jadi tambahkan fetch data kurikulum di sini.
- View mahasiswa.blade.php: Tampilan dashboard mahasiswa untuk pendaftaran TA. Tambahkan section baru untuk kurikulum di atas section existing, menggunakan grid Bootstrap (row dengan col-md-2 untuk 6 semester).
- Tidak ada tabel/model kurikulum existing.
- RoleMiddleware untuk membatasi akses 'mahasiswa'.
- Semua tabel harus punya: created_at, updated_at, created_by, updated_by, active (default 1).
- Sample data: 6 semester dengan 3-4 mata kuliah per semester berdasarkan gambar (e.g., Semester 1: Algoritma dan Pemrograman 1, dll.).

## Plan:
- Buat migration untuk tabel kurikulum dengan kolom: id, semester (int), kode_mk (string), nama_mk (string), nama_mk_eng (string), nama_singkat_mk (string), sks (int), deskripsi_mk (text), timestamps, created_by (string nullable), updated_by (string nullable), active (boolean default 1).
- Buat model Kurikulum, fillable untuk semua kolom kecuali id.
- Buat seeder KurikulumSeeder dengan data sample (bahasa Indonesia, 6 semester).
- Update TugasAkhirController: Import Kurikulum, di indexMahasiswa() tambah $kurikulum = Kurikulum::where('active', 1)->orderBy('semester')->get()->groupBy('semester'); pass ke view.
- Update mahasiswa.blade.php: Tambah section grid kurikulum (row dengan 6 col-md-2, header "Semester X", list mata kuliah: kode_mk - nama_mk (sks SKS)).
- Update DatabaseSeeder untuk call KurikulumSeeder.

## Dependent Files to be edited:
- Baru: database/migrations/[timestamp]_create_kurikulum_table.php
- Baru: app/Models/Kurikulum.php
- Baru: database/seeders/KurikulumSeeder.php
- Edit: database/seeders/DatabaseSeeder.php
- Edit: app/Http/Controllers/TugasAkhirController.php
- Edit: resources/views/tugasakhir/mahasiswa.blade.php

## Followup steps:
- Jalankan migrate dan seed.
- Test: Akses route mahasiswa, verifikasi grid kurikulum muncul hanya untuk role mahasiswa.
- Jika perlu, tambah CSS untuk styling.

## Langkah-langkah:
- [x] Buat migration create_kurikulum_table dan edit isinya.
- [x] Jalankan php artisan migrate.
- [x] Buat model Kurikulum dan edit fillable.
- [x] Buat seeder KurikulumSeeder dan isi data sample.
- [x] Update DatabaseSeeder untuk include KurikulumSeeder.
- [x] Jalankan php artisan db:seed.
- [x] Update TugasAkhirController untuk fetch kurikulum.
- [x] Update mahasiswa.blade.php untuk tampilkan grid.
- [x] Verifikasi route di web.php sudah dilindungi RoleMiddleware('mahasiswa') jika belum.
- [x] Test fitur lengkap (siap untuk testing di browser).
