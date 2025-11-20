<?php

namespace App\Http\Controllers;

use App\Models\Language;
use Illuminate\View\View;

class FlashcardController extends Controller
{
    public function show(Language $language): View
    {
        $flashcards = $language->flashcards()
            ->select('id', 'term', 'translation')
            ->orderBy('term')
            ->get();

        return view('flashcards', [
            'language' => $language,
            'flashcards' => $flashcards,
        ]);
    }
}
