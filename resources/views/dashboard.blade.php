{{-- FAIL: resources/views/dashboard.blade.php --}}
{{-- Teknisyen tidak lagi sampai ke sini; mereka terus ke Report Log. --}}

@extends('layouts.app')

@section('title', 'Dashboard — CampusCare')

@section('content')

<h1 class="page-title">Welcome, {{ auth()->user()->name }}</h1>
<p class="page-sub">Signed in as {{ auth()->user()->email }}</p>

@if (auth()->user()->isStudent())

  <div class="card-plain">
    <h2 style="font-size:1.05rem;font-weight:600;margin-bottom:.5rem">Something broken on campus?</h2>
    <p style="color:var(--muted);font-size:.94rem">
      Send a complaint and follow it from the moment you report it
      until a technician closes the job.
    </p>
    <div class="d-flex gap-2 flex-wrap mt-3">
      <a href="{{ route('complaints.create') }}" class="btn btn-brand">Report a problem</a>
      <a href="{{ route('complaints.index') }}" class="btn btn-link">See my complaints</a>
    </div>
  </div>

@elseif (auth()->user()->isAdmin())

  <div class="card-plain">
    <h2 style="font-size:1.05rem;font-weight:600;margin-bottom:.5rem">Complaints waiting for you</h2>
    <p style="color:var(--muted);font-size:.94rem">
      Review each complaint, confirm its category, and assign it to a technician.
    </p>
    <a href="{{ route('admin.complaints.index') }}" class="btn btn-brand mt-2">Open all complaints</a>
  </div>

@endif

@endsection
