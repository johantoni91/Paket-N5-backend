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
        Schema::create('satker', function (Blueprint $table) {
            $table->id();
            $table->enum('satker_type', [0, 1, 2, 3])->default(3);
            $table->text('satker_code');
            $table->text('satker_name');
            $table->text('satker_phone')->nullable();
            $table->text('satker_email')->nullable();
            $table->text('satker_address')->nullable();
            $table->text('satker_akronim');
            $table->enum('satker_status', [0, 1])->default(1);
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));


            // $table->text('satker_slug')->nullable();
            // $table->text('satker_map')->nullable();
            // $table->text('satker_facebook')->nullable();
            // $table->text('satker_twitter')->nullable();
            // $table->text('satker_instagram')->nullable();
            // $table->text('satker_description')->nullable();
            // $table->text('satker_whatsapp')->nullable();
            // $table->text('satker_videotitle')->nullable();
            // $table->text('satker_videosubtitle')->nullable();
            // $table->text('satker_videotype')->nullable();
            // $table->text('satker_videolink')->nullable();
            // $table->text('satker_videopath')->nullable();
            // $table->text('satker_url')->nullable();
            // $table->text('satker_link')->nullable();
            // $table->text('is_cover')->nullable();
            // $table->text('satker_pattern')->nullable();
            // $table->text('satker_background')->nullable();
            // $table->text('satker_version')->nullable();
            // $table->text('satker_overlay')->nullable();
            // $table->text('is_deleted')->nullable();
            // $table->text('last_user')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('satker');
    }
};
