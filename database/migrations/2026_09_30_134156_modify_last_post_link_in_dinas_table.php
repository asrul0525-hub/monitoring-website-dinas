<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dinas', function (Blueprint $table) {
            $table->text('last_post_link')->nullable()->change();
            $table->text('last_post_title')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('dinas', function (Blueprint $table) {
            $table->string('last_post_link', 255)->nullable()->change();
            $table->string('last_post_title', 255)->nullable()->change();
        });
    }
};
