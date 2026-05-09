<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Penghasilan</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #B33232;
        }
        
        .header h1 {
            font-size: 24px;
            color: #B33232;
            margin-bottom: 5px;
        }
        
        .header h3 {
            font-size: 16px;
            color: #666;
            margin-bottom: 5px;
        }
        
        .header p {
            font-size: 11px;
            color: #999;
        }
        
        .info-section {
            margin-bottom: 20px;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 5px;
        }
        
        .info-grid {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        
        .info-item {
            flex: 1;
        }
        
        .info-label {
            font-weight: bold;
            color: #555;
            font-size: 11px;
            margin-bottom: 3px;
        }
        
        .info-value {
            font-size: 14px;
            font-weight: bold;
            color: #B33232;
        }
        
        .summary-cards {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            gap: 15px;
        }
        
        .summary-card {
            flex: 1;
            padding: 12px;
            background: linear-gradient(135deg, #ad5d5d 0%, #B33232 100%);
            border-radius: 8px;
            color: white;
        }
        
        .summary-card-income {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        }
        
        .summary-card-primary {
            background: linear-gradient(135deg,  #ad5d5d 0%, #B33232 100%);
        }
        
        .summary-card-purple {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        
        .summary-label {
            font-size: 10px;
            opacity: 0.9;
            margin-bottom: 5px;
        }
        
        .summary-value {
            font-size: 18px;
            font-weight: bold;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        
        th {
            background: #B33232;
            color: white;
            padding: 10px;
            text-align: left;
            font-size: 11px;
            font-weight: bold;
        }
        
        td {
            padding: 8px 10px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 10px;
        }
        
        tr:hover {
            background: #f9fafb;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .font-bold {
            font-weight: bold;
        }
        
        .text-brand-primary {
            color: #B33232;
        }
        
        .text-brand-income {
            color: #10B981;
        }
        
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            font-size: 9px;
            color: #999;
        }
        
        .badge {
            display: inline-block;
            padding: 2px 6px;
            background: #e5e7eb;
            border-radius: 4px;
            font-size: 9px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>AGUNG MOTOR</h1>
        <h3>Laporan Penghasilan</h3>
        <p>Jl. Karangmulya II No.74, Drajat, Kec. Kesambi, Kota Cirebon, Jawa Barat 45133</p>
        <p>Telp: 0853-2305-7009 | Email: info@agungmotor.com</p>
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
            @if($search)
            <div class="info-item">
                <div class="info-label">Pencarian</div>
                <div class="info-value">{{ $search }}</div>
            </div>
            @endif
        </div>
    </div>

    <div class="summary-cards">
        <div class="summary-card summary-card-income">
            <div class="summary-label">Total Pendapatan</div>
            <div class="summary-value">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
        </div>
        <div class="summary-card summary-card-primary">
            <div class="summary-label">Total Transaksi</div>
            <div class="summary-value">{{ number_format($totalTransaksi, 0, ',', '.') }}</div>
        </div>
        <div class="summary-card summary-card-purple">
            <div class="summary-label">Rata-rata Transaksi</div>
            <div class="summary-value">
                Rp {{ $totalTransaksi > 0 ? number_format($totalPendapatan / $totalTransaksi, 0, ',', '.') : '0' }}
            </div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th>Tanggal</th>
                <th>Plat Nomor</th>
                <th>Nama Pemilik</th>
                <th>Service</th>
                <th>Sparepart</th>
                <th class="text-right">Harga Sparepart</th>
                <th class="text-right">Biaya Jasa</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($penghasilan as $i => $item)
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                <td class="font-bold">{{ $item->plat_nomor }}</td>
                <td>{{ $item->nama_pemilik }}</td>
                <td>{{ Str::limit($item->service, 30) }}</td>
                <td>{{ $item->sparepart ?: '-' }}</td>
                <td class="text-right">Rp {{ number_format($item->harga_sparepart, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($item->biaya_jasa, 0, ',', '.') }}</td>
                <td class="text-right font-bold text-brand-income">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center">Tidak ada data transaksi</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr style="background: #f3f4f6; font-weight: bold;">
                <td colspan="6" class="text-right">TOTAL:</td>
                <td class="text-right">Rp {{ number_format($totalHargaSparepart, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($totalBiayaJasa, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Dicetak secara otomatis oleh sistem Agung Motor</p>
        <p>&copy; {{ date('Y') }} Agung Motor - All rights reserved</p>
    </div>
</body>
</html>