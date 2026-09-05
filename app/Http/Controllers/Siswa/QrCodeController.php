<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class QrCodeController extends Controller
{
    /** Berapa detik 1 QR berlaku sebelum otomatis berganti. */
    private const TTL_SECONDS = 25;

    public function show(): View
    {
        $student = auth()->user();
        $token = $student->rotateQrToken(self::TTL_SECONDS);
        $classroom = $student->currentClassroom();

        return view('siswa.qr-code.show', [
            'student' => $student,
            'token' => $token,
            'classroom' => $classroom,
            'ttl' => self::TTL_SECONDS,
        ]);
    }

    public function refresh(): JsonResponse
    {
        $token = auth()->user()->rotateQrToken(self::TTL_SECONDS);

        return response()->json([
            'token' => $token,
            'ttl' => self::TTL_SECONDS,
        ]);
    }
}