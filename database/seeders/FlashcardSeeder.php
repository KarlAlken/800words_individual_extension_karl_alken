<?php

namespace Database\Seeders;

use App\Models\Flashcard;
use App\Models\Language;
use Illuminate\Database\Seeder;

class FlashcardSeeder extends Seeder
{
    public function run(): void
    {
        $language = Language::where('name', 'Polish')->first();

        if (! $language) {
            return;
        }

        $cards = [
            ['term' => 'Hello', 'translation' => 'Cześć'],
            ['term' => 'Thank you', 'translation' => 'Dziękuję'],
            ['term' => 'Please', 'translation' => 'Proszę'],
            ['term' => 'Good morning', 'translation' => 'Dzień dobry'],
            ['term' => 'Excuse me', 'translation' => 'Przepraszam'],
        ];

        foreach ($cards as $card) {
            Flashcard::updateOrCreate(
                ['language_id' => $language->id, 'term' => $card['term']],
                array_merge($card, ['language_id' => $language->id])
            );
        }
    }
}
