<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class AuthenticatedSessionController extends Controller
{
    /**
     * Menampilkan halaman login.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Menangani request autentikasi.
     */
    public function store(Request $request): RedirectResponse|JsonResponse
{
    // Validasi input login
    $credentials = $request->validate([
        'name'     => ['required', 'string'],
        'password' => ['required', 'string', 'min:8'],
    ], [
        'password.min' => 'Password harus memiliki minimal 8 karakter.', // Pesan kustom
    ]);


    

        // Attempt login
        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('auth.failed'),
                ], 422);
            }
            throw ValidationException::withMessages([
                'name' => __('auth.failed'),
            ]);
        }

        // Regenerasi session
        $request->session()->regenerate();

        // Redirect berdasarkan usertype
        if ($request->user()->usertype == 'admin') {
            $redirectUrl = '/admin/dashboard';
            $request->session()->flash('status', 'Login berhasil!');
            if ($request->expectsJson()) {
                return response()->json([
                    'success'  => true,
                    'redirect' => $redirectUrl,
                ]);
            }
            return redirect($redirectUrl);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }
        return redirect()->intended(route('login', false));
    }

    /**
     * Logout user (menghancurkan session).
     */
    public function destroy(Request $request): RedirectResponse|JsonResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->expectsJson()) {
            return response()->json([
                'success'  => true,
                'redirect' => '/login',
            ]);
        }

        return redirect('/login');
    }
}
