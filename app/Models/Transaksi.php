<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Transaksi extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'transaksi';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'kode_transaksi',
        'nama_pelanggan',
        'no_hp',
        'layanan_id',
        'berat',
        'total_harga',
        'status',
        'status_pembayaran',
        'catatan',
        'user_id',
        'tgl_masuk',
        'tgl_selesai',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'status' => 'Masuk',
        'status_pembayaran' => 'Belum Lunas',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'berat' => 'decimal:2',
        'total_harga' => 'decimal:2',
        'tgl_masuk' => 'datetime',
        'tgl_selesai' => 'datetime',
    ];

    /**
     * Boot the model.
     */
    protected static function booted(): void
    {
        static::creating(function (Transaksi $transaksi) {
            if (empty($transaksi->kode_transaksi)) {
                $transaksi->kode_transaksi = self::generateKodeTransaksi();
            }

            if (empty($transaksi->total_harga) && $transaksi->layanan_id && $transaksi->berat) {
                $layanan = $transaksi->layanan ?? Layanan::find($transaksi->layanan_id);
                if ($layanan) {
                    $transaksi->total_harga = round($transaksi->berat * (float) $layanan->harga_per_kg, 2);
                }
            }

            if (empty($transaksi->tgl_masuk)) {
                $transaksi->tgl_masuk = now();
            }
        });
    }

    /**
     * Generate unique transaction code.
     */
    public static function generateKodeTransaksi(): string
    {
        $datePrefix = 'TRX-' . date('Ymd');
        $latest = self::where('kode_transaksi', 'like', "{$datePrefix}-%")->latest('id')->first();

        if ($latest) {
            $parts = explode('-', $latest->kode_transaksi);
            $lastNumber = isset($parts[2]) ? (int) $parts[2] : 0;
            $nextNumber = str_pad((string) ($lastNumber + 1), 4, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = '0001';
        }

        return "{$datePrefix}-{$nextNumber}";
    }

    /**
     * Get the service for the transaction.
     */
    public function layanan(): BelongsTo
    {
        return $this->belongsTo(Layanan::class, 'layanan_id');
    }

    /**
     * Get the user (admin/kasir) who handled the transaction.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
