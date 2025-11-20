<?php

namespace App\Http\Controllers;

use App\Models\Flashcard;
use App\Models\Language;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProgressController extends Controller
{
    // show progress page
    public function index(): View
    {
        $user = Auth::user();
        
        // count total words learned
        $totalWords = $user->knownFlashcards()->get()->count();
        
        // calculate days studying
        $daysStudying = (int) $user->created_at->diffInDays(now());
        if ($daysStudying == 0) {
            $daysStudying = 1;
        }
        
        // get all languages
        $languages = Language::all();
        
        // get user's known flashcards
        $knownFlashcards = $user->knownFlashcards()->get();
        
        // calculate progress for each language
        $languageProgressList = [];
        foreach ($languages as $language) {
            // count words known for this language
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

