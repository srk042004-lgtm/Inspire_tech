@extends('layouts.app')

@section('title', 'Student Login | Inspire Tech')

@section('content')
<div class="d-flex align-items-center justify-content-center vh-100">
    <div class="card p-4 shadow" style="min-width: 360px;">
        <h2 class="mb-3 text-center">Student Portal</h2>
        <p class="text-muted text-center mb-4">#1 Tech Academy in Nowshera</p>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('student.login.post') }}">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>

        <div class="text-center mt-3">
            <small class="text-muted">Don't have an account? <a href="#">Register here</a></small>
        </div>
    </div>
</div>
@endsection
