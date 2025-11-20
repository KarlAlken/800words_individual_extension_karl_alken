<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>800Words - Learn</title>
  <link rel="stylesheet" href="{{ asset('custom/styles.css') }}">
  <script defer src="{{ asset('custom/app.js') }}"></script>
</head>

<body>

  <header>
    <a href="{{ route('home') }}">
      <img src="{{ asset('custom/logo.png') }}" alt="800Words Logo" class="logo">
    </a>
    <nav>
      <a href="{{ route('home') }}">Learn</a>
      @if (auth()->check())
        <a href="{{ route('review') }}">Review</a>
        <a href="{{ route('progress') }}">Progress</a>
        <a href="{{ route('account') }}">Account</a>
        @if (auth()->user()->isAdmin())
          <a href="{{ route('admin') }}">Admin</a>
        @endif
      @else
        <a href="{{ route('account') }}">Log In</a>
      @endif
    </nav>
  </header>

<main>
  <section class="languages">
    <h2>Choose a Language</h2>

    @if ($languages->isEmpty())
      <p>No languages have been added yet. Visit the Admin dashboard to create one.</p>
    @else
      <div class="language-grid">
        @foreach ($languages as $language)
          <button
            class="language-card"
            @if ($language->flashcards_count > 0)
              onclick="location.href='{{ route('languages.flashcards', $language) }}'"
            @else
              disabled
            @endif
          >
            <h3>{{ $language->flag_emoji }} {{ $language->name }}</h3>
            <p class="progress-text">
              {{ $language->flashcards_count }} / {{ $language->target_word_count }} words ready
            </p>
            <p>
              {{ $language->flashcards_count > 0 ? 'Start learning now!' : 'Add flashcards in Admin' }}
            </p>
          </button>
        @endforeach
      </div>
    @endif

  </section>
</main>

<footer>
  <p>© 2025 800Words | Learn smarter, one flashcard at a time.</p>
</footer>

</body>
</html>
