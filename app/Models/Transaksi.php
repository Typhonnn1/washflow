<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
     * Boot the model with event listeners for auto code generation and price calculation.
     */
    protected static function booted(): void
    {
        static::creating(function (Transaksi $transaksi) {
            if (empty($transaksi->kode_transaksi)) {
                $transaksi->kode_transaksi = self::generateKodeTransaksi();
            }

            if (is_null($transaksi->total_harga) && $transaksi->layanan_id && $transaksi->berat) {
                $layanan = $transaksi->layanan ?? Layanan::find($transaksi->layanan_id);
                if ($layanan) {
                    $transaksi->total_harga = round((float) $transaksi->berat * (float) $layanan->harga_per_kg, 2);
                }
            }

            if (empty($transaksi->tgl_masuk)) {
                $transaksi->tgl_masuk = now();
            }

            if ($transaksi->status === 'Selesai' && empty($transaksi->tgl_selesai)) {
                $transaksi->tgl_selesai = now();
            }
        });

        static::updating(function (Transaksi $transaksi) {
            // Automatically record completion timestamp when status transitions to Selesai
            if ($transaksi->isDirty('status')) {
                if ($transaksi->status === 'Selesai' && empty($transaksi->tgl_selesai)) {
                    $transaksi->tgl_selesai = now();
                } elseif ($transaksi->status !== 'Selesai' && !$transaksi->isDirty('tgl_selesai')) {
                    $transaksi->tgl_selesai = null;
                }
            }

            // Recalculate total_harga if berat or layanan_id changed and total_harga was not explicitly overridden
            if (($transaksi->isDirty('berat') || $transaksi->isDirty('layanan_id')) && !$transaksi->isDirty('total_harga')) {
                $layanan = $transaksi->layanan ?? Layanan::find($transaksi->layanan_id);
                if ($layanan && $transaksi->berat) {
                    $transaksi->total_harga = round((float) $transaksi->berat * (float) $layanan->harga_per_kg, 2);
                }
            }
        });
    }

    /**
     * Generate unique transaction code: TRX-YYYYMMDD-XXXX
     * Robust against non-numeric suffixes and collision-safe.
     */
    public static function generateKodeTransaksi(): string
    {
        $datePrefix = 'TRX-' . date('Ymd');
        $matchingCodes = self::where('kode_transaksi', 'like', "{$datePrefix}-%")->pluck('kode_transaksi');

        $maxNumber = 0;
        foreach ($matchingCodes as $code) {
            if (preg_match('/^' . preg_quote($datePrefix, '/') . '-(\d+)$/', $code, $matches)) {
                $num = (int) $matches[1];
                if ($num > $maxNumber) {
                    $maxNumber = $num;
                }
            }
        }

        $nextNumber = $maxNumber + 1;
        $candidate = "{$datePrefix}-" . str_pad((string) $nextNumber, 4, '0', STR_PAD_LEFT);

        while (self::where('kode_transaksi', $candidate)->exists()) {
            $nextNumber++;
            $candidate = "{$datePrefix}-" . str_pad((string) $nextNumber, 4, '0', STR_PAD_LEFT);
        }

        return $candidate;
    }

    /**
     * Mark transaction as Selesai.
     */
    public function markAsSelesai(): bool
    {
        return $this->update([
            'status' => 'Selesai',
            'tgl_selesai' => now(),
        ]);
    }

    /**
     * Mark transaction as Proses.
     */
    public function markAsProses(): bool
    {
        return $this->update([
            'status' => 'Proses',
        ]);
    }

    /**
     * Mark transaction as Lunas.
     */
    public function markAsLunas(): bool
    {
        return $this->update([
            'status_pembayaran' => 'Lunas',
        ]);
    }

    /**
     * Accessor for formatted Rupiah price.
     */
    public function getHargaRupiahAttribute(): string
    {
        return 'Rp ' . number_format((float) ($this->total_harga ?? 0), 0, ',', '.');
    }

    /**
     * Accessor for formatted weight with unit.
     */
    public function getBeratFormattedAttribute(): string
    {
        $val = (float) ($this->berat ?? 0);
        return (floor($val) == $val ? number_format($val, 0) : number_format($val, 2, ',', '.')) . ' kg';
    }

    /**
     * Accessor for Tailwind badge styling based on status.
     */
    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'Masuk' => 'bg-amber-100 text-amber-800 border-amber-200',
            'Proses' => 'bg-blue-100 text-blue-800 border-blue-200',
            'Selesai' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
            default => 'bg-gray-100 text-gray-800 border-gray-200',
        };
    }

    /**
     * Accessor for Tailwind badge styling based on payment status.
     */
    public function getStatusPembayaranBadgeAttribute(): string
    {
        return match ($this->status_pembayaran) {
            'Lunas' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
            default => 'bg-rose-100 text-rose-800 border-rose-200',
        };
    }

    /**
     * Scope query by status (Masuk, Proses, Selesai).
     */
    public function scopeStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

    /**
     * Scope query by payment status (Belum Lunas, Lunas).
     */
    public function scopeStatusPembayaran(Builder $query, string $statusPembayaran): Builder
    {
        return $query->where('status_pembayaran', $statusPembayaran);
    }

    /**
     * Scope query for paid transactions.
     */
    public function scopeLunas(Builder $query): Builder
    {
        return $query->where('status_pembayaran', 'Lunas');
    }

    /**
     * Scope query for completed transactions.
     */
    public function scopeSelesai(Builder $query): Builder
    {
        return $query->where('status', 'Selesai');
    }

    /**
     * Scope query to filter transactions between dates.
     */
    public function scopeFilterTanggal(Builder $query, ?string $dari, ?string $sampai): Builder
    {
        if ($dari) {
            $query->whereDate('tgl_masuk', '>=', $dari);
        }
        if ($sampai) {
            $query->whereDate('tgl_masuk', '<=', $sampai);
        }
        return $query;
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
