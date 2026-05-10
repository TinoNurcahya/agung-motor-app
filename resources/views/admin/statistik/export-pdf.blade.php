<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Statistik - Agung Motor</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Arial', sans-serif; font-size: 12px; padding: 20px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #B33232; padding-bottom: 10px; }
        .header h1 { color: #B33232; }
        .summary-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; margin: 20px 0; }
        .summary-card { padding: 15px; text-align: center; background: #f8f9fa; border-radius: 8px; }
        .summary-card h3 { font-size: 20px; margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #B33232; color: white; }
        .footer { margin-top: 30px; text-align: center; font-size: 10px; color: #999; }
    </style>
</head>
<body>
    <div class="header">
        <h1>AGUNG MOTOR</h1>
        <h3>Laporan Statistik Keuangan</h3>
        <p>Periode: {{ $period }} hari terakhir | Export: {{ $exportDate }}</p>
    </div>

    <div class="summary-grid">
        <div class="summary-card">
            <p>Total Penghasilan</p>
            <h3>Rp {{ number_format($summary['total_penghasilan'], 0, ',', '.') }}</h3>
        </div>
        <div class="summary-card">
            <p>Total Pengeluaran</p>
            <h3>Rp {{ number_format($summary['total_pengeluaran'], 0, ',', '.') }}</h3>
        </div>
        <div class="summary-card">
            <p>Laba Bersih</p>
            <h3>Rp {{ number_format($summary['laba_bersih'], 0, ',', '.') }}</h3>
        </div>
        <div class="summary-card">
            <p>Margin Profit</p>
            <h3>{{ number_format($summary['margin_profit'], 1) }}%</h3>
        </div>
    </div>

    <h3>Top Layanan</h3>
    <table>
        <thead>
            <tr><th>Layanan</th><th>Jumlah Servis</th><th>Persentase</th></tr>
        </thead>
        <tbody>
            @foreach($topServices as $service)
            <tr><td>{{ $service['name'] }}</td><td>{{ $service['count'] }}</td><td>{{ $service['width'] }}</td></tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak oleh sistem Agung Motor | {{ date('Y') }} &copy; Agung Motor</p>
    </div>
</body>
</html>