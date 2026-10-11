{{-- FAIL: resources/views/auth/login.blade.php --}}

@extends('layouts.guest')

@section('title', 'Sign in')

@section('helper')
  New here? <a href="{{ route('register') }}">Create an account</a>
@endsection

@section('form')
<div class="panel">
  <h2>Sign in</h2>
  <p class="sub">Use your campus account to continue.</p>

  {{-- Mesej selepas berjaya daftar atau log keluar --}}
  @if (session('status'))
    <div class="alert alert-success py-2" style="font-size:.9rem">
      {{ session('status') }}
    </div>
  @endif

  <form method="POST" action="{{ route('login.store') }}">
    @csrf

    <div class="mb-3">
      <label class="form-label" for="email">Email</label>
      <input type="email"
             class="form-control @error('email') is-invalid @enderror"
             id="email" name="email"
             value="{{ old('email') }}"
             placeholder="name@student.uptm.edu.my"
             autocomplete="email" required autofocus>
      @error('email')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>

    <div class="mb-3">
      <label class="form-label" for="password">Password</label>
      <input type="password"
             class="form-control @error('password') is-invalid @enderror"
             id="password" name="password"
             autocomplete="current-password" required>
      @error('password')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>

    <div class="row-between">
      <div class="form-check">
        {{-- value="1" supaya $request->boolean('remember') berfungsi --}}
        <input class="form-check-input" type="checkbox"
               id="remember" name="remember" value="1">
        <label class="form-check-label" for="remember" style="font-size:.86rem">
          Keep me signed in
        </label>
      </div>
    </div>

    <button type="submit" class="btn btn-brand">Sign in</button>
  </form>

  <div class="swap">
    Don't have an account? <a href="{{ route('register') }}">Create one</a>
  </div>
</div>
@endsection
