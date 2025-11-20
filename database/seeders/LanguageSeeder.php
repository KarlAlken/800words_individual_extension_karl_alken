<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    public function run(): void
    {
        Language::updateOrCreate(
            ['name' => 'Polish'],
            [
                'flag_emoji' => '🇵🇱',
                'target_word_count' => 800,
            ]
        );
    }
}
