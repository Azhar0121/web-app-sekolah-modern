@extends('layouts.admin')

@section('title', 'Pengaturan Global Sistem')

@section('content')

<link rel="stylesheet" href="{{ asset('css/admin/settings/index.css') }}">

<div class="settings-page">

    {{-- HERO --}}
    <div class="settings-hero">
        <div class="settings-hero-content">
            <div class="settings-hero-text">
                <span class="settings-hero-label">KONFIGURASI SISTEM</span>

                <h1>Pengaturan Global Sistem</h1>

                <p>
                    Kustomisasi identitas aplikasi, warna tema, kredensial SMTP email,
                    dan API Google Maps.
                </p>
            </div>

            <div class="settings-hero-icon">
                <i class="bi bi-gear-wide-connected"></i>
            </div>
        </div>
    </div>

    {{-- FORM --}}
    <form method="POST"
          action="{{ route('admin.settings.update') }}"
          enctype="multipart/form-data">

        @csrf

        <div class="settings-grid">

            {{-- IDENTITAS SEKOLAH --}}
            <div class="settings-card">

                <div class="settings-card-header">
                    <div class="settings-card-icon">
                        <i class="bi bi-building"></i>
                    </div>

                    <div>
                        <h2>Identitas Sekolah & Aplikasi</h2>
                        <p>Atur informasi utama yang digunakan oleh sistem.</p>
                    </div>
                </div>

                <div class="settings-card-body">

                    <div class="settings-form-group">
                        <label for="school_name">
                            Nama Resmi Sekolah
                        </label>

                        <input
                            type="text"
                            name="school_name"
                            id="school_name"
                            class="settings-input"
                            value="{{ old('school_name', $settings['school_name'] ?? config('app.name')) }}"
                            required
                        >
                    </div>

                    <div class="settings-form-group">
                        <label for="school_abbreviation">
                            Singkatan Sekolah
                        </label>

                        <input
                            type="text"
                            name="school_abbreviation"
                            id="school_abbreviation"
                            class="settings-input"
                            value="{{ old('school_abbreviation', $settings['school_abbreviation'] ?? 'SMK / SMA') }}"
                        >
                    </div>

                    <div class="settings-form-group">
                        <label for="address">
                            Alamat Lengkap
                        </label>

                        <textarea
                            name="address"
                            id="address"
                            class="settings-textarea"
                            rows="2"
                        >{{ old('address', $settings['address'] ?? '') }}</textarea>
                    </div>

                    <div class="settings-form-grid">

                        <div class="settings-form-group">
                            <label for="phone">
                                Nomor Telepon / WhatsApp
                            </label>

                            <input
                                type="text"
                                name="phone"
                                id="phone"
                                class="settings-input"
                                value="{{ old('phone', $settings['phone'] ?? '') }}"
                            >
                        </div>

                        <div class="settings-form-group">
                            <label for="email">
                                Email Resmi
                            </label>

                            <input
                                type="email"
                                name="email"
                                id="email"
                                class="settings-input"
                                value="{{ old('email', $settings['email'] ?? '') }}"
                            >
                        </div>

                    </div>

                    <div class="settings-form-grid">

                        <div class="settings-form-group">
                            <label for="logo">
                                Logo Sekolah (PNG/JPG)
                            </label>

                            <input
                                type="file"
                                name="logo"
                                id="logo"
                                class="settings-file-input"
                            >
                        </div>

                        <div class="settings-form-group">
                            <label for="favicon">
                                Favicon Web (.ico / .png)
                            </label>

                            <input
                                type="file"
                                name="favicon"
                                id="favicon"
                                class="settings-file-input"
                            >
                        </div>

                    </div>

                </div>
            </div>


            {{-- TEMA & GOOGLE MAPS --}}
            <div class="settings-card">

                <div class="settings-card-header">
                    <div class="settings-card-icon">
                        <i class="bi bi-palette"></i>
                    </div>

                    <div>
                        <h2>Tema Warna & Peta</h2>
                        <p>Atur warna aplikasi dan konfigurasi Google Maps.</p>
                    </div>
                </div>

                <div class="settings-card-body">

                    <div class="settings-form-grid">

                        <div class="settings-form-group">
                            <label for="primary_color">
                                Warna Utama (Primary)
                            </label>

                            <input
                                type="color"
                                name="primary_color"
                                id="primary_color"
                                class="settings-color-input"
                                value="{{ old('primary_color', $settings['primary_color'] ?? '#0d6efd') }}"
                            >
                        </div>

                        <div class="settings-form-group">
                            <label for="secondary_color">
                                Warna Sekunder (Secondary)
                            </label>

                            <input
                                type="color"
                                name="secondary_color"
                                id="secondary_color"
                                class="settings-color-input"
                                value="{{ old('secondary_color', $settings['secondary_color'] ?? '#6c757d') }}"
                            >
                        </div>

                    </div>

                    <div class="settings-form-group">
                        <label for="google_maps_api_key">
                            Google Maps API Key
                        </label>

                        <input
                            type="text"
                            name="google_maps_api_key"
                            id="google_maps_api_key"
                            class="settings-input"
                            value="{{ old('google_maps_api_key', $settings['google_maps_api_key'] ?? '') }}"
                            placeholder="AIzaSy..."
                        >
                    </div>

                    <div class="settings-form-group settings-form-group-last">
                        <label for="google_maps_embed">
                            Google Maps Embed URL / Iframe Code
                        </label>

                        <textarea
                            name="google_maps_embed"
                            id="google_maps_embed"
                            class="settings-textarea"
                            rows="2"
                            placeholder="<iframe src=... ></iframe>"
                        >{{ old('google_maps_embed', $settings['google_maps_embed'] ?? '') }}</textarea>
                    </div>

                </div>
            </div>


            {{-- SMTP --}}
            <div class="settings-card settings-card-smtp">

                <div class="settings-card-header">
                    <div class="settings-card-icon">
                        <i class="bi bi-envelope-at"></i>
                    </div>

                    <div>
                        <h2>Konfigurasi Email (SMTP Standar)</h2>
                        <p>Atur server dan kredensial pengiriman email aplikasi.</p>
                    </div>
                </div>

                <div class="settings-card-body">

                    <div class="settings-form-grid settings-smtp-host-grid">

                        <div class="settings-form-group">
                            <label for="smtp_host">
                                SMTP Host
                            </label>

                            <input
                                type="text"
                                name="smtp_host"
                                id="smtp_host"
                                class="settings-input"
                                value="{{ old('smtp_host', $settings['smtp_host'] ?? '') }}"
                                placeholder="smtp.mailtrap.io"
                            >
                        </div>

                        <div class="settings-form-group">
                            <label for="smtp_port">
                                Port
                            </label>

                            <input
                                type="text"
                                name="smtp_port"
                                id="smtp_port"
                                class="settings-input"
                                value="{{ old('smtp_port', $settings['smtp_port'] ?? '587') }}"
                            >
                        </div>

                    </div>

                    <div class="settings-form-grid">

                        <div class="settings-form-group">
                            <label for="smtp_username">
                                SMTP Username
                            </label>

                            <input
                                type="text"
                                name="smtp_username"
                                id="smtp_username"
                                class="settings-input"
                                value="{{ old('smtp_username', $settings['smtp_username'] ?? '') }}"
                            >
                        </div>

                        <div class="settings-form-group">
                            <label for="smtp_password">
                                SMTP Password
                            </label>

                            <input
                                type="password"
                                name="smtp_password"
                                id="smtp_password"
                                class="settings-input"
                                value="{{ old('smtp_password', $settings['smtp_password'] ?? '') }}"
                            >
                        </div>

                    </div>

                    <div class="settings-form-group settings-form-group-last">
                        <label for="smtp_encryption">
                            Encryption
                        </label>

                        <select
                            name="smtp_encryption"
                            id="smtp_encryption"
                            class="settings-select"
                        >
                            <option
                                value="tls"
                                @selected(($settings['smtp_encryption'] ?? '') === 'tls')
                            >
                                TLS
                            </option>

                            <option
                                value="ssl"
                                @selected(($settings['smtp_encryption'] ?? '') === 'ssl')
                            >
                                SSL
                            </option>

                            <option
                                value="null"
                                @selected(($settings['smtp_encryption'] ?? '') === 'null')
                            >
                                None
                            </option>
                        </select>
                    </div>

                </div>
            </div>

        </div>

        {{-- SAVE --}}
        <div class="settings-footer">
            <button type="submit" class="settings-save-button">
                <i class="bi bi-check-circle"></i>
                <span>Simpan Perubahan Pengaturan</span>
            </button>
        </div>

    </form>

</div>

@endsection