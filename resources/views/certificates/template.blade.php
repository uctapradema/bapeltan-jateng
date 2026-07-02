<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: 'Times New Roman', serif;
            margin: 0;
            padding: 0;
        }
        .certificate {
            width: 297mm;
            height: 210mm;
            padding: 20mm;
            box-sizing: border-box;
            position: relative;
            border: 3px double #1a5632;
        }
        .header {
            text-align: center;
            margin-bottom: 10mm;
        }
        .logo {
            font-size: 14pt;
            font-weight: bold;
            color: #1a5632;
            margin-bottom: 2mm;
        }
        .instansi {
            font-size: 10pt;
            color: #333;
        }
        .title {
            text-align: center;
            font-size: 22pt;
            font-weight: bold;
            color: #1a5632;
            margin: 8mm 0;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .nomor {
            text-align: center;
            font-size: 11pt;
            color: #666;
            margin-bottom: 8mm;
        }
        .content {
            font-size: 12pt;
            line-height: 1.8;
            text-align: justify;
            margin: 0 15mm;
        }
        .content strong {
            color: #1a5632;
        }
        .name {
            text-align: center;
            font-size: 18pt;
            font-weight: bold;
            color: #1a5632;
            margin: 5mm 0;
            text-decoration: underline;
        }
        .detail {
            text-align: center;
            font-size: 11pt;
            color: #555;
            margin-bottom: 5mm;
        }
        .sign-section {
            display: flex;
            justify-content: space-between;
            margin-top: 15mm;
            padding: 0 15mm;
        }
        .sign-block {
            text-align: center;
            width: 30%;
        }
        .sign-block .title {
            font-size: 10pt;
            margin-bottom: 20mm;
        }
        .sign-block .name {
            font-size: 11pt;
            font-weight: bold;
            text-decoration: none;
        }
        .sign-block .nip {
            font-size: 9pt;
            color: #666;
        }
        .footer {
            text-align: center;
            font-size: 8pt;
            color: #999;
            margin-top: 10mm;
            border-top: 1px solid #ddd;
            padding-top: 3mm;
        }
        .stamp {
            position: absolute;
            bottom: 40mm;
            right: 25mm;
            width: 30mm;
            height: 30mm;
            opacity: 0.15;
        }
    </style>
</head>
<body>
    <div class="certificate">
        <div class="header">
            <div class="logo">BALAI PELATIHAN PERTANIAN</div>
            <div class="instansi">JAWA TENGAH</div>
        </div>

        <div class="title">SERTIFIKAT</div>
        <div class="nomor">Nomor: {{ $nomor }}</div>

        <div class="content">
            Dengan ini menyatakan bahwa
            <div class="name">{{ $peserta->nama }}</div>
            <div class="detail">
                NIK: {{ $peserta->nik }} | Kabupaten: {{ $peserta->kabupaten->nama ?? '-' }}
            </div>

            telah berhasil menyelesaikan pelatihan
            <strong>{{ $kegiatan->nama_pelatihan }}</strong>
            dengan kode <strong>{{ $kegiatan->kode_pelatihan }}</strong>
            yang diselenggarakan oleh Balai Pelatihan Pertanian Jawa Tengah
            pada tanggal {{ $kegiatan->tanggal_mulai->format('d M Y') }}
            s/d {{ $kegiatan->tanggal_selesai->format('d M Y') }}
            dengan total durasi {{ $kegiatan->tanggal_mulai->diffInDays($kegiatan->tanggal_selesai) + 1 }} hari.
        </div>

        <div class="sign-section">
            <div class="sign-block">
                <div class="title">Mengetahui,<br>Kepala Balai Pelatihan Pertanian</div>
                <div class="nip">NIP. ...................</div>
            </div>
            <div class="sign-block">
                <div class="title">&nbsp;</div>
            </div>
            <div class="sign-block">
                <div class="title">Semarang, {{ now()->format('d M Y') }}<br>Kepala Bidang Pelatihan</div>
                <div class="nip">NIP. ...................</div>
            </div>
        </div>

        <div class="footer">
            Sertifikat ini diterbitkan secara digital oleh Sistem Informasi Manajemen Pelatihan Bapeltan Jawa Tengah
        </div>
    </div>
</body>
</html>
