@extends('layouts.app')

@section('title', 'Admin Login | Inspire Tech')

@section('content')
<div class="d-flex align-items-center justify-content-center vh-100">
    <div class="card shadow" style="min-width: 360px;">
        <div class="card-body">
            <h2 class="card-title mb-3">Admin Login</h2>
            <p class="text-muted">This page is a Blade conversion placeholder. Re-implement your login form and authentication logic here.</p>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-primary w-100">Go to Admin Dashboard (demo)</a>
        </div>
    </div>
</div>
@endsection
