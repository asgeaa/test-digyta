@extends('layouts.app')
@section('title', 'Login')
@section('content')
    <div class="container d-flex justify-content-center align-items-center min-vh-100 bg-light">
        <div class="col-md-5 p-4 rounded shadow-sm bg-white">
            @if (Session::has('pesan'))
                <div class="alert {{ Session::get('alert-class') }} alert-dismissible fade show" role="alert">
                    {{ Session::get('pesan') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <h3 class="text-center text-primary mb-3">Login</h3>
            <p class="text-center text-muted">Silahkan masukkan username dan password Anda</p>
            <form action="{{ route('auth.store') }}" method="POST" class="mt-4">
                @csrf
                <div class="mb-4">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" value="{{ old('username') }}"
                        placeholder="Masukkan username" required />
                    @error('username')
                        <p class="text-danger mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password"
                        placeholder="Masukkan password" required />
                    @error('password')
                        <p class="text-danger mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="d-grid mb-3">
                    <button type="submit" class="btn btn-primary btn-lg">Login</button>
                </div>
            </form>
        </div>
    </div>
@endsection
