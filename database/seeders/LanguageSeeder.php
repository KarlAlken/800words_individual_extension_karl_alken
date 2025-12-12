<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    public function run(): void
    {
        $languages = [
            ['name' => 'Polish', 'flag_emoji' => '🇵🇱'],
            ['name' => 'Danish', 'flag_emoji' => '🇩🇰'],
            ['name' => 'German', 'flag_emoji' => '🇩🇪'],
            ['name' => 'Spanish', 'flag_emoji' => '🇪🇸'],
            ['name' => 'French', 'flag_emoji' => '🇫🇷'],
            ['name' => 'Italian', 'flag_emoji' => '🇮🇹'],
            ['name' => 'Portuguese', 'flag_emoji' => '🇵🇹'],
            ['name' => 'Dutch', 'flag_emoji' => '🇳🇱'],
            ['name' => 'Swedish', 'flag_emoji' => '🇸🇪'],
            ['name' => 'Norwegian', 'flag_emoji' => '🇳🇴'],
            ['name' => 'Finnish', 'flag_emoji' => '🇫🇮'],
            ['name' => 'Turkish', 'flag_emoji' => '🇹🇷'],
            ['name' => 'Japanese', 'flag_emoji' => '🇯🇵'],
            ['name' => 'Chinese', 'flag_emoji' => '🇨🇳'],
            ['name' => 'Arabic', 'flag_emoji' => '🇦🇪'],
        ];

        foreach ($languages as $language) {
            Language::updateOrCreate(
                ['name' => $language['name']],
                [
                    'flag_emoji' => $language['flag_emoji'],
                    'target_word_count' => 800,
                ]
            );
        }
    }
}
