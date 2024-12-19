@extends('master')

@section('content')
<h1>Register</h1>

    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('register') }}" method="POST">
        @csrf
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username" value="{{ old('username') }}" required><br><br>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" value="{{ old('email') }}" required><br><br>

        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br><br>

        <label for="password_confirmation">Confirm Password:</label><br>
        <input type="password" id="password_confirmation" name="password_confirmation" required><br><br>

        <label for="role">Role:</label><br>
        <select id="role" name="role">
            <option value="writer" {{ old('role') == 'writer' ? 'selected' : '' }}>Writer</option>
            <option value="talent" {{ old('role') == 'talent' ? 'selected' : '' }}>Talent</option>
            <option value="company" {{ old('role') == 'company' ? 'selected' : '' }}>Company</option>
        </select><br><br>

        <button type="submit">Register</button>
    </form>
@endsection