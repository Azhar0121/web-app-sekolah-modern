@extends('layouts.admin')

@section('title', 'Pusat Konfigurasi SEO')

@section('content')
<div class="container-fluid py-3">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="fw-bold text-dark mb-0">
                <i class="bi bi-search-heart text-primary me-2"></i>Pusat Konfigurasi SEO & Pengalihan Link
            </h5>
            <small class="text-muted">Pengaturan Meta Title, Description, Open Graph (Share Sosmed), Robots.txt, Sitemap, dan Redirects Manager.</small>
        </div>
    </div>

    <div class="row g-4">

        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-globe me-2 text-primary"></i>Global Meta Tags & Open Graph (Social Share)</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.seo.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Default Meta Title</label>
                            <input type="text" name="meta_title" class="form-control" value="{{ old('meta_title', $seo->meta_title ?? config('app.name')) }}" placeholder="Sekolah Modern — Portal Edukasi & Layanan Digital">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Default Meta Description</label>
                            <textarea name="meta_description" class="form-control" rows="3" placeholder="Website resmi Sekolah Modern menyediakan informasi akademik, PPDB online, profil pengajar...">{{ old('meta_description', $seo->meta_description ?? '') }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Meta Keywords (Pisahkan koma)</label>
                            <input type="text" name="meta_keywords" class="form-control" value="{{ old('meta_keywords', $seo->meta_keywords ?? '') }}" placeholder="sekolah modern, smk, sma, ppdb online, pendidikan">
                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Open Graph Title (Facebook/WA)</label>
                                <input type="text" name="og_title" class="form-control" value="{{ old('og_title', $seo->og_title ?? '') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Canonical URL</label>
                                <input type="text" name="canonical_url" class="form-control" value="{{ old('canonical_url', $seo->canonical_url ?? config('app.url')) }}">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Open Graph Image (Gambar Share Media Sosial)</label>
                            <input type="file" name="og_image" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Isi File Robots.txt</label>
                            <textarea name="robots_txt" class="form-control font-monospace small" rows="4">{{ old('robots_txt', $seo->robots_txt ?? "User-agent: *\nAllow: /\nDisallow: /admin/\nDisallow: /guru/\nDisallow: /siswa/\n\nSitemap: " . url('/sitemap.xml')) }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="bi bi-check-circle me-1"></i>Simpan Konfigurasi SEO
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-diagram-3 me-2 text-primary"></i>Manajemen Pengalihan Link (URL Redirects)</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.seo.redirects.store') }}" method="POST" class="mb-3">
                        @csrf
                        <div class="mb-2">
                            <input type="text" name="source_url" class="form-control form-control-sm" placeholder="URL Asal (Contoh: /pendaftaran-lama)" required>
                        </div>
                        <div class="mb-2">
                            <input type="text" name="target_url" class="form-control form-control-sm" placeholder="URL Tujuan (Contoh: /ppdb)" required>
                        </div>
                        <div class="d-flex gap-2">
                            <select name="status_code" class="form-select form-select-sm" style="width:140px;">
                                <option value="301">301 (Permanen)</option>
                                <option value="302">302 (Sementara)</option>
                            </select>
                            <button type="submit" class="btn btn-primary btn-sm flex-grow-1">+ Tambah Redirect</button>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle table-sm mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Asal</th>
                                    <th>Tujuan</th>
                                    <th class="text-center">Tipe</th>
                                    <th class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($redirects as $red)
                                <tr>
                                    <td class="small font-monospace">{{ $red->source_url }}</td>
                                    <td class="small font-monospace text-primary">{{ $red->target_url }}</td>
                                    <td class="text-center"><span class="badge bg-secondary-subtle text-secondary">{{ $red->status_code }}</span></td>
                                    <td class="text-end">
                                        <form action="{{ route('admin.seo.redirects.destroy', $red) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-link text-danger p-0"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="4" class="text-center py-3 text-muted small">Belum ada pengalihan URL.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body">
                    <h6 class="fw-bold text-dark mb-2"><i class="bi bi-file-earmark-code me-2 text-success"></i>Sitemap.xml Otomatis</h6>
                    <p class="small text-muted mb-2">Sitemap XML dibuat otomatis oleh sistem untuk mendaftarkan halaman web ke Google Search Console.</p>
                    <a href="{{ url('/sitemap.xml') }}" target="_blank" class="btn btn-outline-success btn-sm"><i class="bi bi-box-arrow-up-right me-1"></i>Buka Sitemap.xml</a>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
