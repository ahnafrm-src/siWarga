<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Data Anggota Keluarga</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        h2 {
            text-align: center;
            margin-bottom: 4px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 6px 8px;
            text-align: left;
        }

        th {
            background-color: #eee;
        }
    </style>
</head>

<body>

    <h2>Data Anggota Keluarga</h2>
    <h3>filter:
        @if (!empty(array_filter($filters)))
            @foreach (array_filter($filters) as $filter)
                {{ $filter }},
            @endforeach
        @endif
    </h3>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Jenis Kelamin</th>
                <th>Tanggal Lahir</th>
                <th>Kelompok Usia</th>
                <th>Hubungan ke KK</th>
                <th>Nama Kepala Keluarga</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $anggota)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $anggota->nama }}</td>
                    <td>{{ $anggota->jenis_kelamin == 'l' ? 'laki-laki' : 'perempuan' }}</td>
                    <td>{{ \Carbon\Carbon::parse($anggota->tanggal_lahir)->format('d-m-y') }}</td>
                    <td>{{ $anggota->kelompokUsia }}</td>
                    <td>{{ $anggota->hubungan_ke_kk }}</td>
                    <td>{{ $anggota->kepala->nama_kepala ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center;">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>

</html>
