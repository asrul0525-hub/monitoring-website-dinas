<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dinas', function (Blueprint $table) {
            // Hapus kolom lama yang berkaitan dengan postingan/RSS
            if (Schema::hasColumn('dinas', 'last_post_title')) {
                $table->dropColumn('last_post_title');
            }
            if (Schema::hasColumn('dinas', 'last_post_date')) {
                $table->dropColumn('last_post_date');
            }
            if (Schema::hasColumn('dinas', 'last_post_link')) {
                $table->dropColumn('last_post_link');
            }
            if (Schema::hasColumn('dinas', 'rss_url')) {
                $table->dropColumn('rss_url');
            }

            // Tambahkan kolom baru untuk status server
            $table->integer('http_status_code')->nullable();
            $table->integer('response_time_ms')->nullable();
            $table->timestamp('last_checked_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('dinas', function (Blueprint $table) {
            $table->string('last_post_title')->nullable();
            $table->dateTime('last_post_date')->nullable();
            $table->text('last_post_link')->nullable();
            $table->string('rss_url')->nullable();

            $table->dropColumn(['http_status_code', 'response_time_ms', 'last_checked_at']);
        });
    }
};
