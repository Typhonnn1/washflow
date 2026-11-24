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
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi')->unique();
            $table->string('nama_pelanggan');
            $table->string('no_hp');
            $table->foreignId('layanan_id')->constrained('layanan')->onDelete('cascade');
            $table->decimal('berat', 8, 2);
            $table->decimal('total_harga', 12, 2);
            $table->string('status')->default('Masuk'); // Masuk, Proses, Selesai
            $table->string('status_pembayaran')->default('Belum Lunas'); // Belum Lunas, Lunas
            $table->text('catatan')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('tgl_masuk')->useCurrent();
            $table->timestamp('tgl_selesai')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
