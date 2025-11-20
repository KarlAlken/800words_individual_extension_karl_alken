<?php

namespace App\Http\Controllers;

use App\Models\Flashcard;
use App\Models\Language;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FlashcardController extends Controller
{
    public function show(Language $language): View
    {
        $flashcards = $language->flashcards()
            ->select('id', 'term', 'translation')
            ->orderBy('term')
            ->get();

        // I get known flashcard IDs if user is logged in
        $knownIds = [];
        if (auth()->check()) {
            $knownIds = auth()->user()->knownFlashcards()->get()->pluck('id')->toArray();
        }

        return view('flashcards', [
            'language' => $language,
            'flashcards' => $flashcards,
            'knownIds' => $knownIds,
        ]);
    }

    // I mark a flashcard as known
    public function markAsKnown(Flashcard $flashcard): RedirectResponse
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->route('account');
        }

        // I attach the flashcard to user's known flashcards
        $user->knownFlashcards()->syncWithoutDetaching([$flashcard->id]);

        // I redirect back to the language flashcards page
        $language = $flashcard->language;
        return redirect()->route('languages.flashcards', $language)->with('success', 'Word marked as known!');
    }
}
