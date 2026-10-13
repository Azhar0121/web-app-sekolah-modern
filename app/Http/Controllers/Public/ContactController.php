<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\Setting;
use App\Models\User;
use App\Notifications\ContactMessageReceivedNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function index(): View
    {
        $settings = Setting::all()->pluck('value', 'key')->all();

        return view('public.contact', compact('settings'));
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

        $contactMessage = ContactMessage::create($validated);

        $recipients = User::whereHas('role', function ($query) {
            $query->whereIn('slug', ['super-admin', 'tu']);
        })->get();

        if ($recipients->isNotEmpty()) {
            Notification::send($recipients, new ContactMessageReceivedNotification($contactMessage));
        }

        return back()->with('success', 'Pesan Anda telah berhasil terkirim. Tim staf sekolah akan segera menghubungi Anda.');
    }
}