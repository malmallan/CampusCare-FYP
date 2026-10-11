
{{-- FILE: resources/views/technician/jobs/index.blade.php --}}

@extends('layouts.app')

@section('title', 'Report Log — CampusCare')

@section('content')

<style>
  .proof-upload {
    background: #f8fafc;
    border: 1px dashed #a8b9cc;
    border-radius: 10px;
    padding: 1rem;
    margin: 1rem 0;
  }

  .proof-upload .form-label {
    font-weight: 600;
  }

  .proof-preview {
    display: none;
    max-width: 260px;
    max-height: 220px;
    width: auto;
    height: auto;
    object-fit: contain;
    border: 1px solid var(--line);
    border-radius: 8px;
    margin-top: .8rem;
  }

  .proof-saved {
    margin-top: 1rem;
    padding: 1rem;
    background: #f2f7f3;
    border: 1px solid #d5e7da;
    border-radius: 10px;
  }

  .proof-saved img {
    max-width: 100%;
    max-height: 260px;
    border-radius: 8px;
    object-fit: contain;
    display: block;
    margin-top: .65rem;
  }
</style>

<h1 class="page-title">Report Log</h1>

<p class="page-sub">
  Repair jobs assigned to you and a record of the work you have completed.
</p>

<div class="tabs">
  <a href="{{ route('jobs.index') }}"
     class="{{ $tab === 'active' ? 'on' : '' }}">
    Active <span class="count">{{ $counts['active'] }}</span>
  </a>

  <a href="{{ route('jobs.index', ['tab' => 'history']) }}"
     class="{{ $tab === 'history' ? 'on' : '' }}">
    History <span class="count">{{ $counts['history'] }}</span>
  </a>
</div>

@if (session('success'))
  <div class="alert alert-success">
    {{ session('success') }}
  </div>
@endif

@if ($errors->has('status'))
  <div class="alert alert-danger">
    {{ $errors->first('status') }}
  </div>
@endif

@forelse ($jobs as $job)

  <div class="card-plain mb-3">

    {{-- JOB HEADER --}}
    <div class="d-flex justify-content-between align-items-start gap-3 mb-2">

      <div>
        <h2 style="font-size:1.02rem;font-weight:600;margin-bottom:.2rem">
          {{ $job->title }}
        </h2>

        <div style="font-size:.83rem;color:var(--muted)">
          {{ $job->location }}
          &middot;
          Reported by {{ $job->user->name }}
          &middot;
          {{ $job->created_at->format('d M Y, g:i a') }}
        </div>
      </div>

      <span class="st st-{{ $job->statusColour() }}">
        {{ $job->statusLabel() }}
      </span>

    </div>

    {{-- COMPLAINT DESCRIPTION --}}
    <p style="font-size:.93rem;margin:.7rem 0">
      {{ $job->description }}
    </p>

    <div class="d-flex gap-2 align-items-center flex-wrap mb-3">

      @if ($job->category)
        <span class="cat-chip">
          {{ $job->category->category_name }}
        </span>
      @endif

      @if ($job->photo_path)
        <a href="{{ asset('storage/' . $job->photo_path) }}"
           target="_blank"
           rel="noopener noreferrer"
           style="font-size:.85rem;color:var(--brand)">
          View complaint photo
        </a>
      @endif

    </div>

    <div class="row g-4">

      {{-- LEFT: ACTIONS --}}
      <div class="col-md-6">

        @if ($job->status === 'assigned')

          <form method="POST"
                action="{{ route('jobs.start', $job) }}"
                class="m-0">

            @csrf
            @method('PATCH')

            <button type="submit" class="btn btn-brand">
              Start work
            </button>

          </form>

        @elseif ($job->status === 'in_progress')

          @php
            $bag = $errors->getBag('job' . $job->id);
          @endphp

          <form method="POST"
                action="{{ route('jobs.complete', $job) }}"
                enctype="multipart/form-data"
                class="m-0">

            @csrf
            @method('PATCH')

            {{-- WORK NOTES --}}
            <label class="form-label" for="note-{{ $job->id }}">
              What did you repair?
            </label>

            <textarea
              class="form-control mb-2 {{ $bag->has('note') ? 'is-invalid' : '' }}"
              id="note-{{ $job->id }}"
              name="note"
              rows="3"
              minlength="5"
              maxlength="500"
              placeholder="Replaced both fluorescent tubes and the starter."
              required>{{ $bag->any() ? old('note') : '' }}</textarea>

            @if ($bag->has('note'))
              <div class="invalid-feedback d-block mb-2">
                {{ $bag->first('note') }}
              </div>
            @endif

            {{-- COMPLETION PHOTO --}}
            <div class="proof-upload">

              <label class="form-label" for="proof-{{ $job->id }}">
                Proof of Work (Photo)
              </label>

              <input
                type="file"
                class="form-control {{ $bag->has('completion_photo') ? 'is-invalid' : '' }}"
                id="proof-{{ $job->id }}"
                name="completion_photo"
                accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                onchange="previewProof(this, {{ $job->id }})">

              <div class="form-text mt-2">
                Optional. Upload a JPG, PNG or WEBP image (maximum 5 MB).
              </div>

              @if ($bag->has('completion_photo'))
                <div class="invalid-feedback d-block">
                  {{ $bag->first('completion_photo') }}
                </div>
              @endif

              <img
                id="proof-preview-{{ $job->id }}"
                class="proof-preview"
                alt="Selected proof of work preview">

            </div>

            <button type="submit" class="btn btn-brand">
              Mark as completed
            </button>

          </form>

        @else

          {{-- COMPLETED JOB --}}
          <div class="done-box">

            <strong style="color:#1c5c30">
              Completed
            </strong>

            on {{ $job->resolved_at?->format('d M Y, g:i a') }}

            @if ($job->timeTaken())
              <div style="color:var(--muted);margin-top:.25rem">
                Time taken: {{ $job->timeTaken() }}
              </div>
            @endif

          </div>

          {{-- SAVED COMPLETION PHOTO --}}
          @if ($job->completion_photo)

            <div class="proof-saved">

              <strong style="color:#1c5c30">
                Proof of Work
              </strong>

              <p style="font-size:.85rem;color:var(--muted);margin:.3rem 0">
                Photo uploaded when the maintenance work was completed.
              </p>

              <a href="{{ asset('storage/' . $job->completion_photo) }}"
                 target="_blank"
                 rel="noopener noreferrer">

                <img
                  src="{{ asset('storage/' . $job->completion_photo) }}"
                  alt="Completed maintenance work">

              </a>

              <a href="{{ asset('storage/' . $job->completion_photo) }}"
                 target="_blank"
                 rel="noopener noreferrer"
                 class="d-inline-block mt-2"
                 style="font-size:.85rem;color:var(--brand)">
                View full photo
              </a>

            </div>

          @endif

        @endif

      </div>

      {{-- RIGHT: STATUS HISTORY --}}
      <div class="col-md-6">

        <div class="section-label">
          History
        </div>

        @include('partials.timeline', [
          'logs' => $job->statusLogs
        ])

      </div>

    </div>

  </div>

@empty

  <div class="card-plain text-center"
       style="padding:3.5rem 1.5rem">

    <p style="color:var(--muted);margin-bottom:0">

      @if ($tab === 'history')
        You have not completed any jobs yet.
      @else
        No active jobs. New assignments from the admin will appear here.
      @endif

    </p>

  </div>

@endforelse

<div class="mt-4">
  {{ $jobs->links() }}
</div>

<script>
  function previewProof(input, jobId) {
    const preview = document.getElementById('proof-preview-' + jobId);

    if (!preview) {
      return;
    }

    if (preview.dataset.objectUrl) {
      URL.revokeObjectURL(preview.dataset.objectUrl);
      delete preview.dataset.objectUrl;
    }

    preview.removeAttribute('src');
    preview.style.display = 'none';

    if (!input.files || !input.files[0]) {
      return;
    }

    const file = input.files[0];

    const allowedTypes = [
      'image/jpeg',
      'image/png',
      'image/webp'
    ];

    if (!allowedTypes.includes(file.type)) {
      alert('Please select a JPG, PNG or WEBP image.');
      input.value = '';
      return;
    }

    if (file.size > 5 * 1024 * 1024) {
      alert('The image must not exceed 5 MB.');
      input.value = '';
      return;
    }

    const objectUrl = URL.createObjectURL(file);
    preview.dataset.objectUrl = objectUrl;
    preview.src = objectUrl;
    preview.style.display = 'block';
  }
</script>

@endsection
