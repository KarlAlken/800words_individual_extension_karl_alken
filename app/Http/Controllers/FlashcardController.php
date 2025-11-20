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

        // get known flashcard IDs if user is logged in
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

    // mark flashcard as known
    public function markAsKnown(Flashcard $flashcard): RedirectResponse
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->route('account');
        }

        // add flashcard to user's known flashcards
        $user->knownFlashcards()->syncWithoutDetaching([$flashcard->id]);

        // redirect back to flashcards page
        $language = $flashcard->language;
        return redirect()->route('languages.flashcards', $language)->with('success', 'Word marked as known!');
    }
}
