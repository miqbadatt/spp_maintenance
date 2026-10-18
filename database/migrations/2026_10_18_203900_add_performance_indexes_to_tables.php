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
        Schema::table('tagihans', function (Blueprint $table) {
            $table->index('status_tagihan', 'tagihans_status_tagihan_idx');
            $table->index('bulan', 'tagihans_bulan_idx');
            $table->index('tahun', 'tagihans_tahun_idx');
            $table->index(['id_siswa', 'tahun', 'bulan'], 'tagihans_siswa_periode_idx');
        });

        Schema::table('pembayarans', function (Blueprint $table) {
            $table->index('status_pembayaran', 'pembayarans_status_pembayaran_idx');
        });

        Schema::table('siswas', function (Blueprint $table) {
            $table->index('kelas', 'siswas_kelas_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tagihans', function (Blueprint $table) {
            $table->dropIndex('tagihans_status_tagihan_idx');
            $table->dropIndex('tagihans_bulan_idx');
            $table->dropIndex('tagihans_tahun_idx');
            $table->dropIndex('tagihans_siswa_periode_idx');
        });

        Schema::table('pembayarans', function (Blueprint $table) {
            $table->dropIndex('pembayarans_status_pembayaran_idx');
        });

        Schema::table('siswas', function (Blueprint $table) {
            $table->dropIndex('siswas_kelas_idx');
        });
    }
};
