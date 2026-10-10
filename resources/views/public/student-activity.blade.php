@extends('layouts.public')

@section('title', 'Kesiswaan — ' . ($settings['school_name'] ?? config('app.name')))

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="{{ asset('css/public/student.css') }}">

<div class="student-page">

    {{-- HERO SECTION --}}
    @include('public.student-activity.partials.hero')

    <main class="container student-content">

        {{-- OSIS & MPK --}}
        @include('public.student-activity.partials.osis-section')

        {{-- EKSTRAKURIKULER --}}
        @include('public.student-activity.partials.extracurricular-section')

        {{-- PRESTASI SISWA --}}
        @include('public.student-activity.partials.achievement-section')

        {{-- BEASISWA --}}
        @include('public.student-activity.partials.scholarship-section')

    </main>
</div>

{{-- MODAL GALERI KEGIATAN OSIS --}}
@include('public.student-activity.partials.modals-gallery')

{{-- FILTER EKSTRAKURIKULER --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const filterButtons = document.querySelectorAll('.filter-ekskul-btn');
    const ekskulCards = document.querySelectorAll('.ekskul-card-item');

    filterButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            filterButtons.forEach(function (item) {
                item.classList.remove('active', 'btn-dark');
                item.classList.add('btn-outline-secondary');
            });

            this.classList.add('active', 'btn-dark');
            this.classList.remove('btn-outline-secondary');

            const targetCategory = this.dataset.category;

            ekskulCards.forEach(function (card) {
                const cardCategory = card.dataset.category;
                const isVisible =
                    targetCategory === 'all' ||
                    cardCategory === targetCategory;

                card.style.display = isVisible ? '' : 'none';
            });
        });
    });
});
</script>
@endsection

