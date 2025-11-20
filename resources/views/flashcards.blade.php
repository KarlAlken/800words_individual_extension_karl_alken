<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>800Words • {{ $language->name }} Flashcards</title>
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

    <h1>{{ $language->flag_emoji }} {{ $language->name }} Flashcards</h1>
    <p>Click "Next" to see new words and try to remember them!</p>

    @if ($flashcards->isEmpty())
      <p>No flashcards have been added for this language yet. Add some in the Admin dashboard.</p>
    @else
      <section
        id="flashcard-section"
        data-flashcards='@json($flashcards->map(fn ($card) => [
          'term' => $card->term,
          'translation' => $card->translation,
        ]))'
      >
        <div id="flashcard" class="card">
          <h2 id="cardText">Ready?</h2>
        </div>

        <div class="button-row">
          <button id="flip-btn" class="button">Show Translation</button>
          <button id="next-btn" class="button">Next</button>
        </div>
      </section>
    @endif

  </main>

  <footer>
    <p>© 2025 800Words | Learn smarter, one flashcard at a time.</p>
  </footer>

</body>
</html>
