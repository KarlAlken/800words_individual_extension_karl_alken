<?php

namespace App\Http\Controllers;

use App\Models\Language;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $languages = Language::query()
            ->withCount('flashcards')
            ->orderBy('name')
            ->get();

        return view('index', compact('languages'));
    }
}
