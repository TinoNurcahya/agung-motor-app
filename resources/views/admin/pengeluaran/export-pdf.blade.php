<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Pengeluaran</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Arial', sans-serif; font-size: 12px; padding: 20px; }
        .header { text-align: center; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #EF4444; }
        .header h1 { font-size: 24px; color: #B33232; margin-bottom: 5px; }
        .header h3 { font-size: 16px; color: #666; margin-bottom: 5px; }
        .header p { font-size: 11px; color: #999; }
        .info-section { margin-bottom: 20px; padding: 10px; background: #f8f9fa; border-radius: 5px; }
        .info-grid { display: flex; justify-content: space-between; margin-bottom: 10px; }
        .info-item { flex: 1; }
        .info-label { font-weight: bold; color: #555; font-size: 11px; margin-bottom: 3px; }
        .info-value { font-size: 14px; font-weight: bold; color: #B33232; }
        .summary-cards { display: flex; justify-content: space-between; margin-bottom: 20px; gap: 15px; }
        .summary-card { flex: 1; padding: 12px; border-radius: 8px; color: white; }
        .summary-card-expense { background: linear-gradient(135deg, #B33232 0%, #DC2626 100%); }
        .summary-card-orange { background: linear-gradient(135deg, #F97316 0%, #EA580C 100%); }
        .summary-card-yellow { background: linear-gradient(135deg, #FBBF24 0%, #D97706 100%); }
        .summary-label { font-size: 10px; opacity: 0.9; margin-bottom: 5px; }
        .summary-value { font-size: 18px; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background: #B33232; color: white; padding: 10px; text-align: left; font-size: 11px; }
        td { padding: 8px 10px; border-bottom: 1px solid #e5e7eb; font-size: 10px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .footer { margin-top: 20px; padding-top: 10px; border-top: 1px solid #e5e7eb; text-align: center; font-size: 9px; color: #999; }
        .badge { display: inline-block; padding: 2px 6px; background: #e5e7eb; border-radius: 4px; font-size: 9px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>AGUNG MOTOR</h1>
        <h3>Laporan Pengeluaran</h3>
        <p>Jl. Karangmulya II No.74, Drajat, Kec. Kesambi, Kota Cirebon, Jawa Barat 45133 | Telp: 0853-2305-7009</p>
    </div>

    <div class="info-section">
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Periode Laporan</div>
                <div class="info-value">{{ $filterText }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Tanggal Export</div>
                <div class="info-value">{{ $exportDate }}</div>
            </div>
        </div>
    </div>

    <div class="summary-cards">
        <div class="summary-card summary-card-expense">
            <div class="summary-label">Total Pengeluaran</div>
            <div class="summary-value">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</div>
        </div>
        <div class="summary-card summary-card-orange">
            <div class="summary-label">Belanja Stok</div>
            <div class="summary-value">Rp {{ number_format($totalStokBarang, 0, ',', '.') }}</div>
        </div>
        <div class="summary-card summary-card-yellow">
            <div class="summary-label">Operasional</div>
            <div class="summary-value">Rp {{ number_format($totalOperasional, 0, ',', '.') }}</div>
        </div>
    </div>

    <table>
        <thead>
            <tr><th class="text-center">No</th><th>Tanggal</th><th>Keterangan</th><th>Kategori</th><th class="text-right">Nominal</th></tr>
        </thead>
        <tbody>
            @forelse($pengeluaran as $i => $item)
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                <td>{{ $item->keterangan }}</td>
                <td>{{ $item->kategori }}</td>
                <td class="text-right font-bold">Rp {{ number_format($item->nominal, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr><td colspan="5" class="text-center">Tidak ada data</td></tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr style="background: #f3f4f6;">
                <td colspan="4" class="text-right font-bold">TOTAL:</td>
                <td class="text-right font-bold">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Dicetak otomatis oleh sistem Agung Motor | {{ date('Y') }} &copy; Agung Motor</p>
    </div>
</body>
</html>