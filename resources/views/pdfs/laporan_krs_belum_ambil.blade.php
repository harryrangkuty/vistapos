<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Mahasiswa Belum Ambil KRS</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0px 5px;
            color: #000;
        }

        /* Header */
        .header-table {
            width: 100%;
            border-bottom: 3px solid #004aad;
            background: #e0ebff;
            margin-bottom: 15px;
        }

        .header-table td {
            vertical-align: middle;
        }

        .header-title h2 {
            margin: 0;
            font-size: 22px;
            color: #004aad;
        }

        .header-title h3 {
            margin: 0;
            font-size: 14px;
            color: #333;
        }

        /* Statistik */
        .stat-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 12px;
        }

        .stat-table th {
            background-color: #003366;
            color: #fff;
            border: 1px solid #ccc;
            padding: 6px;
            text-align: left;
        }

        .stat-table td {
            border: 1px solid #ccc;
            padding: 6px;
        }

        .status-badge {
            padding: 4px 8px;
            border-radius: 6px;
            font-weight: bold;
            display: inline-block;
            color: #fff;
        }

        /* Tabel Mahasiswa */
        table.krs-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table.krs-table th {
            background-color: #ffd54f;
            padding: 8px;
            text-align: center;
            border-bottom: 1px solid #000;
        }

        table.krs-table td {
            padding: 6px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table.krs-table td.text-center {
            text-align: center;
        }

        table.krs-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>
</head>

<body>
    <div style="text-align:right; font-size:11px; color:#555; margin-top:20px;">
        Laporan Dicetak pada {{ now()->translatedFormat('d F Y, H:i') }} WIB
    </div>
    {{-- Header --}}
    <table class="header-table">
        <tr>
            <td style="width:100px; text-align:center;">
                <img src="{{ public_path('images/main_logo.webp') }}" alt="Logo" style="height:80px;">
            </td>
            <td class="header-title" style="text-align:center;">
                <h2>Laporan Mahasiswa Belum Ambil KRS</h2>
                <h3>Periode {{ academic_year()['academic_year'] }} ({{ academic_year()['term'] }})</h3>
            </td>
            <td style="width:100px;"></td>
        </tr>
    </table>

    {{-- Statistik --}}
    <table class="stat-table">
        <thead>
            <tr>
                <th>Program Studi</th>
                <th>Total Data</th>
            </tr>
        </thead>
        <tbody>
            <tr style="background-color:#e6f0ff;">
                <td>{{ $unitName ?? 'Semua Program Studi' }}</td>
                <td>{{ $totalData }}</td>
            </tr>
        </tbody>
    </table>

    {{-- Tabel Mahasiswa --}}
    <table class="krs-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Mahasiswa</th>
                <th>NIM</th>
                <th>Program Studi</th>
                <th>Penasihat Akademik</th>
                <th class="text-center">Semester</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $i => $user)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->identifier }}</td>
                    <td>{{ $user->unit?->name ?? '-' }}</td>
                    <td>{{ $user->academicAdvisor?->name ?? 'Belum Diset Operator VISTA POS' }}</td>
                    <td class="text-center">{{ $user->semester ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
