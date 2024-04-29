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
        Schema::create('pengajuan', function (Blueprint $table) {
            $table->id();
            $table->longtext('nip');
            $table->longtext('nama');
            $table->text('kode_satker');
            $table->text('photo')->nullable();
            $table->text('token')->nullable();
            $table->text('kartu');
            $table->enum('status', [0, 1, 2, 3])->default(1); // 0 = ditolak, 1 = proses, 2 = diterima di bawah kejagung, 3 diterima kejagung + token
            $table->enum('approve_satker', [0, 1, 2, 3])->default(3); // 0 = kejagung, 1 = kejati, 2 = kejari, 3 = kejari
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan');
    }
};
