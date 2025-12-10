<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan KRS per Prodi</title>
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

        /* Info Prodi */
        .prodi-info {
            margin-bottom: 15px;
        }

        .prodi-info span {
            font-weight: bold;
        }

        /* Tabel KRS */
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

        table.krs-table tr.total-row td {
            font-weight: bold;
            border-top: 2px solid #000;
        }

        .tag-submitted {
            background-color: #3490dc;
        }

        .tag-validated {
            background-color: #38c172;
        }

        .tag-approved {
            background-color: #6c757d;
        }

        .tag-rejected {
            background-color: #e3342f;
        }

        .tag-all {
            background-color: #999;
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
                <img src="{{ public_path('images/vista_logo.webp') }}" alt="Logo" style="height:80px;">
            </td>
            <td class="header-title" style="text-align:center;">
                <h2>Laporan Pengajuan KRS</h2>
            </td>
            <td style="width:100px;"></td>
        </tr>
    </table>

    {{-- Statistik --}}
    {{-- Statistik --}}
    <table
        style="width:100%; border-collapse:collapse; margin-bottom:15px; font-size:12px; font-family:Arial, sans-serif;">
        <thead>
            <tr>
                <th style="background-color:#003366; color:#fff; border:1px solid #ccc; padding:6px; text-align:left;">
                    Program Studi</th>
                <th style="background-color:#003366; color:#fff; border:1px solid #ccc; padding:6px; text-align:left;">
                    Status</th>
                <th style="background-color:#003366; color:#fff; border:1px solid #ccc; padding:6px; text-align:left;">
                    Total Data</th>
            </tr>
        </thead>
        <tbody>
            <tr style="background-color:#e6f0ff;">
                <td style="border:1px solid #ccc; padding:6px;">{{ $unitName ?? 'Semua Program Studi' }}</td>
                <td style="border:1px solid #ccc; padding:6px;">
                    @php
                        $statusText = match ($statusFilter ?? 'all') {
                            'submitted' => 'DIAJUKAN',
                            'validated' => 'DIVALIDASI',
                            'approved' => 'DISAHKAN',
                            'rejected' => 'DITOLAK',
                            default => 'Semua Status',
                        };

                        $statusClass = match ($statusFilter ?? 'all') {
                            'submitted' => '#3490dc',
                            'validated' => '#38c172',
                            'approved' => '#6c757d',
                            'rejected' => '#e3342f',
                            default => '#999999',
                        };
                    @endphp
                    <span
                        style="background-color:{{ $statusClass }}; color:#fff; padding:4px 8px; border-radius:6px; font-weight:bold; display:inline-block;">
                        {{ $statusText }}
                    </span>
                </td>
                <td style="border:1px solid #ccc; padding:6px;">{{ $totalData }}</td>
            </tr>
        </tbody>
    </table>

    {{-- Tabel KRS --}}
    <table class="krs-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Mahasiswa</th>
                <th>NIM</th>
                <th>Program Studi</th>
                <th>Penasihat Akademik</th>
                <th class="text-center">Semester</th>
                <th class="text-center">Total MK</th>
                <th class="text-center">Total SKS</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $i => $krs)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td>{{ $krs->student->name ?? '-' }}</td>
                    <td>{{ $krs->student->identifier ?? '-' }}</td>
                    <td>{{ $krs->student->unit->name ?? '-' }}</td>
                    <td>{{ $krs->student->academicAdvisor->name ?? '-' }}</td>
                    <td class="text-center">{{ $krs->semester }} ({{ $krs->term }} {{ $krs->academic_year }})</td>
                    <td class="text-center">{{ $krs->total_matkul ?? 0 }}</td>
                    <td class="text-center">{{ $krs->total_sks ?? 0 }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
