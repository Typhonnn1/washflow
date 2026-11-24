<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan & Rekap Transaksi - WashFlow</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 24px;
            background: #fff;
            font-size: 12px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .header p {
            margin: 4px 0 0 0;
            color: #666;
            font-size: 11px;
        }
        .meta-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 16px;
            font-size: 11px;
        }
        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin-bottom: 20px;
        }
        .metric-box {
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 4px;
            background: #fafafa;
        }
        .metric-title {
            font-size: 10px;
            color: #666;
            text-transform: uppercase;
        }
        .metric-value {
            font-size: 16px;
            font-weight: bold;
            margin-top: 4px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            font-size: 11px;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
            padding-top: 20px;
        }
        .sign-box {
            text-align: center;
            width: 200px;
        }
        .sign-space {
            height: 60px;
        }
        .no-print {
            margin-bottom: 20px;
            padding: 10px;
            background: #eef2ff;
            border: 1px solid #c7d2fe;
            border-radius: 6px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .btn {
            background: #2563eb;
            color: white;
            padding: 6px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
        }
        @media print {
            .no-print { display: none; }
            body { padding: 0; }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <div>
            <strong>Pratinjau Cetak Laporan Keuangan WashFlow</strong>
            <span style="color: #666; margin-left: 8px;">Klik tombol cetak untuk mencetak atau simpan ke PDF.</span>
        </div>
        <button class="btn" onclick="window.print()" style="display: inline-flex; align-items: center; gap: 6px;">
            <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
            </svg>
            Cetak Dokumen
        </button>
    </div>

    <div class="header">
        <h1>SISTEM INFORMASI MANAJEMEN WASHFLOW</h1>
        <p>Jl. Raya Kampus No. 123 | Telp / WhatsApp: 0812-3456-7890 | Email: info@washflow.com</p>
        <p style="font-weight: bold; font-size: 13px; margin-top: 8px; color: #111;">REKAPITULASI TRANSAKSI DAN LAPORAN KEUANGAN</p>
    </div>

    <div class="meta-info">
        <div>
            <strong>Periode Laporan:</strong> 
            {{ $dariTanggal ? \Carbon\Carbon::parse($dariTanggal)->format('d/m/Y') : 'Awal' }} s/d 
            {{ $sampaiTanggal ? \Carbon\Carbon::parse($sampaiTanggal)->format('d/m/Y') : 'Sekarang' }}
            <br>
            <strong>Filter Layanan:</strong> {{ $layananFilter ? $layananFilter->nama_layanan : 'Semua Jenis Layanan' }}
        </div>
        <div class="text-right">
            <strong>Dicetak Pada:</strong> {{ now()->format('d/m/Y H:i') }} WIB<br>
            <strong>Petugas:</strong> {{ auth()->user()->name }} ({{ auth()->user()->role_label }})
        </div>
    </div>

    <!-- Ringkasan Statistik -->
    <div class="metrics-grid">
        <div class="metric-box">
            <div class="metric-title">Total Transaksi</div>
            <div class="metric-value">{{ number_format($totalTransaksi) }}</div>
        </div>
        <div class="metric-box">
            <div class="metric-title">Total Berat Cucian</div>
            <div class="metric-value">{{ number_format($totalBerat, 1, ',', '.') }} kg</div>
        </div>
        <div class="metric-box">
            <div class="metric-title">Total Pendapatan Lunas</div>
            <div class="metric-value" style="color: #15803d;">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
        </div>
        <div class="metric-box">
            <div class="metric-title">Belum Lunas (Piutang)</div>
            <div class="metric-value" style="color: #b91c1c;">Rp {{ number_format($totalBelumLunas, 0, ',', '.') }}</div>
        </div>
    </div>

    <!-- Tabel Data Transaksi -->
    <table>
        <thead>
            <tr>
                <th class="text-center" style="width: 30px;">No</th>
                <th>Kode Transaksi</th>
                <th>Tgl Masuk</th>
                <th>Nama Pelanggan</th>
                <th>Jenis Layanan</th>
                <th class="text-center">Berat (kg)</th>
                <th class="text-right">Total Biaya</th>
                <th class="text-center">Status</th>
                <th class="text-center">Pembayaran</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transaksis as $i => $item)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td style="font-family: monospace; font-weight: bold;">{{ $item->kode_transaksi }}</td>
                    <td>{{ $item->tgl_masuk ? $item->tgl_masuk->format('d/m/Y H:i') : '-' }}</td>
                    <td>{{ $item->nama_pelanggan }} ({{ $item->no_hp }})</td>
                    <td>{{ $item->layanan?->nama_layanan ?? '-' }}</td>
                    <td class="text-center">{{ $item->berat_formatted }}</td>
                    <td class="text-right">{{ $item->harga_rupiah }}</td>
                    <td class="text-center">{{ $item->status }}</td>
                    <td class="text-center" style="font-weight: bold; color: {{ $item->status_pembayaran === 'Lunas' ? '#15803d' : '#b91c1c' }};">
                        {{ $item->status_pembayaran }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center">Tidak ada data transaksi pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr style="background: #f8fafc; font-weight: bold;">
                <td colspan="5" class="text-right">TOTAL KESELURUHAN:</td>
                <td class="text-center">{{ number_format($totalBerat, 2, ',', '.') }} kg</td>
                <td class="text-right">Rp {{ number_format($transaksis->sum('total_harga'), 0, ',', '.') }}</td>
                <td colspan="2" class="text-center">Lunas: Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="signatures">
        <div class="sign-box">
            <div>Mengetahui,</div>
            <div><strong>Pimpinan / Owner WashFlow</strong></div>
            <div class="sign-space"></div>
            <div>( _____________________ )</div>
        </div>
        <div class="sign-box">
            <div>Dicetak Oleh,</div>
            <div><strong>{{ auth()->user()->role_label }}</strong></div>
            <div class="sign-space"></div>
            <div>( <strong>{{ auth()->user()->name }}</strong> )</div>
        </div>
    </div>

</body>
</html>
