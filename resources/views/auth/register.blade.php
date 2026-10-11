{{-- FAIL: resources/views/auth/register.blade.php --}}

@extends('layouts.guest')

@section('title', 'Create account')

@section('helper')
  Already registered? <a href="{{ route('login') }}">Sign in</a>
@endsection

@section('form')
<div class="panel">
  <h2>Create account</h2>
  <p class="sub">Students can register here. Staff accounts are created by the admin.</p>

  <form method="POST" action="{{ route('register.store') }}">
    @csrf

    <div class="mb-3">
      <label class="form-label" for="name">Full name</label>
      <input type="text"
             class="form-control @error('name') is-invalid @enderror"
             id="name" name="name" value="{{ old('name') }}"
             autocomplete="name" required autofocus>
      @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>

    <div class="mb-3">
      <label class="form-label" for="email">Email</label>
      <input type="email"
             class="form-control @error('email') is-invalid @enderror"
             id="email" name="email" value="{{ old('email') }}"
             placeholder="name@student.uptm.edu.my"
             autocomplete="email" required>
      @error('email')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>

    <div class="mb-3">
      <label class="form-label" for="password">Password</label>
      <input type="password"
             class="form-control @error('password') is-invalid @enderror"
             id="password" name="password"
             autocomplete="new-password" required>
      <div class="form-text">At least 8 characters.</div>
      @error('password')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>

    <div class="mb-4">
      <label class="form-label" for="password_confirmation">Confirm password</label>
      {{-- Nama field MESTI password_confirmation. --}}
      {{-- Peraturan 'confirmed' dalam controller mencari nama ini. --}}
      <input type="password" class="form-control"
             id="password_confirmation" name="password_confirmation"
             autocomplete="new-password" required>
    </div>

    <button type="submit" class="btn btn-brand">Create account</button>
  </form>

  <div class="swap">
    Already have an account? <a href="{{ route('login') }}">Sign in</a>
  </div>
</div>
@endsection
