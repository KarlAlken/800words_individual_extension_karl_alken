<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>800Words • Review</title>
  <link rel="stylesheet" href="{{ asset('custom/styles.css') }}">
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
    <h1>Review Your Words</h1>
    <p>Here you’ll be able to revisit words you’ve marked as <strong>“Practice”</strong> while learning.</p>

    <section class="review-grid">
      <div class="review-card">
        <h3>🇵🇱 Proszę</h3>
        <p><strong>English:</strong> Please</p>
      </div>

      <div class="review-card">
        <h3>🇵🇱 Dziękuję</h3>
        <p><strong>English:</strong> Thank you</p>
      </div>

      <div class="review-card">
        <h3>🇵🇱 Tak</h3>
        <p><strong>English:</strong> Yes</p>
      </div>
    </section>
  </main>

  <footer>
    <p>© 2025 800Words | Learn smarter, one flashcard at a time.</p>
  </footer>
</body>
</html>
