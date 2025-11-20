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
        Schema::table('languages', function (Blueprint $table) {
            if (Schema::hasColumn('languages', 'code')) {
                $table->dropUnique('languages_code_unique');
                $table->dropColumn('code');
            }

            if (Schema::hasColumn('languages', 'is_active')) {
                $table->dropColumn('is_active');
            }
        });

        Schema::table('flashcards', function (Blueprint $table) {
            if (Schema::hasColumn('flashcards', 'example')) {
                $table->dropColumn('example');
            }

            if (Schema::hasColumn('flashcards', 'needs_review')) {
                $table->dropColumn('needs_review');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('languages', function (Blueprint $table) {
            if (! Schema::hasColumn('languages', 'code')) {
                $table->string('code', 10)->unique()->after('name');
            }

            if (! Schema::hasColumn('languages', 'is_active')) {
                $table->boolean('is_active')->default(false)->after('target_word_count');
            }
        });

        Schema::table('flashcards', function (Blueprint $table) {
            if (! Schema::hasColumn('flashcards', 'example')) {
                $table->text('example')->nullable()->after('translation');
            }

            if (! Schema::hasColumn('flashcards', 'needs_review')) {
                $table->boolean('needs_review')->default(false)->after('example');
            }
        });
    }
};
