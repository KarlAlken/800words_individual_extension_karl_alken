<?php

namespace App\Http\Controllers;

use App\Models\Flashcard;
use App\Models\Language;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProgressController extends Controller
{
    // I show the progress page
    public function index(): View
    {
        $user = Auth::user();
        
        // I count total words the user has marked as known
        $totalWords = $user->knownFlashcards()->get()->count();
        
        // I calculate days since account was created (full number of days)
        $daysStudying = (int) $user->created_at->diffInDays(now());
        if ($daysStudying == 0) {
            $daysStudying = 1;
        }
        
        // I get all languages
        $languages = Language::all();
        
        // I get user's known flashcards
        $knownFlashcards = $user->knownFlashcards()->get();
        
        // I calculate progress for each language
        $languageProgressList = [];
        foreach ($languages as $language) {
            // I count how many flashcards the user knows for this language
            $wordsKnown = $knownFlashcards->where('language_id', $language->id)->count();
            $targetWords = $language->target_word_count;
            $percent = 0;
            if ($targetWords > 0) {
                $percent = round(($wordsKnown / $targetWords) * 100);
            }
            
            $languageProgressList[] = [
                'name' => $language->name,
                'wordsKnown' => $wordsKnown,
                'targetWords' => $targetWords,
                'percent' => $percent,
            ];
        }
        
        return view('progress', [
            'totalWords' => $totalWords,
            'daysStudying' => $daysStudying,
            'languages' => $languageProgressList,
        ]);
    }
}

