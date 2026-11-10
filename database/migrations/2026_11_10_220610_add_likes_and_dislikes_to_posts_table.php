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
        Schema::table('posts', function (Blueprint $table) {
            if (! Schema::hasColumn('posts', 'likes')) {
                $table->unsignedInteger('likes')->default(0)->after('views');
            }
            if (! Schema::hasColumn('posts', 'dislikes')) {
                $table->unsignedInteger('dislikes')->default(0)->after('likes');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $columnsToDrop = [];
            if (Schema::hasColumn('posts', 'likes')) {
                $columnsToDrop[] = 'likes';
            }
            if (Schema::hasColumn('posts', 'dislikes')) {
                $columnsToDrop[] = 'dislikes';
            }
            if (! empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
