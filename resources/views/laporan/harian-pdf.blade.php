<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        h2 { margin-bottom: 2px; }
        p { margin-top: 0; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #ccc; padding: 6px 8px; text-align: left; }
        th { background: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Rekap Presensi Harian</h2>
    <p>Kelas: {{ $kelas->nama_kelas }} &mdash; Tanggal: {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}</p>

    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Status</th>
                <th>Jam Masuk</th>
                <th>Jam Pulang</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $row)
                @php $p = $row['presensi']; @endphp
                <tr>
                    <td>{{ $row['siswa']->nama }}</td>
                    <td>{{ $p ? ucfirst($p->status) : 'Belum tercatat' }}</td>
                    <td>{{ $p?->waktu_masuk ? \Carbon\Carbon::parse($p->waktu_masuk)->format('H:i') : '-' }}</td>
                    <td>{{ $p?->waktu_pulang ? \Carbon\Carbon::parse($p->waktu_pulang)->format('H:i') : '-' }}</td>
                    <td>{{ $p?->keterangan ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>