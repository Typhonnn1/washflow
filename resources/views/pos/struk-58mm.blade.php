<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Struk #{{ $transaksi->nota_display }} - WashFlow</title>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        @page {
            size: 58mm auto;
            margin: 0;
        }
        body {
            font-family: 'JetBrains Mono', monospace;
            font-size: 11px;
            line-height: 1.25;
            color: #000000;
            background: #ffffff;
            margin: 0;
            padding: 4mm;
            width: 58mm;
            max-width: 58mm;
            box-sizing: border-box;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: 700; }
        .divider {
            border-top: 1px dashed #000000;
            margin: 5px 0;
        }
        .double-divider {
            border-top: 1px double #000000;
            margin: 6px 0;
        }
        .row {
            display: flex;
            justify-content: space-between;
        }
        .barcode {
            font-size: 22px;
            letter-spacing: 2px;
            text-align: center;
            margin: 6px 0;
        }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="no-print" style="margin-bottom: 10px; text-align: center;">
        <button onclick="window.print()" style="padding: 6px 12px; cursor: pointer;">Cetak Ulang</button>
        <button onclick="window.close()" style="padding: 6px 12px; cursor: pointer;">Tutup</button>
    </div>

    <div class="text-center font-bold" style="font-size: 13px;">WASHFLOW</div>
    <div class="text-center" style="font-size: 10px;">Jl. Operasional No. 88, Jakarta</div>
    <div class="text-center" style="font-size: 10px;">Telp/WA: 0812-3456-7890</div>

    <div class="double-divider"></div>

    <div class="row">
        <span>No. Nota:</span>
        <span class="font-bold">{{ $transaksi->nota_display }}</span>
    </div>
    <div class="row">
        <span>Tgl Masuk:</span>
        <span>{{ $transaksi->tgl_masuk ? $transaksi->tgl_masuk->format('d/m/Y H:i') : now()->format('d/m/Y H:i') }}</span>
    </div>
    <div class="row">
        <span>Kasir:</span>
        <span>{{ $transaksi->user->name ?? 'Kasir WashFlow' }}</span>
    </div>
    <div class="row">
        <span>Pelanggan:</span>
        <span class="font-bold">{{ $transaksi->nama_pelanggan }}</span>
    </div>
    <div class="row">
        <span>No. HP:</span>
        <span>{{ $transaksi->no_hp }}</span>
    </div>

    <div class="divider"></div>

    <div class="row font-bold" style="font-size: 10px;">
        <span>LAYANAN</span>
        <span>SUBTOTAL</span>
    </div>

    <div class="divider"></div>

    @if (!empty($transaksi->items_detail))
        @foreach ($transaksi->items_detail as $item)
            <div>{{ $item['nama_layanan'] ?? 'Layanan' }}</div>
            <div class="row" style="padding-left: 6px; font-size: 10px;">
                <span>{{ $item['qty'] }} {{ $item['satuan'] ?? 'kg' }} x Rp {{ number_format($item['harga'] ?? 0, 0, ',', '.') }}</span>
                <span>Rp {{ number_format($item['subtotal'] ?? 0, 0, ',', '.') }}</span>
            </div>
        @endforeach
    @else
        <div>{{ $transaksi->layanan->nama_layanan ?? 'Cuci Reguler' }}</div>
        <div class="row" style="padding-left: 6px; font-size: 10px;">
            <span>{{ $transaksi->berat_formatted }} x {{ $transaksi->layanan->harga_rupiah ?? '' }}</span>
            <span>{{ $transaksi->harga_rupiah }}</span>
        </div>
    @endif

    @if ($transaksi->kondisi_baju)
        <div style="font-size: 9px; margin-top: 4px;">[Kondisi: {{ $transaksi->kondisi_baju }}]</div>
    @endif
    @if ($transaksi->catatan)
        <div style="font-size: 9px; margin-top: 2px;">[Catatan: {{ $transaksi->catatan }}]</div>
    @endif

    <div class="divider"></div>

    <div class="row font-bold" style="font-size: 12px;">
        <span>TOTAL:</span>
        <span>{{ $transaksi->harga_rupiah }}</span>
    </div>
    <div class="row">
        <span>STATUS:</span>
        <span class="font-bold">{{ strtoupper($transaksi->status_pembayaran) }} ({{ $transaksi->metode_pembayaran ?? 'TUNAI' }})</span>
    </div>

    @if ($transaksi->lokasi_rak)
        <div class="row">
            <span>Lokasi Rak:</span>
            <span class="font-bold">{{ $transaksi->lokasi_rak }}</span>
        </div>
    @endif

    @if ($transaksi->deadline)
        <div class="row">
            <span>Est. Selesai:</span>
            <span>{{ $transaksi->deadline->format('d/m/Y H:i') }}</span>
        </div>
    @endif

    <div class="divider"></div>

    <div class="barcode">|| | ||| || |||| |</div>

    <div class="text-center" style="font-size: 9px; margin-top: 4px;">
        Simpan struk ini untuk pengambilan.<br>
        Terima kasih atas kepercayaan Anda!
    </div>
</body>
</html>
