<!DOCTYPE html>
<html>

<head>
    <title>Peringatan Pengumpulan LPJ</title>
</head>

<body>
    <h1>Peringatan Pengumpulan LPJ</h1>

    <p>Yth. {{ $data['project_leader'] }},</p>

    <p>Ini adalah pengingat untuk mengumpulkan Laporan Pertanggungjawaban (LPJ) terkait kegiatan
        <strong>{{ $data['event_name'] }}</strong>.
    </p>

    <p><strong>Informasi Kegiatan:</strong></p>
    <ul>
        <li>Nama Kegiatan: {{ $data['event_name'] }}</li>
        <li>Lokasi: {{ $data['location'] }}</li>
        <li>Organisasi Penyelenggara: {{ $data['organization'] }}</li>
        <li>Batas Waktu LPJ: {{ \Carbon\Carbon::parse($data['end_date'])->format('d M Y') }}</li>
    </ul>

    <p>Silakan segera kumpulkan LPJ Anda untuk kegiatan ini. Jika LPJ telah dikumpulkan, abaikan email ini.</p>

    <p>Terima kasih,</p>
    <p>{{ config('app.name') }}</p>
</body>

</html>
