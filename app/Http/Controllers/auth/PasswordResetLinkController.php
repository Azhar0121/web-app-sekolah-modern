<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;
use function Illuminate\Support\defer;

class PasswordResetLinkController extends Controller
{
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ], [
            'email.required' => 'Alamat email wajib diisi.',
            'email.email'    => 'Format email tidak valid.',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            // Jika queue default adalah database, jalankan queue worker secara aman
            // pada termination phase (defer) agar email terkirim tanpa menahan request utama
            if (config('queue.default') === 'database') {
                defer(function () {
                    try {
                        Artisan::call('queue:work', [
                            '--stop-when-empty' => true,
                            '--max-jobs'        => 1,
                            '--tries'           => 1,
                        ]);
                    } catch (\Throwable $e) {
                        report($e);
                    }
                });
            }

            $successMessage = 'Tautan reset password telah dikirim ke email Anda! Silakan periksa kotak masuk atau spam.';

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $successMessage,
                ]);
            }

            return back()->with('status', $successMessage);
        }

        $errorMessage = match ($status) {
            Password::RESET_THROTTLED => 'Mohon tunggu beberapa saat sebelum meminta tautan reset password baru.',
            default                   => 'Email tidak terdaftar dalam sistem kami.',
        };

        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => $errorMessage,
                'errors'  => ['email' => [$errorMessage]],
            ], 422);
        }

        return back()->withInput($request->only('email'))
            ->withErrors(['email' => $errorMessage]);
    }
}