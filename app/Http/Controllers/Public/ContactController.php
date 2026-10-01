<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\MediaFile;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function index(): View
    {
        $settings = Setting::all()->pluck('value', 'key')->all();

        $galleryMedia = MediaFile::orderByDesc('created_at')->take(12)->get();

        return view('public.contact', compact('settings', 'galleryMedia'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'    => ['required', 'string', 'max:255'],
            'email'   => ['required', 'email', 'max:255'],
            'phone'   => ['nullable', 'string', 'max:50'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        ContactMessage::create($validated);

        return back()->with('success', 'Pesan Anda telah berhasil terkirim. Tim staf sekolah akan segera menghubungi Anda.');
    }
}
