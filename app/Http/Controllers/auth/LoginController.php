<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function create(): \Illuminate\View\View
    {
        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withErrors(['email' => 'Email atau password salah.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        $user = Auth::user();

        if (! $user->is_active) {
            AuditLog::log('logout', "Percobaan login ditolak: akun nonaktif ({$user->email})", $user);
            Auth::logout();
            return back()->withErrors(['email' => 'Akun Anda tidak aktif. Hubungi Super Admin.']);
        }

        AuditLog::log('login', "Pengguna {$user->name} ({$user->role?->name}) berhasil login ke sistem", $user);

        return redirect()->intended($this->redirectPathFor($user->role?->slug));
    }

    public function destroy(Request $request): RedirectResponse
    {
        $user = Auth::user();

        if ($user) {
            AuditLog::log('logout', "Pengguna {$user->name} ({$user->role?->name}) telah logout dari sistem", $user);
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    private function redirectPathFor(?string $roleSlug): string
    {
        return match ($roleSlug) {
            'super-admin' => '/admin/dashboard',
            'guru' => '/guru/dashboard',
            'siswa' => '/siswa/dashboard',
            'ortu' => '/ortu/dashboard',
            'tu' => '/tu/dashboard',
            'kepsek' => '/kepsek/dashboard',
            default => '/login',
        };
    }
}