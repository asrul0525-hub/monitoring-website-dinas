<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoring Website Dinas - Kominfo</title>
</head>
<body>
    <h1>Dashboard Monitoring Website Dinas</h1>
    <ul>
        <li>Total Dinas: {{ $stats['total_dinas'] }}</li>
        <li>Aktif: {{ $stats['aktif'] }}</li>
        <li>Kurang Aktif: {{ $stats['kurang_aktif'] }}</li>
        <li>Pasif: {{ $stats['pasif'] }}</li>
    </ul>

    <h2>Daftar Dinas</h2>
    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>Nama Dinas</th>
                <th>Klaster</th>
                <th>Post Terakhir</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dinasList as $dinas)
            <tr>
                <td>{{ $dinas->nama_dinas }} ({{ $dinas->singkatan }})</td>
                <td>{{ $dinas->klaster ? $dinas->klaster->nama_klaster : '-' }}</td>
                <td>{{ $dinas->last_post_title ?? 'Belum ada data' }}</td>
                <td><strong>{{ strtoupper($dinas->status) }}</strong></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>