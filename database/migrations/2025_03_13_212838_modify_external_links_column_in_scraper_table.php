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
            $table->longText('images')->change();
            $table->longText('videos')->change();
            $table->longText('external_links')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('scraper', function (Blueprint $table) {
            $table->text('external_links')->change();
            $table->text('images')->change();
            $table->text('videos')->change();
        });
    }
};
