<?php

namespace App\Http\Controllers;

use App\Models\Flashcard;
use App\Models\Language;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    // check iff user is admin 213
    public function __construct()
    {
        // make suree user is logged in and is admin
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403, 'You must be an admin to access this page.');
        }
    }

    public function index(): View
    {
        $perPageInput = request('per_page', session('per_page_languages'));
        $searchTerm = request('search');
        $allowedPerPage = ['10', '20', '50', '100', 'all'];
        $perPageChoice = in_array($perPageInput, $allowedPerPage, true) ? $perPageInput : '20';

        session(['per_page_languages' => $perPageChoice]);

        $baseQuery = Language::withCount('flashcards')
            ->when($searchTerm, function ($query) use ($searchTerm) {
                $query->where('name', 'like', '%' . $searchTerm . '%');
            });

        $perPage = $perPageChoice === 'all'
            ? max((clone $baseQuery)->count(), 1)
            : (int) $perPageChoice;

        $languages = $baseQuery
            ->orderBy('name')
            ->paginate($perPage)
            ->appends([
                'per_page' => $perPageChoice,
                'search' => $searchTerm,
            ]);

        return view('admin.languages', [
            'languages' => $languages,
            'activeSection' => 'languages',
            'perPageChoice' => $perPageChoice,
            'perPageOptions' => $allowedPerPage,
            'searchTerm' => $searchTerm,
        ]);
    }

    public function flashcards(Request $request)
    {
        $languages = Cache::remember('languages_simple', 600, function () {
            return Language::orderBy('name')->get(['id', 'name', 'flag_emoji']);
        });
        $languageId = $request->input('language_id');
        $searchTerm = $request->input('search');
        $perPageInput = $request->input('per_page', session('per_page_flashcards'));

        $allowedPerPage = ['10', '20', '50', '100', 'all'];
        $perPageChoice = in_array($perPageInput, $allowedPerPage, true) ? $perPageInput : '20';

        session(['per_page_flashcards' => $perPageChoice]);

        $baseQuery = Flashcard::with('language')
            ->when($languageId, fn ($query) => $query->where('language_id', $languageId))
            ->when($searchTerm, function ($query) use ($searchTerm) {
                $query->where(function ($inner) use ($searchTerm) {
                    $inner->where('term', 'like', '%' . $searchTerm . '%')
                          ->orWhere('translation', 'like', '%' . $searchTerm . '%');
                });
            });

        $perPage = $perPageChoice === 'all'
            ? max((clone $baseQuery)->count(), 1)
            : (int) $perPageChoice;

        // I show 20 flashcards per page by default (or the chosen value)
        $flashcards = $baseQuery
            ->orderBy('language_id')
            ->orderBy('term')
            ->paginate($perPage)
            ->appends([
                'language_id' => $languageId,
                'per_page' => $perPageChoice,
                'search' => $searchTerm,
            ]);

        // get known flashcard IDs for current user
        $user = auth()->user();
        $knownIds = $user->knownFlashcards()->get()->pluck('id')->toArray();

        if ($request->wantsJson()) {
            $html = view('admin.flashcards_list', [
                'flashcards' => $flashcards,
                'languages' => $languages,
                'knownIds' => $knownIds,
            ])->render();

            return response()->json(['html' => $html]);
        }

        return view('admin.flashcards', [
            'languages' => $languages,
            'flashcards' => $flashcards,
            'knownIds' => $knownIds,
            'activeSection' => 'flashcards',
            'selectedLanguageId' => $languageId,
            'perPageChoice' => $perPageChoice,
            'perPageOptions' => $allowedPerPage,
            'searchTerm' => $searchTerm,
        ]);
    }

    public function storeLanguage(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:languages,name'],
            'flag_emoji' => ['nullable', 'string', 'max:8'],
            'target_word_count' => ['nullable', 'integer', 'min:1', 'max:2000'],
        ], [
            'name.unique' => 'That language already exists.',
        ]);

        $validated['target_word_count'] = $validated['target_word_count'] ?? 800;

        Language::create($validated);
        Cache::forget('languages_simple');

        return redirect()->route('admin')->with('status', 'Language added successfully.');
    }

    public function storeFlashcard(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'language_id' => ['required', 'exists:languages,id'],
            'term' => [
                'required',
                'string',
                'max:255',
                Rule::unique('flashcards', 'term')
                    ->where(fn ($query) => $query->where('language_id', $request->input('language_id'))),
            ],
            'translation' => ['required', 'string', 'max:255'],
            'example' => ['nullable', 'string'],
        ], [
            'term.required' => 'Please enter a term.',
            'term.unique' => 'This flashcard already exists for that language.',
            'translation.required' => 'Please enter a translation.',
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
        Cache::forget('languages_simple');

        return redirect()->route('admin')->with('status', 'Language updated successfully.');
    }

    public function deleteLanguage(Language $language): RedirectResponse
    {
        $language->delete();
        Cache::forget('languages_simple');

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
        ], [
            'term.unique' => 'This flashcard already exists for that language.',
        ]);

        $flashcard->update($validated);

        return redirect()->route('admin.flashcards.index')->with('status', 'Flashcard updated successfully.');
    }

    public function deleteFlashcard(Flashcard $flashcard): RedirectResponse
    {
        $flashcard->delete();

        return redirect()->route('admin.flashcards.index')->with('status', 'Flashcard deleted.');
    }

    // toggle known status for a flashcard
    public function toggleKnown(Flashcard $flashcard): RedirectResponse
    {
        $user = auth()->user();
        
        // check if flashcard is already known
        $isKnown = $user->knownFlashcards()->where('flashcard_id', $flashcard->id)->exists();
        
        if ($isKnown) {
            // remove it from known flashcards
            $user->knownFlashcards()->detach($flashcard->id);
        } else {
            // add it to known flashcards
            $user->knownFlashcards()->syncWithoutDetaching([$flashcard->id]);
        }

        return redirect()->route('admin.flashcards.index')->with('status', 'Known status updated.');
    }
}
