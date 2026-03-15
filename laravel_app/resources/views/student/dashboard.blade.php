@extends('layouts.app')

@section('title', 'Student Dashboard | Inspire Tech')

@section('content')
    @php
        $courseLabel = ucfirst(str_replace('-', ' ', $myCourse));
    @endphp

    {{-- Navbar placeholder (you can move this into a separate partial) --}}
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Inspire Tech</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">My Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Certificate</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="sidebar">
        <div class="sidebar-profile">
            <img src="{{ $profilePic }}" class="sidebar-img" alt="Student">
            <h6 class="mb-0">{{ $name }}</h6>
            <small class="text-info">{{ $courseLabel }}</small>
            @if($assignedTeacherName)
                <div class="mt-2 small text-secondary">
                    <i class="fas fa-chalkboard-teacher me-1"></i>
                    Assigned Teacher: <strong>{{ $assignedTeacherName }}</strong>
                </div>
            @else
                <div class="mt-2 small text-secondary">
                    <i class="fas fa-user-clock me-1"></i>
                    <strong>No teacher assigned</strong>
                </div>
            @endif

            @if($studentProfile)
                <div class="mt-3 small text-secondary">
                    <div><i class="fas fa-envelope me-1"></i> {{ $studentProfile->email }}</div>
                    <div><i class="fas fa-phone me-1"></i> {{ $studentProfile->mobile }}</div>
                    @if(!empty($studentProfile->district))
                        <div><i class="fas fa-map-marker-alt me-1"></i> {{ $studentProfile->district }}</div>
                    @endif
                </div>
            @endif
        </div>

        <a href="#" class="nav-link-custom active"><i class="fas fa-home me-2"></i> Dashboard</a>
        <a href="#" class="nav-link-custom"><i class="fas fa-user-graduate me-2"></i> My Profile</a>
        <a href="#" class="nav-link-custom"><i class="fas fa-certificate me-2"></i> Certificates</a>
        <hr class="text-secondary">
        <form method="POST" action="{{ route('student.logout') }}" class="d-inline">
            @csrf
            <button type="submit" class="nav-link-custom text-danger border-0 bg-transparent w-100 text-start">
                <i class="fas fa-sign-out-alt me-2"></i> Logout
            </button>
        </form>
    </div>

    <div class="main-content">
        <div class="container-fluid">
            <div class="row mb-4 animate__animated animate__fadeInDown">
                <div class="col-md-8">
                    <h1 class="fw-bold">Welcome back, <span class="text-info">{{ $name }}</span>!</h1>
                    <p class="text-secondary">#1 Tech Academy in Nowshera - Learning & Innovation.</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="badge bg-dark p-3 border border-secondary">
                        <i class="fas fa-calendar-alt text-info me-2"></i> {{ now()->format('jS M Y') }}
                    </div>
                </div>
            </div>

            <div class="row g-4 mb-5">
                <div class="col-xl-7 animate__animated animate__fadeInLeft">
                    {{-- Add your dashboard cards & stats here --}}
                </div>

                <div class="col-xl-5 animate__animated animate__fadeInRight">
                    {{-- Course / certificate card etc. --}}
                </div>
            </div>
        </div>
    </div>
@endsection
