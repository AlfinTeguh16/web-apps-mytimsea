<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;
use App\Jobs\SendOtpJob;
use Illuminate\Support\Facades\Log;


class AuthController extends Controller
{
    /**
     * Menampilkan halaman login.
     */
    public function showLoginForm()
    {
        return view('pages.auth.login');
    }



    /**
     * Proses login.
     */
    public function login(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Cari user berdasarkan email
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'email' => 'Email atau password salah.',
            ]);
        }

        // Login user
        Auth::login($user);

        // Redirect berdasarkan role
        if ($user->role === 'admin') {
            return redirect()->route('index.admin');
        } elseif ($user->role === 'writer') {
            return redirect()->route('index.users');
        }

        // Default redirect jika role tidak dikenal
        return redirect()->route('home');
    }

    /**
     * Proses logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }


    public function showRegistrationForm()
    {
        return view('pages.auth.register');
    }

    /**
     * Proses registrasi pengguna baru.
     */
    public function register(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:50|unique:tb_users,username',
            'email' => 'required|email|unique:tb_users,email',
            'password' => 'required|min:8|confirmed',
            'role' => 'nullable|in:writer,talent,company',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Generate OTP
        $otp = rand(1000, 9999);

        // Simpan user ke database
        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role ?? 'talent',
            'OTP' => $otp,
        ]);

        // Kirim OTP melalui job queue
        SendOtpJob::dispatch($user->email, $otp);

        // Simpan email di session untuk halaman OTP
        session(['email' => $user->email]);

        // Redirect ke halaman input OTP
        return redirect()->route('input-otp')->with('success', 'Registration successful. OTP has been sent to your email.');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:tb_users,email',
            'otp' => 'required|integer',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user && $user->OTP == $request->otp) {
            // Reset OTP setelah verifikasi berhasil
            $user->OTP = null;
            $user->save();

            return redirect()->route('home')->with('success', 'OTP verified successfully.');
        }

        return back()->with('error', 'Invalid OTP. Please try again.');
    }


    public function sendOTP(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:tb_users,email',
        ]);
    
        $otp = rand(1000, 9999);
    
        try {
            $user = User::where('email', $request->email)->first();
            $user->OTP = $otp;
            $user->save();
    
            $mailData = [
                'title' => 'Your OTP Code',
                'body' => $otp,
            ];
    
            SendOtpJob::dispatch($user->email, $mailData);
    
            return response()->json(['message' => 'OTP has been sent to your email.']);
        } catch (\Throwable $th) {
            \Log::error($th->getMessage());
            return response()->json(['message' => 'Failed to send OTP'], 500);
        }
    }
    
    


}
