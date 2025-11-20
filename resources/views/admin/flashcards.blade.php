@extends('admin.layout')

@section('admin-content')
  <h1>Manage Flashcards</h1>

  <section class="card admin-card">
    <h2>Add a Flashcard</h2>

    <form method="POST" action="{{ route('admin.flashcards.store') }}" class="admin-form admin-form-grid">
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
    <div class="table-wrapper">
      <table class="admin-table">
        <thead>
          <tr>
            <th>Actions</th>
            <th>Known</th>
            <th>Language</th>
            <th>Word / Phrase</th>
            <th>Translation</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($flashcards as $flashcard)
            <form id="update-flashcard-{{ $flashcard->id }}" method="POST" action="{{ route('admin.flashcards.update', $flashcard) }}">
              @csrf
              @method('PATCH')
            </form>
            <form id="delete-flashcard-{{ $flashcard->id }}" method="POST" action="{{ route('admin.flashcards.destroy', $flashcard) }}" onsubmit="return confirm('Delete this flashcard?');">
              @csrf
              @method('DELETE')
            </form>
            <tr>
              <td class="table-actions">
                <button class="button button-sm" form="update-flashcard-{{ $flashcard->id }}">Update</button>
                <button class="button button-sm button-danger" form="delete-flashcard-{{ $flashcard->id }}">Delete</button>
              </td>
              <td>
                <form method="POST" action="{{ route('admin.flashcards.toggle-known', $flashcard) }}" style="display: inline;">
                  @csrf
                  <input type="checkbox" 
                         onchange="this.form.submit()" 
                         {{ in_array($flashcard->id, $knownIds ?? []) ? 'checked' : '' }}>
                </form>
              </td>
              <td>
                <select name="language_id" form="update-flashcard-{{ $flashcard->id }}">
                  @foreach ($languages as $language)
                    <option value="{{ $language->id }}" {{ $flashcard->language_id == $language->id ? 'selected' : '' }}>
                      {{ $language->flag_emoji }} {{ $language->name }}
                    </option>
                  @endforeach
                </select>
              </td>
              <td>
                <input form="update-flashcard-{{ $flashcard->id }}" name="term" type="text" value="{{ $flashcard->term }}" required>
              </td>
              <td>
                <input form="update-flashcard-{{ $flashcard->id }}" name="translation" type="text" value="{{ $flashcard->translation }}" required>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5">No flashcards yet.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </section>
@endsection
