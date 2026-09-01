<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class QrCodeController extends Controller
{
    public function show(): View
    {
        $student = auth()->user();
        $token = $student->ensureQrToken();
        $classroom = $student->currentClassroom();

        return view('siswa.qr-code.show', compact('student', 'token', 'classroom'));
    }
}
