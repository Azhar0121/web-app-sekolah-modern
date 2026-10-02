@extends('layouts.admin')

@section('title', 'Pusat Konfigurasi SEO')

@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<link rel="stylesheet" href="{{ asset('css/admin/seo/index.css') }}">

<div class="seo-page">


{{-- HEADER --}}
<div class="seo-header">
    <div class="seo-header-content">

        <div class="seo-header-icon">
            <i class="bi bi-search"></i>
        </div>

        <div>
            <span class="seo-header-label">KONFIGURASI WEBSITE</span>

            <h1>Pusat Konfigurasi SEO</h1>

            <p>
                Kelola Meta Title, Description, Open Graph, Robots.txt,
                Sitemap, dan pengalihan URL website.
            </p>
        </div>

    </div>
</div>


{{-- MAIN CONTENT --}}
<div class="seo-layout">

    {{-- LEFT COLUMN --}}
    <div class="seo-main-column">

        {{-- GLOBAL SEO --}}
        <div class="seo-section-card">

            <div class="seo-section-header">
                <div class="seo-section-header-icon">
                    <i class="bi bi-globe2"></i>
                </div>

                <div>
                    <h2>Global Meta Tags & Open Graph</h2>
                    <p>
                        Pengaturan informasi SEO utama dan tampilan
                        ketika halaman dibagikan ke media sosial.
                    </p>
                </div>
            </div>

            <div class="seo-section-body">

                <form action="{{ route('admin.seo.update') }}"
                      method="POST"
                      enctype="multipart/form-data">

                    @csrf

                    {{-- META TITLE --}}
                    <div class="seo-form-group">

                        <label for="meta_title">
                            Default Meta Title
                        </label>

                        <input
                            type="text"
                            name="meta_title"
                            id="meta_title"
                            class="seo-input"
                            value="{{ old('meta_title', $seo->meta_title ?? config('app.name')) }}"
                            placeholder="Sekolah Modern — Portal Edukasi & Layanan Digital"
                        >

                    </div>


                    {{-- META DESCRIPTION --}}
                    <div class="seo-form-group">

                        <label for="meta_description">
                            Default Meta Description
                        </label>

                        <textarea
                            name="meta_description"
                            id="meta_description"
                            class="seo-textarea"
                            rows="4"
                            placeholder="Website resmi Sekolah Modern menyediakan informasi akademik, PPDB online, profil pengajar..."
                        >{{ old('meta_description', $seo->meta_description ?? '') }}</textarea>

                    </div>


                    {{-- META KEYWORDS --}}
                    <div class="seo-form-group">

                        <label for="meta_keywords">
                            Meta Keywords
                        </label>

                        <input
                            type="text"
                            name="meta_keywords"
                            id="meta_keywords"
                            class="seo-input"
                            value="{{ old('meta_keywords', $seo->meta_keywords ?? '') }}"
                            placeholder="sekolah modern, smk, sma, ppdb online, pendidikan"
                        >

                        <span class="seo-help-text">
                            Pisahkan setiap keyword menggunakan koma.
                        </span>

                    </div>


                    {{-- OG TITLE + CANONICAL --}}
                    <div class="seo-form-grid">

                        <div class="seo-form-group">

                            <label for="og_title">
                                Open Graph Title
                            </label>

                            <input
                                type="text"
                                name="og_title"
                                id="og_title"
                                class="seo-input"
                                value="{{ old('og_title', $seo->og_title ?? '') }}"
                                placeholder="Judul ketika dibagikan ke Facebook / WhatsApp"
                            >

                        </div>


                        <div class="seo-form-group">

                            <label for="canonical_url">
                                Canonical URL
                            </label>

                            <input
                                type="text"
                                name="canonical_url"
                                id="canonical_url"
                                class="seo-input"
                                value="{{ old('canonical_url', $seo->canonical_url ?? config('app.url')) }}"
                                placeholder="https://domainsekolah.sch.id"
                            >

                        </div>

                    </div>


                    {{-- OG IMAGE --}}
                    <div class="seo-form-group">

                        <label for="og_image">
                            Open Graph Image
                        </label>

                        <input
                            type="file"
                            name="og_image"
                            id="og_image"
                            class="seo-file-input"
                        >

                        <span class="seo-help-text">
                            Gambar yang digunakan ketika halaman dibagikan ke media sosial.
                        </span>

                    </div>


                    {{-- ROBOTS TXT --}}
                    <div class="seo-form-group">

                        <label for="robots_txt">
                            Isi File Robots.txt
                        </label>

                        <textarea
                            name="robots_txt"
                            id="robots_txt"
                            class="seo-textarea seo-code-textarea"
                            rows="8"
                            placeholder="User-agent: *


Allow: /
Disallow: /admin/
Disallow: /guru/
Disallow: /siswa/"
>{{ old('robots_txt', $seo->robots_txt ?? '') }}</textarea>


                        <span class="seo-help-text">
                            Masukkan aturan crawler yang ingin digunakan oleh website.
                        </span>

                    </div>


                    {{-- SUBMIT --}}
                    <div class="seo-form-footer">

                        <button type="submit" class="seo-primary-button">
                            <i class="bi bi-check2-circle"></i>
                            <span>Simpan Konfigurasi SEO</span>
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>


    {{-- RIGHT COLUMN --}}
    <div class="seo-side-column">

        {{-- REDIRECT --}}
        <div class="seo-section-card">

            <div class="seo-section-header">

                <div class="seo-section-header-icon">
                    <i class="bi bi-diagram-3"></i>
                </div>

                <div>
                    <h2>Manajemen Pengalihan Link</h2>
                    <p>
                        Kelola pengalihan URL lama ke URL tujuan.
                    </p>
                </div>

            </div>


            <div class="seo-section-body">

                <form
                    action="{{ route('admin.seo.redirects.store') }}"
                    method="POST"
                    class="seo-redirect-form"
                >

                    @csrf

                    <div class="seo-form-group">

                        <label for="source_url">
                            URL Asal
                        </label>

                        <input
                            type="text"
                            name="source_url"
                            id="source_url"
                            class="seo-input"
                            placeholder="/pendaftaran-lama"
                            required
                        >

                    </div>


                    <div class="seo-form-group">

                        <label for="target_url">
                            URL Tujuan
                        </label>

                        <input
                            type="text"
                            name="target_url"
                            id="target_url"
                            class="seo-input"
                            placeholder="/ppdb"
                            required
                        >

                    </div>


                    <div class="seo-redirect-action">

                        <select
                            name="status_code"
                            class="seo-select"
                        >
                            <option value="301">
                                301 (Permanen)
                            </option>

                            <option value="302">
                                302 (Sementara)
                            </option>
                        </select>

                        <button
                            type="submit"
                            class="seo-primary-button seo-add-button"
                        >
                            <i class="bi bi-plus-lg"></i>
                            <span>Tambah Redirect</span>
                        </button>

                    </div>

                </form>


                {{-- REDIRECT TABLE --}}
                <div class="seo-table-wrapper">

                    <table class="seo-table">

                        <thead>
                            <tr>
                                <th>Asal</th>
                                <th>Tujuan</th>
                                <th class="seo-center">Tipe</th>
                                <th class="seo-right">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse ($redirects as $red)

                                <tr>

                                    <td>
                                        <span class="seo-url">
                                            {{ $red->source_url }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="seo-url seo-url-target">
                                            {{ $red->target_url }}
                                        </span>
                                    </td>

                                    <td class="seo-center">

                                        <span class="seo-status-badge">
                                            {{ $red->status_code }}
                                        </span>

                                    </td>

                                    <td class="seo-right">

                                        <form
                                            action="{{ route('admin.seo.redirects.destroy', $red) }}"
                                            method="POST"
                                            class="seo-delete-form"
                                        >

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="seo-delete-button"
                                                title="Hapus Redirect"
                                            >
                                                <i class="bi bi-trash"></i>
                                            </button>

                                        </form>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td
                                        colspan="4"
                                        class="seo-empty-table"
                                    >
                                        Belum ada pengalihan URL.
                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>


        {{-- SITEMAP --}}
        <div class="seo-sitemap-card">

            <div class="seo-sitemap-icon">
                <i class="bi bi-file-earmark-code"></i>
            </div>

            <div class="seo-sitemap-content">

                <span class="seo-sitemap-label">
                    SITEMAP WEBSITE
                </span>

                <h2>Sitemap.xml Otomatis</h2>

                <p>
                    Sitemap XML dibuat otomatis oleh sistem untuk
                    membantu mesin pencari menemukan halaman website.
                </p>

                <a
                    href="{{ url('/sitemap.xml') }}"
                    target="_blank"
                    class="seo-sitemap-button"
                >
                    <i class="bi bi-box-arrow-up-right"></i>
                    <span>Buka Sitemap.xml</span>
                </a>

            </div>

        </div>

    </div>

</div>


</div>

@endsection
