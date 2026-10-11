{{-- FAIL: resources/views/complaints/index.blade.php --}}

@extends('layouts.app')

@section('title', 'My complaints — CampusCare')

@section('content')

<div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
  <div>
    <h1 class="page-title">My complaints</h1>
    <p class="page-sub mb-0">
      {{ $complaints->total() }} {{ Str::plural('complaint', $complaints->total()) }} sent
    </p>
  </div>
  <a href="{{ route('complaints.create') }}" class="btn btn-brand">Report a problem</a>
</div>

@if (session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
@endif

@forelse ($complaints as $complaint)
  <div class="card-plain mb-3">
    <div class="d-flex justify-content-between align-items-start gap-3 mb-2">
      <div>
        <h2 style="font-size:1.02rem;font-weight:600;margin-bottom:.2rem">{{ $complaint->title }}</h2>
        <div style="font-size:.83rem;color:var(--muted)">
          {{ $complaint->location }}
          &middot; {{ $complaint->created_at->format('d M Y, g:i a') }}
        </div>
      </div>
      <span class="st st-{{ $complaint->statusColour() }}">{{ $complaint->statusLabel() }}</span>
    </div>

    <p style="font-size:.93rem;margin:.7rem 0">{{ $complaint->description }}</p>

    <div class="d-flex gap-3 align-items-center flex-wrap" style="font-size:.85rem;color:var(--muted)">
      @if ($complaint->category)
        <span class="cat-chip">{{ $complaint->category->category_name }}</span>
      @else
        <span class="cat-chip" style="background:#fdf0d9;color:#7a5106">Waiting for admin to set a category</span>
      @endif

      @if ($complaint->photo_path)
        <a href="{{ asset('storage/' . $complaint->photo_path) }}" target="_blank" style="color:var(--brand)">View photo</a>
      @endif

      @if ($complaint->status === 'completed' && $complaint->resolved_at)
        <span style="color:#1c5c30">Repaired on {{ $complaint->resolved_at->format('d M Y') }}</span>
      @endif
    </div>
  </div>
@empty
  <div class="card-plain text-center" style="padding:3.5rem 1.5rem">
    <p style="color:var(--muted);margin-bottom:1.25rem">You have not reported anything yet.</p>
    <a href="{{ route('complaints.create') }}" class="btn btn-brand">Report a problem</a>
  </div>
@endforelse

<div class="mt-4">{{ $complaints->links() }}</div>

@endsection
