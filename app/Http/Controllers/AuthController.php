<?php

namespace App\Http\Controllers;

use App\Helpers\JwtHelper;
use Flasher\Laravel\Facade\Flasher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            Flasher::error('Lengkapi email dan kata sandi terlebih dahulu.');

            return back()
                ->withErrors($validator)
                ->withInput($request->only('email'));
        }

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        // Check if user exists by email
        $user = \App\Models\User::where('email', $credentials['email'])->first();

        if (!$user) {
            Flasher::error('Email tidak ditemukan.');

            return back()
                ->withErrors(['email' => 'Email tidak ditemukan.'])
                ->withInput($request->only('email'));
        }

        // Attempt authentication
        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $request->session()->put(
                'jwt_token',
                JwtHelper::tokenForUser(Auth::user(), config('app.key'))
            );

            Flasher::success('Selamat datang, ' . Auth::user()->name . '!');

            return redirect()->intended(route('dashboard'));
        }

        // If user exists but password is wrong
        Flasher::error('Password salah.');

        return back()
            ->withErrors(['password' => 'Password salah.'])
            ->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        $request->session()->forget('jwt_token');

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Flasher::info('Berhasil logout. Sampai jumpa lagi!');

        return redirect()->route('login');
    }
}
