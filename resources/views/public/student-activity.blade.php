@extends('layouts.public')

@section('title', 'Kesiswaan — ' . ($settings['school_name'] ?? config('app.name')))

@section('content')

<link rel="stylesheet" href="{{ asset('css/public/student.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<div class="student-page">

    {{-- HERO SECTION --}}
    @include('public.student-activity.partials.hero')

    <main class="container student-content">
        {{-- 1. OSIS & MPK SECTION --}}
        @include('public.student-activity.partials.osis-section')

        {{-- 2. EKSTRAKURIKULER SECTION --}}
        @include('public.student-activity.partials.extracurricular-section')

        {{-- 3. PRESTASI SISWA SECTION --}}
        @include('public.student-activity.partials.achievement-section')

        {{-- 4. BEASISWA SECTION --}}
        @include('public.student-activity.partials.scholarship-section')
    </main>

</div>

{{-- MODALS GALERI FOTO KEGIATAN OSIS --}}
@include('public.student-activity.partials.modals-gallery')

{{-- CLIENT SCRIPT: FILTER KATEGORI EKSTRAKURIKULER --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const filterButtons = document.querySelectorAll('.filter-ekskul-btn');
    const ekskulCards = document.querySelectorAll('.ekskul-card-item');

    filterButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            filterButtons.forEach(b => {
                b.classList.remove('active', 'btn-dark');
                b.classList.add('btn-outline-secondary');
            });

            this.classList.add('active', 'btn-dark');
            this.classList.remove('btn-outline-secondary');

            const targetCategory = this.dataset.category;

            ekskulCards.forEach(card => {
                const cardCat = card.dataset.category;
                if (targetCategory === 'all' || cardCat === targetCategory) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
});
</script>

@endsection