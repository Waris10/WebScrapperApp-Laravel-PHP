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
        Schema::table('scraper', function (Blueprint $table) {
            $table->text('images')->nullable()->after('result');
            $table->text('videos')->nullable()->after('images');
            $table->text('external_links')->nullable()->after('videos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('scraper', function (Blueprint $table) {
            $table->dropColumn(['images', 'videos', 'external_links']);
        });
    }
};
