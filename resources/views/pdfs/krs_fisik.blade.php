<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>KRS {{ $krs->student->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0px 5px;
            color: #000;
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
    </style>
</head>

<body>

    {{-- header --}}
    <table style="width:100%; border-bottom:3px solid #004aad; background:#e0ebff; padding:5px;">
        <tr>
            <!-- Logo kiri -->
            <td style="width:100px; text-align:center; vertical-align:middle;">
                <img src="{{ public_path('images/main_logo.webp') }}" alt="Logo" style="height:80px;">
            </td>

            <!-- Teks header tengah -->
            <td style="text-align:center; vertical-align:middle;">
                <h2 style="margin:0; font-size:24px; color:#004aad;">Rumah Sakit Bunda Thamrin</h2>
                <h3 style="margin:0; font-size:16px; color:#333;">KARTU RENCANA STUDI (KRS) MAHASISWA</h3>
            </td>

            <!-- Kolom kanan kosong -->
            <td style="width:100px;"></td>
        </tr>
    </table>

    <!-- Info Mahasiswa + Foto -->
    <table style="width:100%; margin-top:15px; margin-bottom:20px;">
        <tr>
            <!-- Kolom info mahasiswa -->
            <td
                style="vertical-align:top; border:1px solid #004aad; border-radius:5px; padding:10px; background:#f0f4ff;">
                <table style="width:100%; border-collapse:collapse;">
                    <tr>
                        <td style="width:30%;"><strong>Nama</strong></td>
                        <td>: {{ $krs->student->name }}</td>
                    </tr>
                    <tr>
                        <td><strong>NIM</strong></td>
                        <td>: {{ $krs->student->identifier }}</td>
                    </tr>
                    <tr>
                        <td><strong>Semester / TA</strong></td>
                        <td>: {{ $krs->semester }} / {{ $krs->academic_year }} ({{ $krs->term }})</td>
                    </tr>
                    <tr>
                        <td><strong>Program Studi</strong></td>
                        <td>: {{ $krs->student->unit->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Penasihat Akademik</strong></td>
                        <td>: </td>
                    </tr>
                </table>
            </td>

            <!-- Kolom Foto 4x6 -->
            <td style="width:110px; vertical-align:top; text-align:right; padding-left:10px;">
                <div
                    style="border:2px dashed #000; width:100px; height:130px; line-height:130px; font-size:12px; color:#555; text-align:center;">
                    Foto 3x4
                </div>
            </td>
        </tr>
    </table>

    <!-- Tabel KRS -->
    <table class="krs-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Mata Kuliah</th>
                <th>SKS</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
                $totalSks = 0;
            @endphp
            @foreach ($krs->details as $detail)
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td class="text-center">{{ $detail->curriculumCourse?->course?->code ?? '-' }}</td>
                    <td>{{ $detail->curriculumCourse?->course?->name ?? '-' }}</td>
                    <td class="text-center">{{ $detail->curriculumCourse?->sks ?? 0 }}</td>
                    <td class="text-center" style="text-align:center; padding:6px;">
                        @if ($detail->curriculumCourse?->is_mandatory)
                            <span
                                style="background-color:#4caf50; color:#fff; padding:2px 6px; border-radius:4px; font-size:11px;">Wajib</span>
                        @else
                            <span
                                style="background-color:#ff9800; color:#fff; padding:2px 6px; border-radius:4px; font-size:11px;">Pilihan</span>
                        @endif
                    </td>
                </tr>
                @php $totalSks += $detail->curriculumCourse?->sks ?? 0; @endphp
            @endforeach
            <tr class="total-row">
                <td colspan="3" class="text-center">Jumlah SKS</td>
                <td class="text-center">{{ $totalSks }}</td>
                <td></td>
            </tr>
        </tbody>
    </table>

    <!-- Footer / TTD -->
    <table style="width:100%; margin-top:50px; text-align:center;">
        <tr>
            <!-- Kolom Penasihat -->
            <td style="width:50%; vertical-align:top; text-align:center;">
                <div>Disetujui oleh,</div>
                <div>Penasihat Akademik</div>

                <!-- Spacer untuk tanda tangan -->
                <div style="height:80px;"></div>

                <!-- Garis tanda tangan -->
                <div style="display:inline-block; width:150px; border-top:1px solid #000; margin-top:5px;"></div>
            </td>

            <!-- Kolom Mahasiswa -->
            <td style="width:50%; vertical-align:top; text-align:center;">
                <div>Kota Medan, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</div>
                <div>Mahasiswa</div>

                <!-- Spacer untuk tanda tangan -->
                <div style="height:80px;"></div>

                <!-- Nama -->
                <div style="font-weight:bold;">{{ $krs->student->name }}</div>

                <!-- Garis tanda tangan -->
                <div style="width:150px; border-top:1px solid #000; margin:5px auto;"></div>

                <!-- NIM -->
                <div style="margin-top:5px;">NIM: {{ $krs->student->identifier }}</div>
            </td>
        </tr>
    </table>


</body>

</html>
