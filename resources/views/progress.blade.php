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
  <h1>Your Progress</h1>
  <p>Track your learning journey!</p>

  <div style="max-width: 600px; margin: 30px auto;">
    
    <div style="background-color: #f9f9f9; border: 2px solid #ddd; border-radius: 10px; padding: 20px; margin-bottom: 20px;">
      <h2>Overall Statistics</h2>
      <p><strong>Total Words Learned:</strong> 277</p>
      <p><strong>Days Studying:</strong> 12</p>
    </div>

    <div style="background-color: #f9f9f9; border: 2px solid #ddd; border-radius: 10px; padding: 20px; margin-bottom: 20px;">
      <h2>Polish Progress</h2>
      <p><strong>Words Known:</strong> 277 / 800</p>
      
      <div style="background-color: #ddd; border-radius: 10px; height: 30px; width: 100%; margin: 10px 0;">
        <div style="background-color: #FF7400; border-radius: 10px; height: 30px; width: 35%; text-align: center; line-height: 30px; color: white; font-weight: bold;">
          35%
        </div>
      </div>
      
      <p><strong>Cards Reviewed Today:</strong> 15</p>
      <p><strong>Accuracy:</strong> 87%</p>
    </div>

    

  </div>
</main>

<footer>
  <p>© 2025 800Words | Learn smarter, one flashcard at a time.</p>
</footer>

</body>
</html>
