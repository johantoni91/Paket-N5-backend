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
            $table->unsignedBigInteger('nip')->nullable();
            $table->unsignedBigInteger('nrp')->nullable();
            $table->text('nama');
            $table->text('jabatan');
            $table->date('tgl_lahir');
            $table->text('eselon')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P'])->default('L');
            $table->text('GOL_KD')->nullable();
            $table->text('golpang')->nullable();
            $table->text('foto_pegawai');
            $table->text('nama_satker')->nullable();
            $table->text('agama');
            $table->text('status_pegawai')->nullable();
            $table->text('jaksa_tu')->nullable();
            $table->text('struktural_non')->nullable();
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
