@extends('layouts.app')

@section('title', 'Page Not Found')

@section('styles')
    <style>
        .error-container {
            text-align: center;
            padding: 100px 0;
        }

        .error-code {
            font-size: 120px;
            color: #e74c3c;
            margin-bottom: 0;
        }

        .error-message {
            font-size: 24px;
            margin-bottom: 30px;
        }
    </style>
@endsection

@section('content')
    <div class="error-container">
        <h1 class="error-code">404</h1>
        <p class="error-message">Page Not Found</p>
        <p>The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.</p>
        <a href="{{ route('home') }}" class="btn btn-primary">Back to Home</a>
    </div>
@endsection
