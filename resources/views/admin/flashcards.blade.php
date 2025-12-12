@extends('admin.layout')

@section('admin-content')
  <h1>Manage Flashcards</h1>

  <section class="card admin-card">
    <h2>Add a Flashcard</h2>

    <form method="POST" action="{{ route('admin.flashcards.store') }}" class="admin-form admin-form-grid filter-inline">
      @csrf

      <label>Language
        <select name="language_id" required>
          <option value="" disabled selected>Select a language</option>
          @foreach ($languages as $language)
            <option value="{{ $language->id }}" {{ old('language_id') == $language->id ? 'selected' : '' }}>
              {{ $language->flag_emoji }} {{ $language->name }}
            </option>
          @endforeach
        </select>
      </label>

      <label>Word / Phrase
        <input name="term" type="text" required value="{{ old('term') }}">
      </label>

      <label>Translation
        <input name="translation" type="text" required value="{{ old('translation') }}">
      </label>

      <div class="align-end">
        <button class="button button-sm" type="submit">Add Flashcard</button>
      </div>
    </form>
  </section>

  <section class="card admin-card">
    <h2>Flashcards</h2>

    @php
      $filtersActive = !empty($selectedLanguageId) || !empty($searchTerm) || (($perPageChoice ?? '20') !== '20');
    @endphp

    <form id="flashcards-filter-form" method="GET" action="{{ route('admin.flashcards.index') }}" class="admin-form filter-inline filter-row" data-per-page-default="20">
      <label>Filter by language
        <select name="language_id">
          <option value="">All languages</option>
          @foreach ($languages as $language)
            <option value="{{ $language->id }}" {{ ($selectedLanguageId ?? '') == $language->id ? 'selected' : '' }}>
              {{ $language->flag_emoji }} {{ $language->name }}
            </option>
          @endforeach
        </select>
      </label>
      <label>Search term / translation
        <input type="text" name="search" value="{{ $searchTerm ?? '' }}" placeholder="Search...">
      </label>
      <label>Per page
        <select name="per_page">
          @foreach ($perPageOptions as $option)
            <option value="{{ $option }}" {{ ($perPageChoice ?? '20') == $option ? 'selected' : '' }}>
              {{ $option === 'all' ? 'All' : $option }}
            </option>
          @endforeach
        </select>
      </label>
      <div class="filter-actions">
        <a class="button button-sm button-compact {{ $filtersActive ? '' : 'button-disabled' }}"
           id="flashcards-clear"
           href="{{ route('admin.flashcards.index', ['per_page' => 20]) }}"
           @unless ($filtersActive) aria-disabled="true" tabindex="-1" @endunless>
          Clear
        </a>
      </div>
    </form>

    <div id="flashcards-data">
      @include('admin.flashcards_list')
    </div>
  </section>
@endsection
