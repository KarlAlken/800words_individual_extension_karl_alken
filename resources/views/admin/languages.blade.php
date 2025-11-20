@extends('admin.layout')

@section('admin-content')
  <h1>Manage Languages</h1>

  <section class="card admin-card">
    <h2>Add a Language</h2>

    <form method="POST" action="{{ route('admin.languages.store') }}" class="admin-form admin-form-grid">
      @csrf
      <label>Name
        <input name="name" type="text" required value="{{ old('name') }}">
      </label>

      <label>Flag Emoji
        <input name="flag_emoji" type="text" maxlength="8" placeholder="🇵🇱" value="{{ old('flag_emoji') }}">
      </label>

      <label>Target Word Count
        <input name="target_word_count" type="number" min="1" max="2000" value="{{ old('target_word_count', 800) }}">
      </label>

      <div class="align-end">
        <button class="button button-sm" type="submit">Save Language</button>
      </div>
    </form>
  </section>

  <section class="card admin-card">
    <h2>Languages</h2>
    <div class="table-wrapper">
      <table class="admin-table">
        <thead>
          <tr>
            <th>Actions</th>
            <th>Name</th>
            <th>Flag</th>
            <th>Target words</th>
            <th>Flashcards</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($languages as $language)
            <form id="update-language-{{ $language->id }}" method="POST" action="{{ route('admin.languages.update', $language) }}">
              @csrf
              @method('PATCH')
            </form>
            <form id="delete-language-{{ $language->id }}" method="POST" action="{{ route('admin.languages.destroy', $language) }}" onsubmit="return confirm('Delete {{ $language->name }} and all flashcards?');">
              @csrf
              @method('DELETE')
            </form>
            <tr>
              <td class="table-actions">
                <button class="button button-sm" form="update-language-{{ $language->id }}">Update</button>
                <button class="button button-sm button-danger" form="delete-language-{{ $language->id }}">Delete</button>
              </td>
              <td>
                <input form="update-language-{{ $language->id }}" name="name" type="text" value="{{ $language->name }}" required>
              </td>
              <td>
                <input form="update-language-{{ $language->id }}" name="flag_emoji" type="text" value="{{ $language->flag_emoji }}">
              </td>
              <td>
                <input form="update-language-{{ $language->id }}" name="target_word_count" type="number" min="1" max="2000" value="{{ $language->target_word_count }}">
              </td>
              <td>{{ $language->flashcards_count }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="5">No languages yet.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </section>
@endsection
