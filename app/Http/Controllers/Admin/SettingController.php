<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function index(): View
    {
        $settings = Setting::all()->pluck('value', 'key')->all();

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'school_name'         => ['required', 'string', 'max:255'],
            'school_abbreviation' => ['nullable', 'string', 'max:50'],
            'address'             => ['nullable', 'string', 'max:1000'],
            'phone'               => ['nullable', 'string', 'max:50'],
            'email'               => ['nullable', 'email', 'max:255'],
            'primary_color'       => ['nullable', 'string', 'max:20'],
            'secondary_color'     => ['nullable', 'string', 'max:20'],
            'google_maps_api_key' => ['nullable', 'string', 'max:255'],
            'google_maps_embed'   => ['nullable', 'string'],
            'smtp_host'           => ['nullable', 'string', 'max:255'],
            'smtp_port'           => ['nullable', 'string', 'max:10'],
            'smtp_username'       => ['nullable', 'string', 'max:255'],
            'smtp_password'       => ['nullable', 'string', 'max:255'],
            'smtp_encryption'     => ['nullable', 'string', 'max:10'],
            'logo'                => ['nullable', 'image', 'max:2048'],
            'favicon'             => ['nullable', 'file', 'mimes:ico,png,jpg', 'max:1024'],
        ]);

        foreach (['school_name', 'school_abbreviation', 'address', 'phone', 'email', 'primary_color', 'secondary_color', 'google_maps_api_key', 'google_maps_embed', 'smtp_host', 'smtp_port', 'smtp_username', 'smtp_password', 'smtp_encryption'] as $field) {
            if (array_key_exists($field, $validated)) {
                Setting::set($field, $validated[$field]);
            }
        }

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $path = $file->store('settings', 'public');
            Setting::set('logo_path', $path, 'image', 'identity', 'Logo Sekolah');
        }

        if ($request->hasFile('favicon')) {
            $file = $request->file('favicon');
            $path = $file->store('settings', 'public');
            Setting::set('favicon_path', $path, 'image', 'identity', 'Favicon');
        }

        AuditLog::log('update', 'Memperbarui Pengaturan Global Sistem');

        return back()->with('success', 'Pengaturan global sistem berhasil diperbarui.');
    }
}
