@extends('layouts.admin')

@section('title', 'Pengaturan Global Sistem')

@section('content')
<div class="container-fluid py-3">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="fw-bold text-dark mb-0">
                <i class="bi bi-gear-wide-connected text-primary me-2"></i>Pengaturan Global Sistem
            </h5>
            <small class="text-muted">Kustomisasi identitas aplikasi, warna tema, kredensial SMTP email, dan API Google Maps (*no hardcoding*).</small>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
        @csrf

        <div class="row g-4">

            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-white py-3">
                        <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-building me-2 text-primary"></i>Identitas Sekolah & Aplikasi</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Nama Resmi Sekolah</label>
                            <input type="text" name="school_name" class="form-control" value="{{ old('school_name', $settings['school_name'] ?? config('app.name')) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Singkatan Sekolah</label>
                            <input type="text" name="school_abbreviation" class="form-control" value="{{ old('school_abbreviation', $settings['school_abbreviation'] ?? 'SMK / SMA') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Alamat Lengkap</label>
                            <textarea name="address" class="form-control" rows="2">{{ old('address', $settings['address'] ?? '') }}</textarea>
                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Nomor Telepon / WhatsApp</label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone', $settings['phone'] ?? '') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Email Resmi</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email', $settings['email'] ?? '') }}">
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Logo Sekolah (PNG/JPG)</label>
                                <input type="file" name="logo" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Favicon Web (.ico / .png)</label>
                                <input type="file" name="favicon" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-header bg-white py-3">
                        <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-palette me-2 text-primary"></i>Tema Warna & Peta (Google Maps)</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label small fw-semibold">Warna Utama (Primary)</label>
                                <input type="color" name="primary_color" class="form-control form-control-color w-100" value="{{ old('primary_color', $settings['primary_color'] ?? '#0d6efd') }}">
                            </div>
                            <div class="col-6">
                                <label class="form-label small fw-semibold">Warna Sekunder (Secondary)</label>
                                <input type="color" name="secondary_color" class="form-control form-control-color w-100" value="{{ old('secondary_color', $settings['secondary_color'] ?? '#6c757d') }}">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Google Maps API Key</label>
                            <input type="text" name="google_maps_api_key" class="form-control" value="{{ old('google_maps_api_key', $settings['google_maps_api_key'] ?? '') }}" placeholder="AIzaSy...">
                        </div>
                        <div class="mb-0">
                            <label class="form-label small fw-semibold">Google Maps Embed URL / Iframe Code</label>
                            <textarea name="google_maps_embed" class="form-control" rows="2" placeholder="<iframe src=... ></iframe>">{{ old('google_maps_embed', $settings['google_maps_embed'] ?? '') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-white py-3">
                        <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-envelope-at me-2 text-primary"></i>Konfigurasi Email (SMTP Standar)</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-2 mb-2">
                            <div class="col-md-8">
                                <label class="form-label small fw-semibold">SMTP Host</label>
                                <input type="text" name="smtp_host" class="form-control" value="{{ old('smtp_host', $settings['smtp_host'] ?? '') }}" placeholder="smtp.mailtrap.io">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold">Port</label>
                                <input type="text" name="smtp_port" class="form-control" value="{{ old('smtp_port', $settings['smtp_port'] ?? '587') }}">
                            </div>
                        </div>
                        <div class="row g-2 mb-2">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">SMTP Username</label>
                                <input type="text" name="smtp_username" class="form-control" value="{{ old('smtp_username', $settings['smtp_username'] ?? '') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">SMTP Password</label>
                                <input type="password" name="smtp_password" class="form-control" value="{{ old('smtp_password', $settings['smtp_password'] ?? '') }}">
                            </div>
                        </div>
                        <div class="mb-0">
                            <label class="form-label small fw-semibold">Encryption</label>
                            <select name="smtp_encryption" class="form-select">
                                <option value="tls" @selected(($settings['smtp_encryption'] ?? '') === 'tls')>TLS</option>
                                <option value="ssl" @selected(($settings['smtp_encryption'] ?? '') === 'ssl')>SSL</option>
                                <option value="null" @selected(($settings['smtp_encryption'] ?? '') === 'null')>None</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 text-end">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-check-circle me-1"></i>Simpan Perubahan Pengaturan
                </button>
            </div>

        </div>
    </form>

</div>
@endsection
