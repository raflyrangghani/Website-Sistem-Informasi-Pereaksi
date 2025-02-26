<!DOCTYPE html>
<html>
<head>
    <title>Laporan Penggunaan Reagent</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 90%;
            margin: 20px auto;
        }
        .header {
            display: flex;
            align-items: flex-start; /* Pastikan logo dan kop surat sejajar di bagian atas */
            margin-bottom: 20px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }
        .header img {
            width: 250px; /* Sesuaikan ukuran logo agar lebih proporsional */
            height: auto;
            margin-right: 20px;
        }
        .header .company-info {
            flex-grow: 1; /* Biarkan kop surat mengisi sisa ruang yang tersedia */
            text-align: left;
            max-width: 75%; /* Batasi lebar agar tidak terlalu panjang */
        }
        .header .company-info h1 {
            font-size: 18px;
            color: #333;
            margin: 0 0 5px;
        }
        .header .company-info p {
            font-size: 12px;
            color: #666;
            margin: 2px 0;
        }
        .report-title {
            text-align: center;
            margin: 20px 0;
        }
        .report-title h2 {
            font-size: 18px;
            color: #333;
            margin: 0;
        }
        .report-title p {
            font-size: 12px;
            color: #666;
            margin: 5px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #0c107a;
            color: white;
            text-align: center;
        }
        .footer {
            position: fixed;
            bottom: 20px;
            width: 90%;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ public_path('images/logo_kop.png') }}" alt="Logo">
        </div>

        <div class="report-title">
            <h2>Laporan Penggunaan Reagent</h2>
            <p>Periode: {{ \Carbon\Carbon::parse($start_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($end_date)->format('d M Y') }}</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Nama Reagent</th>
                    <th>Total Penggunaan (Gram)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($summaries as $summary)
                    <tr>
                        <td>{{ $summary->nama_reagent }}</td>
                        <td>{{ number_format($summary->total_penggunaan, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p>Laporan ini dihasilkan oleh Laboratory Management System</p>
        <p>Dicetak pada: {{ now()->format('d M Y H:i:s') }} | Halaman {PAGE_NUM} dari {PAGE_COUNT}</p>
    </div>
</body>
</html>
