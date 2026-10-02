<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dinas', function (Blueprint $table) {
            $table->integer('http_status')->nullable()->after('domain_url');
            $table->integer('response_time')->nullable()->after('http_status');
        });
    }

    public function down(): void
    {
        Schema::table('dinas', function (Blueprint $table) {
            $table->dropColumn(['http_status', 'response_time']);
        });
    }
};