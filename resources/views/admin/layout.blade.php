<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>800Words - Admin</title>
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

  <div class="admin-page">
    <aside class="admin-sidebar">
      <h2>Admin Panel</h2>
      <nav class="admin-nav">
        <a href="{{ route('admin') }}" class="{{ ($activeSection ?? 'languages') === 'languages' ? 'active' : '' }}">Languages</a>
        <a href="{{ route('admin.flashcards.index') }}" class="{{ ($activeSection ?? '') === 'flashcards' ? 'active' : '' }}">Flashcards</a>
      </nav>
    </aside>

    <main class="admin-main">
      @if (session('status'))
        <div class="alert success">{{ session('status') }}</div>
      @endif

      @if ($errors->any())
        <div class="alert error">
          <ul>
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      @yield('admin-content')
    </main>
  </div>

  <footer>
    <p>© 2025 800Words | Learn smarter, one flashcard at a time.</p>
  </footer>
</body>
</html>
