<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Permohonan KP</title>
    <style>
        @page {
            size: A4;
            margin: 15mm 20mm 15mm 25mm; /* top, right, bottom, left */
        }

        body {
            font-family: "Times New Roman", serif;
            font-size: 12pt;
            line-height: 1.5;
            color: #000;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            max-width: 100%;
            padding: 0;
            margin: 0;
        }

        /* Header */
        .header {
            text-align: center;
            margin-bottom: 10px;
        }

        .logo {
            width: 65px;
            height: auto;
            margin-bottom: 8px;
        }

        .inst-name {
            font-weight: bold;
            font-size: 13.5pt;
            margin: 0;
            text-transform: uppercase;
        }

        .faculty-name {
            font-weight: bold;
            font-size: 12.5pt;
            margin: 3px 0 0;
            text-transform: uppercase;
        }

        .address {
            font-size: 10.5pt;
            margin: 6px 0 3px;
            line-height: 1.3;
        }

        .contact {
            font-size: 10pt;
            margin: 2px 0;
        }

        /* Tanggal & Nomor Surat */
        .date {
            text-align: right;
            margin: 15px 0 8px;
            font-size: 11.5pt;
        }

        .meta {
            font-size: 11.5pt;
            margin-bottom: 15px;
        }

        .meta div {
            display: flex;
            justify-content: flex-start;
            margin-bottom: 3px;
        }

        .meta-label {
            width: 60px;
            font-weight: bold;
        }

        /* Penerima */
        .recipient {
            margin: 20px 0 15px;
            font-size: 11.5pt;
        }

        .recipient p {
            margin: 2px 0;
        }

        .recipient strong {
            font-weight: bold;
        }

        /* Isi Surat */
        .content {
            text-align: justify;
            font-size: 11.5pt;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .content p {
            margin: 10px 0;
            text-indent: 35px;
        }

        .content strong {
            font-weight: bold;
        }

        .objectives {
            margin: 12px 0 15px 40px;
        }

        .objectives ol {
            margin: 0;
            padding-left: 18px;
        }

        .objectives li {
            margin-bottom: 8px;
        }

        /* Tabel Mahasiswa */
        .table-container {
            margin: 18px 0;
            width: 100%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11pt;
            table-layout: fixed;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px 5px;
            vertical-align: top;
            word-wrap: break-word;
        }

        th {
            background-color: #f0f0f0;
            text-align: center;
            font-weight: bold;
        }

        th:nth-child(1), td:nth-child(1) { width: 10%; text-align: center; }
        th:nth-child(2), td:nth-child(2) { width: 20%; }
        th:nth-child(3), td:nth-child(3) { width: 45%; }
        th:nth-child(4), td:nth-child(4) { width: 25%; }

        /* Penutup */
        .closing {
            margin: 20px 0;
            font-size: 11.5pt;
            text-align: justify;
            line-height: 1.6;
        }

        /* Tanda Tangan Halaman 1 */
        .signature1 {
            margin-top: 40px;
            text-align: center;
            font-size: 11.5pt;
        }

        .signature1 p {
            margin: 5px 0;
        }

        .signature-name {
            font-weight: bold;
            margin-top: 50px;
        }

        /* Halaman 2 - Hanya Tanda Tangan */
        .page2 {
            page-break-before: always;
            height: 297mm;
            position: relative;
        }

        .signature2 {
            position: absolute;
            top: 120mm;
            left: 50%;
            transform: translateX(-50%);
            text-align: center;
            width: 100%;
            font-size: 11.5pt;
        }

        .signature2 img {
            width: 80px;
            margin: 15px 0;
        }

        .signature2 .signature-name {
            margin-top: 10px;
        }

        /* Print Optimization */
        @media print {
            body, .container { margin: 0; padding: 0; }
            @page { margin: 15mm 20mm 15mm 25mm; }
        }
    </style>
</head>
<body>

<div class="container">

    <!-- Halaman 1 -->
    <div class="header">
        <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('img/logo_del.png'))) }}" alt="Logo" class="logo">
        <div class="inst-name">INSTITUT TEKNOLOGI DEL</div>
        <div class="faculty-name">FAKULTAS TEKNOLOGI INDUSTRI</div>
        <div class="address">
            Jl. Sisingamangaraja, Laguboti 22381<br>
            Toba, Sumatera Utara, Indonesia
        </div>
        <div class="contact">Telp.: (0632) 331234, Fax.: (0632) 331116</div>
        <div class="contact">info@del.ac.id, http://www.del.ac.id</div>
    </div>

    <div class="date">Laguboti, 23 Mei 2025</div>

    <div class="meta">
        <div><span class="meta-label">No</span><span>: 1162/ITDel/FTI/Dekan/Adm/KP/V/25</span></div>
        <div><span class="meta-label">Perihal</span><span>: Permohonan Kerja Praktek</span></div>
    </div>

    <div class="recipient">
        <p>Kepada Yth,</p>
        <p><strong>Bapak/Ibu {{ $request->company->nama_perusahaan ?? 'Nama Perusahaan' }}</strong></p>
        <p>{{ $request->company->alamat_perusahaan ?? 'Alamat Perusahaan' }}</p>
    </div>

    <div class="content">
        <p><strong>Dengan Hormat,</strong></p>
        <p>Fakultas Teknologi Industri (FTI), Institut Teknologi Del (IT Del) menyelenggarakan Kerja Praktik mahasiswa FTI, IT Del, untuk tahun akademik 2024/2025. Program Kerja Praktik akan dilaksanakan dimulai dari 02 Juni 2025 s/d 02 Agustus 2025 di berbagai perusahaan/lembaga di seluruh Indonesia. Kerja Praktik wajib diikuti oleh setiap mahasiswa dengan tujuan:</p>

        <div class="objectives">
            <ol>
                <li>Mengenalkan mahasiswa ke lingkungan kerja nyata, sehingga mereka dapat lebih cepat beradaptasi pada saat memasuki dunia profesi setelah menyelesaikan studinya;</li>
                <li>Memberikan kesempatan kepada mahasiswa untuk menerapkan pengetahuan dan kemampuan yang diperoleh selama belajar di IT Del, serta mendapatkan pengalaman kerja di industri;</li>
                <li>Memberikan kesempatan bagi industri untuk mengobservasi dan mengenali kapabilitas mahasiswa yang melakukan Kerja Praktik di organisasinya, sehingga bilamana diinginkan untuk merekrut dikemudian hari, telah diperoleh basis informasi yang diperlukan.</li>
            </ol>
        </div>

        <p>Sehubungan dengan hal tersebut, kami memohon kesediaan Bapak/Ibu agar dapat memberikan kesempatan kepada mahasiswa FTI, IT Del sebagai berikut:</p>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIM</th>
                        <th>Nama Mahasiswa</th>
                        <th>Program Studi</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $mahasiswaList = json_decode($request->company->mahasiswa ?? '[]', true);
                        $students = \App\Models\FtiData::whereIn('username', $mahasiswaList)->get();
                    @endphp
                    @foreach($students as $index => $student)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $student->nim ?? '-' }}</td>
                        <td>{{ $student->nama ?? '-' }}</td>
                        <td>{{ $student->prodi ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <p class="closing">
            Atas perhatian Bapak/Ibu, kami menyampaikan rasa terimakasih dan penghargaan kami atas dukungan dan kontribusi Bapak/Ibu dari {{ $request->company->nama_perusahaan ?? 'Perusahaan' }}, terhadap upaya kami dalam mencerdaskan serta mengembangkan pengetahuan dan keterampilan putra-putri bangsa yang telah kami asuh.
        </p>
    </div>

    <div class="signature1">
        <p>Hormat kami,</p>
        <p>Dekan Fakultas Teknologi Industri</p>
        <p>Institut Teknologi Del</p>
        <p class="signature-name">Dr. Fitriani Tupa Ronauli Silalahi, S.Si, M.Si</p>
    </div>

</div>

<!-- Halaman 2 -->
<div class="page2">
    <div class="signature2">
        <p>Hormat kami,</p>
        <p><strong>Dekan Fakultas Teknologi Industri</strong></p>
        <p><strong>Institut Teknologi Del</strong></p>
        <img src="https://via.placeholder.com/80x80/003366/FFFFFF?text=TTd" alt="Tanda Tangan">
        <p class="signature-name">Dr. Fitriani Tupa Ronauli Silalahi, S.Si, M.Si</p>
    </div>
</div>

</body>
</html>