
{{-- FILE: resources/views/admin/complaints/show.blade.php --}}

@extends('layouts.app')

@section('title', 'Complaint #' . $complaint->id . ' — CampusCare')

@section('content')

<style>
  .evidence-card {
    background: #f2f7f3;
    border: 1px solid #d5e7da;
    border-radius: 12px;
    padding: 1.5rem;
    margin-top: 1rem;
  }

  .evidence-title {
    font-size: 1rem;
    font-weight: 700;
    color: #1c5c30;
    margin-bottom: .5rem;
  }

  .evidence-photo {
    max-width: 100%;
    max-height: 380px;
    object-fit: contain;
    border: 1px solid #d5e7da;
    border-radius: 9px;
    background: white;
    margin-top: .8rem;
  }

  .evidence-link {
    display: inline-block;
    margin-top: .7rem;
    font-size: .88rem;
    color: var(--brand);
    text-decoration: none;
  }

  .evidence-link:hover {
    text-decoration: underline;
  }
</style>

<a href="{{ route('admin.complaints.index') }}"
   style="font-size:.88rem;color:var(--brand);text-decoration:none">
  &larr; Back to all complaints
</a>

<h1 class="page-title mt-3">
  {{ $complaint->title }}
</h1>

<p class="page-sub">
  Complaint #{{ $complaint->id }}
  &middot; {{ $complaint->location }}
  &middot; Reported by {{ $complaint->user->name }}
  on {{ $complaint->created_at->format('d M Y, g:i a') }}
</p>

<div class="row g-4">

  {{-- LEFT COLUMN --}}
  <div class="col-lg-7">

    {{-- COMPLAINT DETAILS --}}
    <div class="card-plain">

      <div class="d-flex gap-2 flex-wrap mb-3">

        <span class="st st-{{ $complaint->statusColour() }}">
          {{ $complaint->statusLabel() }}
        </span>

        @if ($complaint->category)

          <span class="cat-chip">
            {{ $complaint->category->category_name }}
          </span>

        @else

          <span class="cat-chip"
                style="background:#fdf0d9;color:#7a5106">
            Category not set
          </span>

        @endif

      </div>

      <p style="font-size:.95rem">
        {{ $complaint->description }}
      </p>

      {{-- ORIGINAL STUDENT COMPLAINT PHOTO --}}
      @if ($complaint->photo_path)

        <div class="mt-3">

          <div class="section-label">
            Complaint Photo
          </div>

          <a href="{{ asset('storage/' . $complaint->photo_path) }}"
             target="_blank"
             rel="noopener noreferrer">

            <img
              src="{{ asset('storage/' . $complaint->photo_path) }}"
              alt="Photo of the reported problem"
              class="img-fluid rounded mt-2"
              style="max-height:360px;border:1px solid var(--line)">

          </a>

        </div>

      @endif

    </div>

    {{-- TECHNICIAN COMPLETION EVIDENCE --}}
    @if ($complaint->status === 'completed')

      <div class="evidence-card">

        <h2 class="evidence-title">
          Maintenance Completion Evidence
        </h2>

        <p style="font-size:.9rem;color:#41474f;margin-bottom:.5rem">
          This complaint was completed on
          <strong>
            {{ $complaint->resolved_at?->format('d M Y, g:i a') ?? 'Date unavailable' }}
          </strong>.
        </p>

        {{-- TECHNICIAN WORK NOTES --}}
        @php
          $completionLog = $complaint->statusLogs
              ->where('new_status', 'completed')
              ->sortByDesc('id')
              ->first();
        @endphp

        @if ($completionLog && $completionLog->note)

          <div class="mt-3">

            <div class="section-label">
              Technician Work Notes
            </div>

            <div style="background:white;border:1px solid #d5e7da;
                        border-radius:8px;padding:.85rem 1rem;
                        font-size:.9rem;white-space:pre-wrap">{{ $completionLog->note }}</div>

          </div>

        @endif

        {{-- TECHNICIAN COMPLETION PHOTO --}}
        @if ($complaint->completion_photo)

          <div class="mt-3">

            <div class="section-label">
              Proof of Work Photo
            </div>

            <a href="{{ asset('storage/' . $complaint->completion_photo) }}"
               target="_blank"
               rel="noopener noreferrer">

              <img
                src="{{ asset('storage/' . $complaint->completion_photo) }}"
                alt="Photo showing completed maintenance work"
                class="evidence-photo">

            </a>

            <div>
              <a href="{{ asset('storage/' . $complaint->completion_photo) }}"
                 target="_blank"
                 rel="noopener noreferrer"
                 class="evidence-link">
                View full photo
              </a>
            </div>

          </div>

        @else

          <p style="font-size:.88rem;color:var(--muted);margin-top:1rem;margin-bottom:0">
            No completion photo was uploaded for this complaint.
          </p>

        @endif

      </div>

    @endif

    {{-- STATUS HISTORY --}}
    <div class="card-plain mt-3">

      <div class="section-label">
        History
      </div>

      @include('partials.timeline', [
        'logs' => $complaint->statusLogs
      ])

    </div>

  </div>

  {{-- RIGHT COLUMN --}}
  <div class="col-lg-5">

    <div class="card-plain">

      <h2 style="font-size:1rem;font-weight:600;margin-bottom:1rem">
        {{ $complaint->assigned_to ? 'Reassign' : 'Assign this complaint' }}
      </h2>

      @if ($complaint->status === 'completed')

        <div class="done-box">
          This complaint was completed on
          {{ $complaint->resolved_at?->format('d M Y, g:i a') }}.
          It can no longer be reassigned.
        </div>

      @else

        <form method="POST"
              action="{{ route('admin.complaints.assign', $complaint) }}">

          @csrf
          @method('PATCH')

          {{-- CATEGORY --}}
          <div class="mb-3">

            <label class="form-label" for="category_id">
              Category
            </label>

            <select
              class="form-select @error('category_id') is-invalid @enderror"
              id="category_id"
              name="category_id"
              required>

              <option value="">
                Choose a category
              </option>

              @foreach ($categories as $category)

                <option
                  value="{{ $category->id }}"
                  @selected(old('category_id', $complaint->category_id) == $category->id)>

                  {{ $category->category_name }}

                </option>

              @endforeach

            </select>

            <div class="form-text">

              @if ($complaint->category)
                Set automatically by the system.
                Change it if it is wrong.
              @else
                The system could not match any keyword.
                Please choose one.
              @endif

            </div>

            @error('category_id')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
            @enderror

          </div>

          {{-- TECHNICIAN --}}
          <div class="mb-4">

            <label class="form-label" for="assigned_to">
              Technician
            </label>

            <select
              class="form-select @error('assigned_to') is-invalid @enderror"
              id="assigned_to"
              name="assigned_to"
              required>

              <option value="">
                Choose a technician
              </option>

              @foreach ($technicians as $technician)

                <option
                  value="{{ $technician->id }}"
                  @selected(old('assigned_to', $complaint->assigned_to) == $technician->id)>

                  {{ $technician->name }}

                </option>

              @endforeach

            </select>

            @error('assigned_to')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
            @enderror

          </div>

          <button type="submit"
                  class="btn btn-brand w-100">

            {{ $complaint->assigned_to ? 'Update assignment' : 'Assign' }}

          </button>

        </form>

      @endif

    </div>

  </div>

</div>

@endsection
