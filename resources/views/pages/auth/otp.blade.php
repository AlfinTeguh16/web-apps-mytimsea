@extends('master')

@section('content')

    <h1>Verify Your OTP</h1>

    @if (session('error'))
        <p style="color: red;">{{ session('error') }}</p>
    @endif

    <form action="{{ route('verify-otp') }}" method="POST">
        @csrf
        <label for="otp">Enter OTP sent to your email:</label>
        <input type="text" name="otp" required>
        <input type="hidden" name="email" value="{{ session('email') }}">
        <button type="submit">Verify OTP</button>
    </form>


@endsection