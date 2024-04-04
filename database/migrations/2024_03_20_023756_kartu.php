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
        Schema::create('kartu', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('profile', [0, 1])->default(0);
            $table->enum('categories', [0, 1, 2])->default(0);
            $table->unsignedBigInteger('total')->default(0);
            $table->longtext('icon')->nullable();
            $table->longtext('front')->nullable();
            $table->longtext('back')->nullable();
            $table->enum('orientation', ['potrait', 'landscape'])->default('potrait');
            $table->enum('nip', [0, 1])->default(1);
            $table->enum('nrp', [0, 1])->default(1);
            $table->enum('nama', [0, 1])->default(1);
            $table->enum('golongan', [0, 1])->default(1);
            $table->enum('jabatan', [0, 1])->default(1);
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kartu');
    }
};
