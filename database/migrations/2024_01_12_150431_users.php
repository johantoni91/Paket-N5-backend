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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->text('name');
            $table->text('satker');
            $table->enum('roles', ['superadmin', 'admin', 'pegawai']);
            $table->enum('status', [1, 0])->default(1);
            $table->string('nip')->unique()->nullable()->default('');
            $table->string('nrp')->unique()->nullable()->default('');
            $table->string('email');
            $table->text('phone')->nullable()->default('');
            $table->text('photo')->nullable()->default('');
            $table->string('password');
            $table->text('token')->nullable()->default('');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
