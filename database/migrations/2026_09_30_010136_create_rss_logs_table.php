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
        Schema::create('rss_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dinas_id')->constrained('dinas')->onDelete('cascade');
            $table->string('article_title')->nullable();
            $table->string('article_link')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->enum('fetch_status', ['success', 'failed'])->default('success');
            $table->text('error_message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rss_logs');
    }
};
