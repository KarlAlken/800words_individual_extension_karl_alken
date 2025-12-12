<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('flashcards', function (Blueprint $table) {
            $table->index('language_id');
            $table->index('term');
        });

        Schema::table('languages', function (Blueprint $table) {
            $table->index('name');
        });
    }

    public function down(): void
    {
        Schema::table('flashcards', function (Blueprint $table) {
            $table->dropIndex(['language_id']);
            $table->dropIndex(['term']);
        });

        Schema::table('languages', function (Blueprint $table) {
            $table->dropIndex(['name']);
        });
    }
};
