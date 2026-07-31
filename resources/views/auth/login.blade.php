@extends('layouts.auth')

@section('content')
<style>
    body {
        background-color: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
        margin: 0;
    }
    .login-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        overflow: hidden;
        width: 100%;
        max-width: 450px;
        margin: auto;
    }
    .login-header {
        background-color: #054c86; /* Dark blue from the image */
        color: white;
        text-align: center;
        padding: 30px 20px;
    }
    .login-header h2 {
        font-weight: 700;
        margin-bottom: 5px;
    }
    .login-header p {
        font-size: 14px;
        margin-bottom: 0;
        opacity: 0.9;
    }
    .login-body {
        padding: 30px 40px;
        background-color: white;
    }
    .form-label {
        font-weight: 600;
        font-size: 14px;
        color: #333;
    }
    .input-group-text {
        background-color: #f8f9fa;
        border-right: none;
        color: #888;
    }
    .form-control {
        border-left: none;
    }
    .form-control:focus {
        box-shadow: none;
        border-color: #ced4da;
    }
    .input-group:focus-within .input-group-text,
    .input-group:focus-within .form-control {
        border-color: #054c86;
    }
    .btn-login {
        background-color: #054c86;
        color: white;
        font-weight: 600;
        padding: 10px;
        border-radius: 6px;
    }
    .btn-login:hover {
        background-color: #043964;
        color: white;
    }
    .back-link {
        display: block;
        text-align: center;
        margin-top: 20px;
        color: #777;
        text-decoration: none;
        font-size: 14px;
    }
    .back-link:hover {
        color: #333;
    }
</style>

<div class="login-card">
    <div class="login-header">
        <h2>Jai Maa Durga</h2>
        <p>Sign in to your dashboard</p>
    </div>
    <div class="login-body">
        @if($errors->any())
            <div class="alert alert-danger p-2 mb-3">
                <ul class="mb-0 ps-3" style="font-size: 14px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                    <input type="email" name="email" id="email" class="form-control" placeholder="example@domain.com" value="{{ old('email') }}" required autofocus>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required>
                </div>
            </div>
            
            <div class="mb-4 form-check">
                <input type="checkbox" name="remember" id="remember" class="form-check-input">
                <label class="form-check-label text-muted" for="remember" style="font-size: 14px;">Remember me</label>
            </div>
            
            <div class="d-grid">
                <button type="submit" class="btn btn-login">Login to Dashboard</button>
            </div>
            
            <a href="/" class="back-link">
                <i class="bi bi-arrow-left"></i> Back to Website
            </a>
        </form>
    </div>
</div>
@endsection
