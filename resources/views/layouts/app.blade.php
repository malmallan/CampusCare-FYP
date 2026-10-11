
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title', 'CampusCare')</title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
  :root {
    --paper:#f4f3ef;
    --card:#fff;
    --ink:#16181c;
    --muted:#6b6a64;
    --line:#dedcd5;
    --brand:#1d4e89;
    --brand-d:#163c6b;
  }

  body {
    font-family:'Inter',system-ui,sans-serif;
    background:var(--paper);
    color:var(--ink);
    font-size:16px;
    line-height:1.6;
    margin:0;
  }

  .nav-bar {
    background:var(--brand);
    color:#fff;
    padding:.8rem 1.5rem;
  }

  .nav-inner {
    max-width:1100px;
    margin:0 auto;
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:1rem;
    flex-wrap:wrap;
  }

  .nav-brand {
    display:flex;
    align-items:center;
    gap:.75rem;
  }

  .nav-brand img {
    height:30px;
    background:#fff;
    padding:3px 6px;
    border-radius:5px;
  }

  .nav-brand .name {
    font-weight:700;
    letter-spacing:-.01em;
    color:#fff;
  }

  .nav-links {
    display:flex;
    align-items:center;
    gap:1.25rem;
    flex-wrap:wrap;
  }

  .nav-links a {
    color:rgba(255,255,255,.85);
    text-decoration:none;
    font-size:.92rem;
    font-weight:500;
  }

  .nav-links a:hover,
  .nav-links a.active {
    color:#fff;
  }

  .nav-links a.active {
    text-decoration:underline;
    text-underline-offset:5px;
  }

  .btn-out {
    background:transparent;
    border:1px solid rgba(255,255,255,.45);
    color:#fff;
    border-radius:8px;
    padding:.35rem .9rem;
    font-size:.86rem;
  }

  .btn-out:hover {
    background:rgba(255,255,255,.12);
    color:#fff;
  }

  .wrap {
    max-width:1100px;
    margin:0 auto;
    padding:2.5rem 1.5rem 4rem;
  }

  .page-title {
    font-size:1.6rem;
    font-weight:700;
    letter-spacing:-.02em;
    margin-bottom:.25rem;
  }

  .page-sub {
    color:var(--muted);
    font-size:.94rem;
    margin-bottom:2rem;
  }

  .card-plain {
    background:var(--card);
    border:1px solid var(--line);
    border-radius:12px;
    padding:1.5rem;
  }

  .section-label {
    font-size:.74rem;
    font-weight:600;
    letter-spacing:.08em;
    text-transform:uppercase;
    color:var(--muted);
    margin-bottom:.6rem;
  }

  .form-label {
    font-size:.88rem;
    font-weight:500;
    margin-bottom:.4rem;
  }

  .form-control,
  .form-select {
    min-height:46px;
    border:1px solid var(--line);
    border-radius:9px;
    font-size:.95rem;
    background:#fcfcfb;
    padding:.55rem .85rem;
  }

  .form-control:focus,
  .form-select:focus {
    border-color:var(--brand);
    background:#fff;
    box-shadow:0 0 0 3px rgba(29,78,137,.13);
  }

  .form-control:focus-visible,
  .btn:focus-visible,
  a:focus-visible {
    outline:3px solid #8fb0d8;
    outline-offset:2px;
  }

  .form-text {
    font-size:.82rem;
    color:var(--muted);
  }

  .btn-brand {
    background:var(--brand);
    color:#fff;
    border:none;
    border-radius:9px;
    min-height:46px;
    font-weight:600;
    font-size:.94rem;
    padding:0 1.5rem;
  }

  .btn-brand:hover {
    background:var(--brand-d);
    color:#fff;
  }

  /* Complaint status badges */
  .st {
    font-size:.76rem;
    font-weight:600;
    padding:.3rem .65rem;
    border-radius:999px;
    white-space:nowrap;
    display:inline-block;
  }

  .st-secondary {
    background:#eceff3;
    color:#41474f;
  }

  .st-info {
    background:#e3edf9;
    color:#1a4d80;
  }

  .st-warning {
    background:#fdf0d9;
    color:#7a5106;
  }

  .st-success {
    background:#e2f1e6;
    color:#1c5c30;
  }

  .cat-chip {
    font-size:.76rem;
    background:#f0f2f5;
    color:#41474f;
    padding:.28rem .6rem;
    border-radius:6px;
  }

  /* Active and history tabs */
  .tabs {
    display:flex;
    gap:.4rem;
    margin-bottom:1.75rem;
    flex-wrap:wrap;
  }

  .tabs a {
    text-decoration:none;
    font-size:.9rem;
    font-weight:500;
    color:var(--ink);
    padding:.5rem 1rem;
    border-radius:999px;
    border:1px solid var(--line);
    background:#fff;
  }

  .tabs a.on {
    background:var(--brand);
    border-color:var(--brand);
    color:#fff;
  }

  .tabs .count {
    font-size:.76rem;
    opacity:.75;
    margin-left:.3rem;
  }

  /* Complaint status timeline */
  .tl {
    list-style:none;
    padding-left:0;
    margin:0;
  }

  .tl li {
    position:relative;
    padding-left:1.4rem;
    padding-bottom:.9rem;
  }

  .tl li:last-child {
    padding-bottom:0;
  }

  .tl li::before {
    content:"";
    position:absolute;
    left:0;
    top:.45rem;
    width:9px;
    height:9px;
    border-radius:50%;
    background:var(--brand);
  }

  .tl li.tl-done::before {
    background:#1c5c30;
  }

  .tl li:not(:last-child)::after {
    content:"";
    position:absolute;
    left:4px;
    top:1.1rem;
    bottom:0;
    width:1px;
    background:var(--line);
  }

  .tl-title {
    font-size:.88rem;
    font-weight:600;
  }

  .tl-by {
    font-weight:400;
    color:var(--muted);
  }

  .tl-time {
    font-size:.78rem;
    color:var(--muted);
  }

  .tl-note {
    font-size:.85rem;
    margin-top:.25rem;
    background:#f7f6f2;
    border-radius:6px;
    padding:.35rem .6rem;
  }

  .done-box {
    background:#f2f7f3;
    border-radius:9px;
    padding:.9rem 1rem;
    font-size:.9rem;
  }

  /* User Manual navigation */
  .manual-nav-link {
    display:inline-flex;
    align-items:center;
    gap:.4rem;
  }

  @media (max-width:768px) {
    .nav-inner {
      align-items:flex-start;
    }

    .nav-links {
      gap:.8rem;
    }

    .wrap {
      padding:1.5rem 1rem 3rem;
    }
  }
</style>
</head>

<body>

<div class="nav-bar">
  <div class="nav-inner">

    <div class="nav-brand">
      <img src="{{ asset('images/logo-uptm.png') }}" alt="UPTM">
      <span class="name">CampusCare</span>
    </div>

    <div class="nav-links">

      {{-- STUDENT NAVIGATION --}}
      @if (auth()->user()->isStudent())

        <a href="{{ route('complaints.index') }}"
           class="{{ request()->routeIs('complaints.index') ? 'active' : '' }}">
          My complaints
        </a>

        <a href="{{ route('complaints.create') }}"
           class="{{ request()->routeIs('complaints.create') ? 'active' : '' }}">
          Report a problem
        </a>

      {{-- ADMIN NAVIGATION --}}
      @elseif (auth()->user()->isAdmin())

        <a href="{{ route('admin.complaints.index') }}"
           class="{{ request()->routeIs('admin.complaints.*') ? 'active' : '' }}">
          All complaints
        </a>

      {{-- TECHNICIAN NAVIGATION --}}
      @elseif (auth()->user()->isTechnician())

        <a href="{{ route('jobs.index') }}"
           class="{{ request()->routeIs('jobs.*') ? 'active' : '' }}">
          Report Log
        </a>

      @endif

      {{-- USER MANUAL - ALL ROLES --}}
      <a href="{{ route('manual.index') }}"
         class="manual-nav-link {{ request()->routeIs('manual.index') ? 'active' : '' }}">
        User Manual
      </a>

      {{-- LOGGED IN USER --}}
      <span style="font-size:.86rem;opacity:.75">
        {{ auth()->user()->name }}
      </span>

      {{-- LOGOUT --}}
      <form method="POST"
            action="{{ route('logout') }}"
            class="m-0">
        @csrf

        <button type="submit" class="btn btn-out">
          Sign out
        </button>
      </form>

    </div>
  </div>
</div>

<div class="wrap">
  @yield('content')
</div>

</body>
</html>
