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
        Schema::create('dinas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('klaster_opd_id')->nullable()->constrained('klaster_opds')->nullOnDelete();
            $table->string('nama_dinas');
            $table->string('singkatan', 20);
            $table->string('domain_url');
            $table->string('rss_url')->nullable();
            $table->string('pic_nama')->nullable();
            $table->string('last_post_title')->nullable();
            $table->string('last_post_link')->nullable();
            $table->timestamp('last_post_date')->nullable();
            $table->enum('status', ['aktif', 'kurang_aktif', 'pasif'])->default('pasif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dinas');
    }
};
