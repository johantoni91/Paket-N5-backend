<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pegawai', function (Blueprint $table) {
            $table->id();
            $table->text('nip');
            $table->text('nrp')->nullable()->default('');
            $table->text('nama');
            $table->text('jabatan')->nullable()->default('');
            $table->date('tgl_lahir');
            $table->text('eselon')->nullable()->default('');
            $table->enum('jenis_kelamin', ['L', 'P'])->default('L');
            $table->text('GOL_KD')->nullable()->default('');
            $table->text('golpang')->nullable()->default('');
            $table->text('foto_pegawai')->nullable()->default('');
            $table->text('nama_satker');
            $table->text('agama')->nullable()->default('');
            $table->text('status_pegawai');
            $table->text('jaksa_tu');
            $table->text('struktural_non');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pegawai');
    }
};
