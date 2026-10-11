{{-- FAIL: resources/views/complaints/create.blade.php --}}

@extends('layouts.app')

@section('title', 'Report a problem — CampusCare')

@section('content')
<div class="row justify-content-center">
  <div class="col-lg-8">

    <h1 class="page-title">Report a problem</h1>
    <p class="page-sub">Tell us what is broken. The system sends it to the right team automatically.</p>

    <div class="card-plain">
      <form method="POST" action="{{ route('complaints.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
          <label class="form-label" for="title">What is broken?</label>
          <input type="text" class="form-control @error('title') is-invalid @enderror"
                 id="title" name="title" value="{{ old('title') }}"
                 placeholder="Broken light in Lab 2" maxlength="120" required autofocus>
          @error('title')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="mb-3">
          <label class="form-label" for="description">Describe the problem</label>
          <textarea class="form-control @error('description') is-invalid @enderror"
                    id="description" name="description" rows="4"
                    placeholder="The two lights above the back row do not switch on."
                    required>{{ old('description') }}</textarea>
          <div class="form-text">The system reads this text to decide which team handles the repair.</div>
          @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        {{-- Pelajar boleh PILIH dari senarai atau TAIP sendiri. --}}
        {{-- Atribut list="..." menyambungkan kotak ini dengan <datalist> di bawah. --}}
        {{-- Tiada JavaScript diperlukan; browser yang uruskan senarai tu. --}}
        <div class="mb-3">
          <label class="form-label" for="location">Where is it?</label>
          <input type="text" class="form-control @error('location') is-invalid @enderror"
                 id="location" name="location" value="{{ old('location') }}"
                 list="location-options" autocomplete="off"
                 placeholder="Level 2, Computer Lab 1"
                 maxlength="150" required>

          <datalist id="location-options">
            @foreach ($locations as $location)
              <option value="{{ $location->fullName() }}"></option>
            @endforeach
          </datalist>

          <div class="form-text">
            Choose from the list, or type your own if the venue is not listed.
            Tip: type "Level 3" to see every room on that floor.
          </div>
          @error('location')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="mb-4">
          <label class="form-label" for="photo">Photo (optional)</label>
          <input type="file" class="form-control @error('photo') is-invalid @enderror"
                 id="photo" name="photo" accept="image/jpeg,image/png" capture="environment">
          <div class="form-text">JPG or PNG, up to 2MB.</div>
          @error('photo')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="d-flex gap-2 flex-wrap">
          <button type="submit" class="btn btn-brand">Send complaint</button>
          <a href="{{ route('complaints.index') }}" class="btn btn-link">Cancel</a>
        </div>
      </form>
    </div>

  </div>
</div>
@endsection
