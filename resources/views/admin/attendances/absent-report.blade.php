<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Siswa Tidak Hadir</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 3px solid #333;
        }

        .header h1 {
            font-size: 18px;
            margin-bottom: 5px;
            color: #1a1a1a;
        }

        .header h2 {
            font-size: 14px;
            margin-bottom: 3px;
            color: #444;
        }

        .header p {
            font-size: 10px;
            color: #666;
        }

        .info-section {
            margin-bottom: 15px;
            background: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
        }

        .info-row {
            display: flex;
            margin-bottom: 5px;
        }

        .info-label {
            font-weight: bold;
            width: 150px;
            color: #555;
        }

        .info-value {
            color: #333;
        }

        .summary-box {
            background: #fff3cd;
            border: 2px solid #ffc107;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 15px;
        }

        .summary-box h3 {
            font-size: 13px;
            margin-bottom: 8px;
            color: #856404;
        }

        .summary-stats {
            display: flex;
            justify-content: space-around;
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            font-size: 20px;
            font-weight: bold;
            color: #dc3545;
        }

        .stat-label {
            font-size: 10px;
            color: #666;
            margin-top: 3px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        thead {
            background-color: #dc3545;
            color: white;
        }

        th {
            padding: 8px;
            text-align: left;
            font-weight: bold;
            font-size: 11px;
            border: 1px solid #dee2e6;
        }

        td {
            padding: 6px 8px;
            border: 1px solid #dee2e6;
            font-size: 10px;
        }

        tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        tbody tr:hover {
            background-color: #e9ecef;
        }

        .no-data {
            text-align: center;
            padding: 30px;
            background: #d4edda;
            border: 2px solid #28a745;
            border-radius: 5px;
            color: #155724;
            font-size: 12px;
        }

        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #dee2e6;
            font-size: 9px;
            color: #666;
        }

        .signature-section {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }

        .signature-box {
            text-align: center;
            width: 45%;
        }

        .signature-line {
            margin-top: 60px;
            border-top: 1px solid #333;
            padding-top: 5px;
        }

        .page-break {
            page-break-after: always;
        }

        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ App\Models\Setting::appName() }}</h1>
        <h2>LAPORAN SISWA TIDAK HADIR</h2>
        <p>
            @if($date)
                Tanggal: {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}
            @elseif($week)
                Minggu: {{ $start->translatedFormat('d F Y') }} - {{ $end->translatedFormat('d F Y') }}
            @elseif($month)
                Bulan: {{ $start->translatedFormat('F Y') }}
            @endif
        </p>
    </div>

    <div class="info-section">
        <div class="info-row">
            <div class="info-label">Periode Laporan:</div>
            <div class="info-value">
                {{ $start->translatedFormat('d F Y') }} s/d {{ $end->translatedFormat('d F Y') }}
                ({{ $totalDays }} hari)
            </div>
        </div>
        @if($division)
            <div class="info-row">
                <div class="info-label">Divisi:</div>
                <div class="info-value">{{ App\Models\Division::find($division)->name ?? '-' }}</div>
            </div>
        @endif
        @if($jobTitle)
            <div class="info-row">
                <div class="info-label">Jabatan:</div>
                <div class="info-value">{{ App\Models\JobTitle::find($jobTitle)->name ?? '-' }}</div>
            </div>
        @endif
        <div class="info-row">
            <div class="info-label">Tanggal Cetak:</div>
            <div class="info-value">{{ now()->translatedFormat('d F Y, H:i') }} WIB</div>
        </div>
    </div>

    <div class="summary-box">
        <h3>Ringkasan</h3>
        <div class="summary-stats">
            <div class="stat-item">
                <div class="stat-number">{{ $totalStudents }}</div>
                <div class="stat-label">Total Siswa</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ $absentCount }}</div>
                <div class="stat-label">Tidak Hadir</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ number_format(($absentCount / max($totalStudents, 1)) * 100, 1) }}%</div>
                <div class="stat-label">Persentase</div>
            </div>
        </div>
    </div>

    @if($students->count() > 0)
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 15%;">NISN</th>
                    <th style="width: 25%;">Nama Siswa</th>
                    <th style="width: 15%;">Divisi</th>
                    <th style="width: 15%;">Jabatan</th>
                    <th style="width: 15%;">Pendidikan</th>
                    <th style="width: 10%;">Kontak</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $index => $student)
                    <tr>
                        <td style="text-align: center;">{{ $index + 1 }}</td>
                        <td>{{ $student->nisn ?? '-' }}</td>
                        <td><strong>{{ $student->name }}</strong></td>
                        <td>{{ $student->division->name ?? '-' }}</td>
                        <td>{{ $student->jobTitle->name ?? '-' }}</td>
                        <td>{{ $student->education->name ?? '-' }}</td>
                        <td>{{ $student->phone ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="footer">
            <p><strong>Catatan:</strong></p>
            <ul style="margin-left: 20px; margin-top: 5px;">
                <li>Laporan ini menampilkan siswa yang TIDAK MEMILIKI catatan kehadiran sama sekali dalam periode yang dipilih</li>
                <li>Siswa yang tercantum tidak melakukan absensi masuk maupun keluar selama {{ $totalDays }} hari</li>
                <li>Untuk informasi lebih detail, silakan hubungi siswa yang bersangkutan</li>
            </ul>
        </div>

        <div class="signature-section">
            <div class="signature-box">
                <p>Mengetahui,</p>
                <p><strong>Kepala Sekolah</strong></p>
                <div class="signature-line">
                    <p>(...........................)</p>
                </div>
            </div>
            <div class="signature-box">
                <p>{{ now()->translatedFormat('d F Y') }}</p>
                <p><strong>Admin</strong></p>
                <div class="signature-line">
                    <p>(...........................)</p>
                </div>
            </div>
        </div>
    @else
        <div class="no-data">
            <h3 style="margin-bottom: 10px; color: #155724;">Tidak Ada Data</h3>
            <p>Semua siswa memiliki catatan kehadiran dalam periode yang dipilih.</p>
            <p style="margin-top: 5px;">Ini adalah kabar baik! Tidak ada siswa yang tidak hadir.</p>
        </div>
    @endif
</body>
</html>
