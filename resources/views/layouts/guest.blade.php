{{-- FAIL: resources/views/layouts/guest.blade.php --}}
{{--
  Layout untuk halaman SEBELUM log masuk.
  Bahagian kiri (logo, tajuk, perenggan) sama untuk kedua-dua
  halaman log masuk dan pendaftaran, jadi ia ditulis SEKALI di sini.

  @yield('form')   = lubang tempat borang dimasukkan
  @yield('helper') = teks di penjuru kanan atas
--}}
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title', 'Sign in') — CampusCare</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
  :root {
    --paper:#f4f3ef; --card:#ffffff; --ink:#16181c;
    --muted:#6b6a64; --line:#dedcd5;
    --brand:#1d4e89; --brand-d:#163c6b;
  }
  * { box-sizing:border-box; }
  body {
    font-family:'Inter',system-ui,sans-serif; font-size:16px; line-height:1.6;
    color:var(--ink); background:var(--paper); margin:0; min-height:100vh;
    background-image:
      linear-gradient(rgba(0,0,0,.018) 1px,transparent 1px),
      linear-gradient(90deg,rgba(0,0,0,.018) 1px,transparent 1px);
    background-size:26px 26px;
  }
  .topbar { display:flex; align-items:center; justify-content:space-between;
            padding:1.4rem 2rem; max-width:1240px; margin:0 auto; }
  .brandbar { display:flex; align-items:center; gap:.9rem; }
  .brandbar img { height:40px; width:auto; display:block; }
  .brandbar .divider { width:1px; height:26px; background:var(--line); }
  .logo { font-weight:800; font-size:1.15rem; letter-spacing:-.02em; }
  .logo span { color:var(--brand); }
  @media (max-width:575px) {
    .brandbar img { height:32px; }
    .logo { font-size:1rem; }
  }
  .helper { font-size:.88rem; color:var(--muted); }
  .helper a { color:var(--brand); text-decoration:none; font-weight:500; }
  .helper a:hover { text-decoration:underline; }

  .split { max-width:1240px; margin:0 auto; padding:2rem 2rem 5rem;
           display:grid; gap:4rem; grid-template-columns:1fr; align-items:center; }
  @media (min-width:992px) {
    .split { grid-template-columns:1.05fr .95fr; gap:5rem; padding-top:4rem; }
  }
  .eyebrow { font-size:.74rem; font-weight:600; letter-spacing:.13em;
             text-transform:uppercase; color:var(--muted); margin-bottom:1.4rem; }
  h1.hero { font-size:clamp(2.4rem,6vw,3.9rem); font-weight:800;
            line-height:1.04; letter-spacing:-.035em; margin:0 0 1.5rem; }
  h1.hero em { font-style:normal; color:var(--brand); }
  .lede { font-size:1.06rem; color:var(--muted); max-width:30rem; margin-bottom:0; }

  .panel { background:var(--card); border:1px solid var(--line);
           border-radius:14px; padding:2.25rem;
           box-shadow:0 1px 2px rgba(0,0,0,.03), 0 14px 40px rgba(0,0,0,.05); }
  @media (min-width:576px) { .panel { padding:2.75rem; } }
  .panel h2 { font-size:1.45rem; font-weight:700; letter-spacing:-.02em; margin-bottom:.35rem; }
  .panel .sub { color:var(--muted); font-size:.92rem; margin-bottom:2rem; }

  .form-label { font-size:.86rem; font-weight:500; margin-bottom:.4rem; color:var(--ink); }
  .form-control { min-height:48px; border:1px solid var(--line); border-radius:9px;
                  font-size:.96rem; background:#fcfcfb; padding:.6rem .9rem; }
  .form-control:focus { border-color:var(--brand); background:#fff;
                        box-shadow:0 0 0 3px rgba(29,78,137,.13); }
  .form-control:focus-visible, .btn:focus-visible, a:focus-visible {
    outline:3px solid #8fb0d8; outline-offset:2px;
  }
  .form-text { font-size:.82rem; color:var(--muted); }
  .btn-brand { background:var(--brand); color:#fff; border:none; border-radius:9px;
               min-height:48px; font-weight:600; font-size:.96rem; width:100%;
               transition:background .15s ease; }
  .btn-brand:hover { background:var(--brand-d); color:#fff; }
  .row-between { display:flex; align-items:center; justify-content:space-between;
                 margin-bottom:1.6rem; flex-wrap:wrap; gap:.5rem; }
  .row-between a { font-size:.86rem; color:var(--brand); text-decoration:none; }
  .swap { text-align:center; font-size:.9rem; color:var(--muted);
          margin-top:1.75rem; padding-top:1.75rem; border-top:1px solid var(--line); }
  .swap a { color:var(--brand); font-weight:500; text-decoration:none; }
</style>
</head>
<body>

<div class="topbar">
  <div class="brandbar">
    {{-- asset() menunjuk ke folder public/ dalam projek awak --}}
    <img src="{{ asset('images/logo-uptm.png') }}" alt="Universiti Poly-Tech Malaysia">
    <span class="divider"></span>
    <span class="logo">Campus<span>Care</span></span>
  </div>
  <div class="helper">@yield('helper')</div>
</div>

<div class="split">
  <div>
    <div class="eyebrow">Campus Complaint &amp; Maintenance</div>
    <h1 class="hero">Let us know once.<br>We'll <em>get it done</em>.</h1>
    <p class="lede">
      Your feedback gets a category, an owner, and a status you can check
      any time — no more wondering whether anyone read it.
    </p>
  </div>

  <div>
    @yield('form')
  </div>
</div>

</body>
</html>
