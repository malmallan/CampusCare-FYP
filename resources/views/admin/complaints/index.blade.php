{{-- FAIL: resources/views/admin/complaints/index.blade.php --}}

@extends('layouts.app')

@section('title', 'All complaints — CampusCare')

@section('content')

<h1 class="page-title">All complaints</h1>
<p class="page-sub">{{ $complaints->total() }} total</p>

@if (session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
@endif

<form method="GET" action="{{ route('admin.complaints.index') }}" class="d-flex gap-2 flex-wrap mb-4">
  <select name="status" class="form-select" style="max-width:190px">
    <option value="">All statuses</option>
    <option value="new"         @selected(request('status') === 'new')>New</option>
    <option value="assigned"    @selected(request('status') === 'assigned')>Assigned</option>
    <option value="in_progress" @selected(request('status') === 'in_progress')>In Progress</option>
    <option value="completed"   @selected(request('status') === 'completed')>Completed</option>
  </select>

  <select name="category" class="form-select" style="max-width:190px">
    <option value="">All categories</option>
    @foreach ($categories as $category)
      <option value="{{ $category->id }}" @selected(request('category') == $category->id)>
        {{ $category->category_name }}
      </option>
    @endforeach
  </select>

  <button type="submit" class="btn btn-brand">Filter</button>

  @if (request('status') || request('category'))
    <a href="{{ route('admin.complaints.index') }}" class="btn btn-link">Clear</a>
  @endif
</form>

<div class="card-plain" style="padding:0;overflow:hidden">
  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead style="background:#f7f6f2">
        <tr>
          <th style="font-size:.78rem;text-transform:uppercase;color:var(--muted)">Complaint</th>
          <th style="font-size:.78rem;text-transform:uppercase;color:var(--muted)">Category</th>
          <th style="font-size:.78rem;text-transform:uppercase;color:var(--muted)">Status</th>
          <th style="font-size:.78rem;text-transform:uppercase;color:var(--muted)">Assigned</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @forelse ($complaints as $complaint)
          <tr>
            <td>
              <div style="font-weight:500">{{ $complaint->title }}</div>
              <div style="font-size:.82rem;color:var(--muted)">
                {{ $complaint->user->name }}
                &middot; {{ $complaint->location }}
                &middot; {{ $complaint->created_at->format('d M, g:i a') }}
              </div>
            </td>
            <td>
              @if ($complaint->category)
                <span class="cat-chip">{{ $complaint->category->category_name }}</span>
              @else
                <span class="cat-chip" style="background:#fdf0d9;color:#7a5106">Not set</span>
              @endif
            </td>
            <td><span class="st st-{{ $complaint->statusColour() }}">{{ $complaint->statusLabel() }}</span></td>
            <td style="font-size:.86rem;color:var(--muted)">{{ $complaint->technician?->name ?? '—' }}</td>
            <td class="text-end">
              <a href="{{ route('admin.complaints.show', $complaint) }}"
                 class="btn btn-sm {{ $complaint->status === 'new' ? 'btn-brand' : 'btn-outline-secondary' }}">
                {{ $complaint->status === 'new' ? 'Assign' : 'View' }}
              </a>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="5" class="text-center" style="padding:3rem;color:var(--muted)">No complaints match this filter.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<div class="mt-4">{{ $complaints->links() }}</div>

@endsection
