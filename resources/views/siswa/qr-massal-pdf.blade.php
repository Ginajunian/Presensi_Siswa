<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: sans-serif; margin: 0; }
        table.grid { width: 100%; border-collapse: collapse; }
        table.grid td { width: 33.33%; padding: 8px; vertical-align: top; }
        .kartu { border: 1px solid #ccc; border-radius: 6px; padding: 12px; text-align: center; }
        .kartu .foto { width: 48px; height: 48px; border-radius: 50%; object-fit: cover; margin-bottom: 4px; }
        .kartu .avatar-kosong {
            width: 48px; height: 48px; border-radius: 50%; background: #F0F9F4;
            display: inline-block; line-height: 48px; color: #297C49; font-size: 16px; font-weight: bold;
        }
        .kartu h4 { margin: 4px 0 0; font-size: 12px; }
        .kartu p { margin: 0; font-size: 10px; color: #777; }
        .kartu .qr { width: 110px; margin-top: 8px; }
    </style>
</head>
<body>
    <table class="grid">
        <tr>
        @foreach ($kartuData as $i => $item)
            @if ($i > 0 && $i % 3 === 0)
        </tr><tr>
            @endif
            <td>
                <div class="kartu">
                    @if ($item['foto_base64'])
                        <img class="foto" src="data:image/jpeg;base64,{{ $item['foto_base64'] }}">
                    @else
                        <span class="avatar-kosong">{{ strtoupper(substr($item['siswa']->nama, 0, 1)) }}</span>
                    @endif
                    <h4>{{ $item['siswa']->nama }}</h4>
                    <p>NIS: {{ $item['siswa']->nis }}</p>
                    <p>{{ $item['siswa']->kelas->nama_kelas }}</p>
                    <img class="qr" src="data:image/png;base64,{{ $item['qr_base64'] }}">
                </div>
            </td>
        @endforeach
        </tr>
    </table>
</body>
</html>