@extends('layouts.app')

@section('title', 'Student Portal | Inspire Tech')

@section('content')
<div class="d-flex align-items-center justify-content-center vh-100">
    <div class="card p-4 shadow" style="min-width: 360px;">
        <h2 class="mb-3">Student Portal</h2>
        <p class="text-muted">This is a placeholder login page. For a full conversion, replace this with your existing authentication logic.</p>
        <a href="{{ route('student.dashboard') }}" class="btn btn-primary w-100">Go to Dashboard (demo)</a>
    </div>
</div>
@endsection
