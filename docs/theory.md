# Web Technologies – Course Theory for 800Words

Scope: Use only what is written here. Keep code minimal, stick to the simplest Laravel/PHP/JS/HTML/CSS patterns covered below. No extra libraries, no advanced features beyond this document. Always use MVC structure.

---

## 0\) Coding Guidelines

* Comments: minimal, first-person student tone.  
  * Example (PHP/Blade): `{{-- I pass data to the view --}}`  
  * Example (PHP): `// I validate input later`  
  * Example (JS): `// I toggle the card`

* Style:  
  * Prefer clear names over comments.  
  * Keep files small; one responsibility per function/method.  
  * No fluff: only code needed to satisfy the requirement.

* URLs & assets in Blade:  
  * Routes via `route('name')`.  
  * Public assets via `asset('path/to/file.css')`.

* Security baseline:  
  * Forms using POST must include `@csrf`.

---

## 1\) The Web & HTTP (Lec 1\)

* Client–Server over HTTP/HTTPS. Client sends request, server returns response.  
* URL: `protocol://host:port/path`.  
* Methods: `GET` (retrieve), `POST` (send data), others exist (HEAD, PUT, DELETE). Choose `GET` for static page/views, `POST` for form submission.  
* Status codes indicate result (e.g., 200 OK).  
* HTTP messages: start line, headers, blank line, body.

---

## 2\) HTML & CSS (Lec 2\)

* HTML structure: `<!DOCTYPE html>`, `<html>`, `<head>`, `<body>`. Use semantic elements where possible (`header`, `nav`, `main`, `footer`, etc.).

* Forms:  
  * `<form action="…" method="get|post">`  
  * GET puts data in URL; POST puts data in body.  
  * Inputs have `name=` used by the receiver.

* CSS:  
  * Prefer external stylesheet via `<link rel="stylesheet" href="…">`.  
  * Specificity: inline \> internal \> external; more specific selectors win; later rules of equal specificity override earlier.  
  * Box model: content, padding, border, margin.  
  * Layout: block vs inline; Flexbox/Grid for alignment.

---

## 3\) JavaScript, DOM, Events, JSON (Lec 3\)

* Basics: dynamically typed; primitives (number, string, boolean, null/undefined) & objects/arrays/functions. Conversions can be implicit; be careful.

* DOM:  
  * Query: `getElementById`, `querySelector`, `querySelectorAll`.  
  * Manipulate: `createElement`, `appendChild`, `innerHTML`.

* Events:  
  * `addEventListener('load'|'click'|…)`.  
  * Use delegation when elements are dynamic.  
  * Use `<script defer src="…">` so elements exist when code runs.

* JSON:  
  * Send as `application/json`.  
  * `JSON.stringify` / `JSON.parse`.

---

## 4\) Server-Side & PHP (Lec 4\)

* Server-side: server executes a program per request and returns its result.

* PHP:  
  * Dynamic types; variables start with `$`.  
  * Arrays: `[]`, zero-based; `count($a)`.  
  * Functions can be strict with `declare(strict_types=1)`.  
  * OOP: classes, `$this`, constructors `__construct`.

---

## 5\) Laravel Basics: MVC, Routing, Views, Controllers (Lec 4–5)

* MVC:  
  * Model: data \+ business logic.  
  * View: HTML output (Blade templates).  
  * Controller: receives request, talks to models, returns a view/redirect.

* Project structure (selected):  
  * `routes/web.php` – define routes.  
  * `resources/views` – Blade views (`.blade.php`).  
  * `app/Http/Controllers` – controllers.  
  * `public` – assets (CSS/JS/images).

* Routing:  
  * Basic: `Route::get('/path', fn() => view('name'));`  
  * Controller: `Route::get('/x', [XController::class, 'm']);`  
  * Named routes: `->name('x.y')` and use in Blade: `route('x.y')`.  
  * HTTP verbs: `get`, `post`, `put`, `delete`, `match`, `any`.

* Views (Blade):  
  * Render with `return view('folder.file', ['k' => $v]);`  
  * Assets with `asset('…')`.

* Blade basics:  
  * Echo: `{{ $var }}`.  
  * Control: `@if`, `@foreach`.  
  * Forms: include `@csrf` for POST.

* Redirects:  
  * `return redirect()->route('name');` (optionally with `->with('key','val')`).

---

## 6\) Models, Eloquent ORM, Migrations (Lec 6\)

* Migrations (define DB schema):  
  * Make: `php artisan make:migration create_albums_table --create=albums`  
  * Run: `php artisan migrate`  
  * `up()` defines table; `down()` drops it.  
  * Common columns: `$table->id(); $table->timestamps(); $table->string('name'); $table->integer('year');`

* Models:  
  * Make: `php artisan make:model Album`  
  * Convention: Model `Album` ↔ table `albums`.

* Basic CRUD (Eloquent):  
  * Create: `$a = new Album; $a->name='X'; $a->year=2025; $a->save();`  
  * Read: `Album::all()`, `Album::find($id)`, `Album::where('year',2025)->get();`  
  * Update: `$a = Album::find($id); $a->year=2026; $a->save();`  
  * Delete: `$a->delete();` or `Album::destroy($id);`

* Query building:  
  * Chain then `->get()` to execute.  
  * `first()` / `find()` return single (or `null`); `firstOrFail()` / `findOrFail()` throw 404\.

* Mass assignment:  
  * Allow fields with `$fillable = ['name','year'];` (or protect with `$guarded`).

* Relationships (outline only):  
  * `belongsTo`, `hasOne/hasMany`, `belongsToMany` (pivot table named alphabetically, e.g., `album_artist`). Access via `$album->artist`.

* Route-model binding (simple):  
  * `Route::get('albums/{album}', fn(Album $album) => …);`

---

## 7\) Sessions, Cookies, Auth (Lec 7\)

* Cookies:  
  * Small client-side storage; Laravel cookies are encrypted by default.

* Sessions:  
  * Store user-specific data on server; client keeps session ID (cookie).  
  * Drivers: `file` (default), `database`, `cookie`, `redis`, etc.  
  * `.env`: `SESSION_DRIVER=file`, `SESSION_LIFETIME=…`.  
  * API: `session(['k'=>'v']); session('k'); session()->forget('k');`  
  * Flash (one-request): `session()->flash('k','v');`

* Redirect with flash:  
  * `return redirect()->route('x')->with('success','Saved');`  
  * In view: `session('success')`.

* Authentication (concept):  
  * Identify users; never store plain passwords; hash (e.g., `bcrypt()`).  
  * Helpers: `auth()->attempt([...])`, `auth()->check()`, `auth()->user()`, `auth()->logout()`.

* Authorization:  
  * Protect routes with middleware: `->middleware('auth')`.  
  * Guest-only pages can use `->middleware('guest')`.  
  * Custom middleware possible (e.g., `admin`).

---

## 8\) Minimal Laravel Patterns (End-to-End)

* Route → Controller → View (index):  
  * Route: `Route::get('/albums/index',[AlbumController::class,'index'])->name('albums.index');`  
  * Controller: fetch models with Eloquent and `return view('albums.index', compact('albums'));`  
  * View: Blade loops with `@foreach($albums as $a)` and echoes `{{ $a->name }}`.

* Create \+ Store (with CSRF):  
  * GET form view → POST to `store`, include `@csrf`.  
  * In controller `store()`: read via `request()->only(['name','year'])`, create model, redirect with flash.

---

## 9\) Frontend Hooks for 800Words

* HTML: semantic layout for home/review/progress/account pages.  
* CSS: consistent colors/typography; responsive via Flexbox/Grid.  
* JS: DOM handlers for flipping cards, simple validation, small event listeners; use `defer`.  
* Blade: replace raw HTML pages with `.blade.php` and Laravel helpers (`route`, `asset`).

---

## 10\) Checklists

* Before coding:  
  * Route defined? View path correct? Controller method exists? Names consistent?

* Forms:  
  * Using `POST`? Added `@csrf`? Inputs have `name=`?

* DB:  
  * Migration created & migrated? Model exists? `$fillable` set if using mass assign?

* UI:  
  * Minimal markup; CSS external; JS `defer`.

---

