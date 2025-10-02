@extends('layouts.blankLayout')

@section('title', 'Login - Todolisap')

@section('page-style')
<style>
    body {
        background: linear-gradient(135deg, #6f42c1, #9d6fe7);
        font-family: 'Poppins', sans-serif;
    }
    .auth-wrapper {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .auth-card {
        background: #fff;
        border-radius: 16px;
        padding: 40px;
        width: 100%;
        max-width: 450px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }
    .auth-logo {
        font-weight: 700;
        font-size: 26px;
        color: #6f42c1;
        text-align: center;
        margin-bottom: 25px;
    }
    .form-control {
        border-radius: 12px;
        padding: 12px;
    }
    .btn-gradient {
        background: linear-gradient(135deg, #6f42c1, #9d6fe7);
        border: none;
        border-radius: 12px;
        padding: 12px;
        font-size: 16px;
        font-weight: 600;
        color: #fff;
        transition: 0.3s;
    }
    .btn-gradient:hover {
        opacity: 0.9;
    }
    .text-muted a {
        color: #6f42c1;
        font-weight: 600;
        text-decoration: none;
    }
</style>
@endsection

@section('content')
<div class="auth-wrapper">
    <div class="auth-card">
        <!-- Logo -->
        <div class="auth-logo">Todolisap</div>
        <h4 class="mb-3 text-center">Selamat Datang Kembali 👋</h4>
        <p class="text-center text-muted mb-4">Login untuk mengelola daftar tugasmu</p>

        @if(session('success'))
        <div class="alert alert-success mb-3" role="alert">
            <strong>Success:</strong> {{ session('success') }}
        </div>
        @endif

        @error('error')
        <div class="alert alert-danger mb-3" role="alert">
            <strong>Failed:</strong> {{ $message }}
        </div>
        @enderror

        <form id="loginForm" action="{{ route('login') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="text" id="email" name="email" 
                       class="form-control @error('email') is-invalid @enderror"
                       placeholder="Enter your email" 
                       value="{{ old('email') }}" required autofocus>
                @error('email') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" name="password" 
                       class="form-control @error('password') is-invalid @enderror"
                       placeholder="••••••••" required>
                @error('password') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="remember-me" name="remember">
                    <label class="form-check-label" for="remember-me">Remember Me</label>
                </div>
                <a href="{{url('auth/forgot-password-basic')}}" class="text-muted">Forgot Password?</a>
            </div>

            <button type="submit" class="btn-gradient w-100">Login</button>
        </form>

        {{-- 🔑 Small Square Google Login Button --}}
        <div class="d-flex flex-column align-items-center pt-3 mb-3">
            <p class="mb-2">or</p>
            <a href="" class="btn btn-light border rounded d-flex align-items-center justify-content-center" 
               style="width:40px; height:40px;">
                <i class="bx bxl-google text-danger fs-4"></i>
            </a>
        </div>

        <p class="text-center text-muted mt-4">
            Belum punya akun?
            <a href="{{ route('register') }}">Register disini</a>
        </p>
    </div>
</div>
@endsection
