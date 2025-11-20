<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>800Words - Progress</title>
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
  <h1>My Progress</h1>
  
  <h2>Overall Stats</h2>
  <p>Total words learned: {{ $totalWords }}</p>
  <p>Days studying: {{ $daysStudying }}</p>
  
  @foreach ($languages as $language)
  <h2>{{ $language['name'] }}</h2>
  <p>Words known: {{ $language['wordsKnown'] }} out of {{ $language['targetWords'] }}</p>
  <p>That's about {{ $language['percent'] }}%</p>
  @endforeach
  
</main>

<footer>
  <p>© 2025 800Words | Learn smarter, one flashcard at a time.</p>
</footer>

</body>
</html>
