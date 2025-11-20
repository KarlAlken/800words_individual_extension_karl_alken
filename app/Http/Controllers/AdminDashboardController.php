<?php

namespace App\Http\Controllers;

use App\Models\Flashcard;
use App\Models\Language;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    // I check if user is admin before allowing access
    public function __construct()
    {
        // I make sure user is logged in and is admin
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403, 'You must be an admin to access this page.');
        }
    }

    public function index(): View
    {
        $languages = Language::withCount('flashcards')
            ->orderBy('name')
            ->get();

        return view('admin.languages', [
            'languages' => $languages,
            'activeSection' => 'languages',
        ]);
    }

    public function flashcards(): View
    {
        $languages = Language::orderBy('name')->get();
        $flashcards = Flashcard::with('language')
            ->orderBy('language_id')
            ->orderBy('term')
            ->get();

        // I get known flashcard IDs for current admin user
        $user = auth()->user();
        $knownIds = $user->knownFlashcards()->get()->pluck('id')->toArray();

        return view('admin.flashcards', [
            'languages' => $languages,
            'flashcards' => $flashcards,
            'knownIds' => $knownIds,
            'activeSection' => 'flashcards',
        ]);
    }

    public function storeLanguage(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:languages,name'],
            'flag_emoji' => ['nullable', 'string', 'max:8'],
            'target_word_count' => ['nullable', 'integer', 'min:1', 'max:2000'],
        ]);

        $validated['target_word_count'] = $validated['target_word_count'] ?? 800;

        Language::create($validated);

        return redirect()->route('admin')->with('status', 'Language added successfully.');
    }

    public function storeFlashcard(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'language_id' => ['required', 'exists:languages,id'],
            'term' => ['required', 'string', 'max:255'],
            'translation' => ['required', 'string', 'max:255'],
            'example' => ['nullable', 'string'],
        ]);

        Flashcard::create($validated);

        return redirect()->route('admin.flashcards.index')->with('status', 'Flashcard added successfully.');
    }

    public function updateLanguage(Request $request, Language $language): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('languages', 'name')->ignore($language->id)],
            'flag_emoji' => ['nullable', 'string', 'max:8'],
            'target_word_count' => ['nullable', 'integer', 'min:1', 'max:2000'],
        ]);

        $validated['target_word_count'] = $validated['target_word_count'] ?? 800;

        $language->update($validated);

        return redirect()->route('admin')->with('status', 'Language updated successfully.');
    }

    public function deleteLanguage(Language $language): RedirectResponse
    {
        $language->delete();

        return redirect()->route('admin')->with('status', 'Language deleted.');
    }

    public function updateFlashcard(Request $request, Flashcard $flashcard): RedirectResponse
    {
        $validated = $request->validate([
            'language_id' => ['required', 'exists:languages,id'],
            'term' => [
                'required',
                'string',
                'max:255',
                Rule::unique('flashcards', 'term')
                    ->ignore($flashcard->id)
                    ->where(fn ($query) => $query->where('language_id', $request->input('language_id'))),
            ],
            'translation' => ['required', 'string', 'max:255'],
        ]);

        $flashcard->update($validated);

        return redirect()->route('admin.flashcards.index')->with('status', 'Flashcard updated successfully.');
    }

    public function deleteFlashcard(Flashcard $flashcard): RedirectResponse
    {
        $flashcard->delete();

        return redirect()->route('admin.flashcards.index')->with('status', 'Flashcard deleted.');
    }

    // I toggle known status for a flashcard
    public function toggleKnown(Flashcard $flashcard): RedirectResponse
    {
        $user = auth()->user();
        
        // I check if flashcard is already known
        $isKnown = $user->knownFlashcards()->where('flashcard_id', $flashcard->id)->exists();
        
        if ($isKnown) {
            // I remove it from known flashcards
            $user->knownFlashcards()->detach($flashcard->id);
        } else {
            // I add it to known flashcards
            $user->knownFlashcards()->syncWithoutDetaching([$flashcard->id]);
        }

        return redirect()->route('admin.flashcards.index')->with('status', 'Known status updated.');
    }
}
