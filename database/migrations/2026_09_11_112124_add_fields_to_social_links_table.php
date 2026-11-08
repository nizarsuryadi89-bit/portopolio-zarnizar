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
        Schema::table('social_links', function (Blueprint $table) {
            $table->string('name');
            $table->string('icon')->nullable();
            $table->string('url');
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('social_links', function (Blueprint $table) {
            //
        });
    }
};
