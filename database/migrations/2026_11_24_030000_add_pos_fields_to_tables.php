<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('layanan', function (Blueprint $table) {
            $table->string('kategori')->default('Kiloan')->after('nama_layanan');
            $table->string('satuan')->default('kg')->after('harga_per_kg');
            $table->boolean('is_express')->default(false)->after('durasi');
        });

        Schema::table('transaksi', function (Blueprint $table) {
            $table->string('lokasi_rak')->nullable()->after('catatan');
            $table->timestamp('deadline')->nullable()->after('tgl_masuk');
            $table->boolean('is_express')->default(false)->after('layanan_id');
            $table->string('metode_pembayaran')->nullable()->default('Tunai')->after('status_pembayaran');
            $table->string('kondisi_baju')->nullable()->after('catatan');
            $table->json('items_detail')->nullable()->after('kondisi_baju');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('layanan', function (Blueprint $table) {
            $table->dropColumn(['kategori', 'satuan', 'is_express']);
        });

        Schema::table('transaksi', function (Blueprint $table) {
            $table->dropColumn(['lokasi_rak', 'deadline', 'is_express', 'metode_pembayaran', 'kondisi_baju', 'items_detail']);
        });
    }
};
