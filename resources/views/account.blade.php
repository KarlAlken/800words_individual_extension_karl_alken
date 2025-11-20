<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>800Words - Account</title>
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

  <main class="container">
    <h1>Account</h1>

    {{-- I show success or error messages --}}
    @if (session('success'))
      <div style="color: green; margin-bottom: 20px;">{{ session('success') }}</div>
    @endif

    @if (session('error'))
      <div style="color: red; margin-bottom: 20px;">{{ session('error') }}</div>
    @endif

    {{-- I show login form if user is not logged in --}}
    @if (!auth()->check())
      <section>
        <h2>Log In</h2>
        <form method="POST" action="{{ route('login') }}">
          @csrf
          <label for="email">Email</label><br>
          <input id="email" name="email" type="email" required><br><br>
          <label for="password">Password</label><br>
          <input id="password" name="password" type="password" required><br><br>
          <button class="button" type="submit">Log in</button>
        </form>
      </section>
    @else
      {{-- I show account info if user is logged in --}}
      <section>
        <h2>Welcome, {{ auth()->user()->name }}!</h2>
        <p>Email: {{ auth()->user()->email }}</p>
        @if (auth()->user()->isAdmin())
          <p><strong>Role: Admin</strong></p>
        @else
          <p>Role: User</p>
        @endif
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button class="button" type="submit">Log out</button>
        </form>
      </section>
    @endif
  </main>

<footer>
  <p>© 2025 800Words | Learn smarter, one flashcard at a time.</p>
</footer>
</body>
</html>
