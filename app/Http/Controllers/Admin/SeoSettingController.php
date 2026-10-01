<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\SeoSetting;
use App\Models\UrlRedirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SeoSettingController extends Controller
{
    public function index(): View
    {
        $seo = SeoSetting::first() ?? new SeoSetting();
        $redirects = UrlRedirect::orderByDesc('created_at')->paginate(15);

        return view('admin.seo.index', compact('seo', 'redirects'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'meta_title'       => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:1000'],
            'meta_keywords'    => ['nullable', 'string', 'max:500'],
            'og_title'         => ['nullable', 'string', 'max:255'],
            'og_description'   => ['nullable', 'string', 'max:1000'],
            'canonical_url'    => ['nullable', 'string', 'max:255'],
            'robots_txt'       => ['nullable', 'string', 'max:5000'],
            'og_image'         => ['nullable', 'image', 'max:2048'],
        ]);

        $seo = SeoSetting::firstOrNew();
        $seo->fill($validated);

        if ($request->hasFile('og_image')) {
            $file = $request->file('og_image');
            $seo->og_image_path = $file->store('seo', 'public');
        }

        $seo->save();

        AuditLog::log('update', 'Memperbarui Konfigurasi SEO & Social Share');

        return back()->with('success', 'Konfigurasi SEO berhasil diperbarui.');
    }

    public function storeRedirect(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'source_url'  => ['required', 'string', 'max:255', 'unique:url_redirects,source_url'],
            'target_url'  => ['required', 'string', 'max:255'],
            'status_code' => ['required', 'in:301,302'],
        ]);

        UrlRedirect::create($validated);

        AuditLog::log('create', "Menambahkan pengalihan link (Redirect): {$validated['source_url']} -> {$validated['target_url']}");

        return back()->with('success', 'Pengalihan URL berhasil ditambahkan.');
    }

    public function destroyRedirect(UrlRedirect $redirect): RedirectResponse
    {
        $source = $redirect->source_url;
        $redirect->delete();

        AuditLog::log('delete', "Menghapus pengalihan link: {$source}");

        return back()->with('success', 'Pengalihan URL berhasil dihapus.');
    }
}
