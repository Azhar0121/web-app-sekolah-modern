@extends('layouts.public')

@section('title', config('app.name'))

@section('content')

{{-- =========================================================
   EXTERNAL FONT & ICON
   ========================================================= --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<link
    href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
    rel="stylesheet"
>

<link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
>


{{-- =========================================================
   HERO / BANNER UTAMA
   ========================================================= --}}
<section class="hero-section">

    <div class="hero-slider">

        {{-- SLIDE 1 --}}
        <article class="hero-slide active">

            <img
                src="https://file.data.kemendikdasmen.go.id/sekolahkita/69/6996/69969076-3.jpg"
                alt="Gedung {{ config('app.name') }}"
            >

            <div class="hero-overlay"></div>

            <div class="hero-container">

                <div class="hero-content">

                    <span class="hero-kicker">
                        SISTEM INFORMASI SEKOLAH
                    </span>

                    <h1>
                        Selamat Datang di
                        <strong>{{ config('app.name') }}</strong>
                    </h1>

                    <p>
                        Portal resmi sekolah yang menyediakan informasi,
                        layanan pendidikan, kegiatan siswa, dan akses
                        layanan sekolah secara digital.
                    </p>

                    <div class="hero-actions">

                        <a
                            href="{{ url('/login') }}"
                            class="hero-primary-button"
                        >
                            <i class="bi bi-box-arrow-in-right"></i>
                            Masuk Portal
                        </a>

                        <a
                            href="{{ route('ppdb.index') }}"
                            class="hero-secondary-button"
                        >
                            <i class="bi bi-person-plus"></i>
                            PPDB Online
                        </a>

                    </div>

                </div>

            </div>

        </article>


        {{-- SLIDE 2 --}}
        <article class="hero-slide">

            <img
                src="https://images.unsplash.com/photo-1562774053-701939374585?auto=format&fit=crop&w=1800&q=90"
                alt="Lingkungan sekolah"
            >

            <div class="hero-overlay"></div>

            <div class="hero-container">

                <div class="hero-content">

                    <span class="hero-kicker">
                        PENDIDIKAN BERKUALITAS
                    </span>

                    <h1>
                        Membangun Generasi
                        <strong>Unggul dan Berkarakter</strong>
                    </h1>

                    <p>
                        Pendidikan yang mengembangkan pengetahuan,
                        karakter, kreativitas, dan kemampuan peserta
                        didik untuk menghadapi masa depan.
                    </p>

                    <a
                        href="#tentang"
                        class="hero-primary-button"
                    >
                        Kenali Sekolah
                        <i class="bi bi-arrow-right"></i>
                    </a>

                </div>

            </div>

        </article>


        {{-- SLIDE 3 --}}
        <article class="hero-slide">

            <img
                src="https://images.unsplash.com/photo-1509062522246-3755977927d7?auto=format&fit=crop&w=1800&q=90"
                alt="Kegiatan pembelajaran"
            >

            <div class="hero-overlay"></div>

            <div class="hero-container">

                <div class="hero-content">

                    <span class="hero-kicker">
                        SEKOLAH DIGITAL
                    </span>

                    <h1>
                        Pendidikan Modern
                        <strong>Untuk Masa Depan</strong>
                    </h1>

                    <p>
                        Memanfaatkan teknologi untuk memberikan
                        pengalaman belajar dan layanan sekolah
                        yang lebih efektif dan terintegrasi.
                    </p>

                    <a
                        href="#layanan"
                        class="hero-primary-button"
                    >
                        Lihat Layanan
                        <i class="bi bi-arrow-right"></i>
                    </a>

                </div>

            </div>

        </article>


        {{-- SLIDER CONTROL --}}
        <div class="hero-controls">

            <button
                type="button"
                class="hero-prev"
                aria-label="Slide sebelumnya"
            >
                <i class="bi bi-chevron-left"></i>
            </button>

            <div class="hero-dots">

                <button
                    type="button"
                    class="hero-dot active"
                    data-slide="0"
                ></button>

                <button
                    type="button"
                    class="hero-dot"
                    data-slide="1"
                ></button>

                <button
                    type="button"
                    class="hero-dot"
                    data-slide="2"
                ></button>

            </div>

            <button
                type="button"
                class="hero-next"
                aria-label="Slide berikutnya"
            >
                <i class="bi bi-chevron-right"></i>
            </button>

        </div>

    </div>

</section>



{{-- =========================================================
   QUICK SERVICE
   ========================================================= --}}
<section class="service-section" id="layanan">

    <div class="container">

        <div class="section-heading">

            <div>
                <span class="section-kicker">
                    LAYANAN SEKOLAH
                </span>

                <h2>
                    Akses layanan
                    <strong>secara mudah.</strong>
                </h2>
            </div>

            <p>
                Berbagai layanan utama sekolah dapat diakses
                melalui sistem informasi secara lebih cepat,
                praktis, dan terintegrasi.
            </p>

        </div>


        <div class="service-grid">

            <a
                href="{{ url('/login') }}"
                class="service-card"
            >

                <div class="service-icon">
                    <i class="bi bi-grid-1x2-fill"></i>
                </div>

                <div class="service-content">

                    <span>
                        LAYANAN 01
                    </span>

                    <h3>
                        Portal Sekolah
                    </h3>

                    <p>
                        Akses sistem informasi sekolah
                        untuk pengguna internal.
                    </p>

                </div>

                <i class="bi bi-arrow-up-right service-arrow"></i>

            </a>


            <a
                href="{{ route('ppdb.index') }}"
                class="service-card service-card-highlight"
            >

                <div class="service-icon">
                    <i class="bi bi-person-plus-fill"></i>
                </div>

                <div class="service-content">

                    <span>
                        LAYANAN 02
                    </span>

                    <h3>
                        PPDB Online
                    </h3>

                    <p>
                        Informasi dan pendaftaran peserta
                        didik baru secara online.
                    </p>

                </div>

                <i class="bi bi-arrow-up-right service-arrow"></i>

            </a>


            <a
                href="#kegiatan"
                class="service-card"
            >

                <div class="service-icon">
                    <i class="bi bi-calendar-event-fill"></i>
                </div>

                <div class="service-content">

                    <span>
                        LAYANAN 03
                    </span>

                    <h3>
                        Kegiatan Sekolah
                    </h3>

                    <p>
                        Informasi kegiatan dan aktivitas
                        peserta didik.
                    </p>

                </div>

                <i class="bi bi-arrow-up-right service-arrow"></i>

            </a>


            <a
                href="#kontak"
                class="service-card service-card-highlight"
            >

                <div class="service-icon">
                    <i class="bi bi-headset"></i>
                </div>

                <div class="service-content">

                    <span>
                        LAYANAN 04
                    </span>

                    <h3>
                        Hubungi Sekolah
                    </h3>

                    <p>
                        Dapatkan informasi dan bantuan
                        dari pihak sekolah.
                    </p>

                </div>

                <i class="bi bi-arrow-up-right service-arrow"></i>

            </a>

        </div>

    </div>

</section>



{{-- =========================================================
   TENTANG SEKOLAH
   ========================================================= --}}
<section class="about-section" id="tentang">

    <div class="container">

        <div class="about-grid">

            <div class="about-image">

                <img
                    src="https://images.unsplash.com/photo-1562774053-701939374585?auto=format&fit=crop&w=1200&q=90"
                    alt="Lingkungan {{ config('app.name') }}"
                    loading="lazy"
                >


            </div>


            <div class="about-content">

                <span class="section-kicker">
                    TENTANG SEKOLAH
                </span>

                <h2>
                    Pendidikan untuk
                    <strong>masa depan.</strong>
                </h2>

                <div class="section-line"></div>

                <p>
                    {{ config('app.name') }} merupakan lingkungan
                    pendidikan yang memberikan ruang bagi peserta
                    didik untuk belajar, mengembangkan potensi,
                    membangun karakter, dan mempersiapkan diri
                    menghadapi masa depan.
                </p>

                <p>
                    Pembelajaran tidak hanya berfokus pada
                    pencapaian akademik, tetapi juga pada
                    pengembangan kreativitas, kemampuan bekerja
                    sama, komunikasi, tanggung jawab, serta
                    kemampuan beradaptasi dengan perkembangan zaman.
                </p>


                <div class="about-stats">

                    <div>
                        <strong>01</strong>
                        <span>Karakter</span>
                    </div>

                    <div>
                        <strong>02</strong>
                        <span>Kompetensi</span>
                    </div>

                    <div>
                        <strong>03</strong>
                        <span>Kreativitas</span>
                    </div>

                    <div>
                        <strong>04</strong>
                        <span>Prestasi</span>
                    </div>

                </div>

            </div>

        </div>

    </div>

</section>



{{-- =========================================================
   INFORMASI SEKOLAH
   ========================================================= --}}
<section class="information-section" id="kegiatan">

    <div class="container">

        <div class="section-heading">

            <div>

                <span class="section-kicker">
                    INFORMASI SEKOLAH
                </span>

                <h2>
                    Informasi dan
                    <strong>kegiatan terbaru.</strong>
                </h2>

            </div>

            <p>
                Informasi seputar kegiatan, perkembangan,
                dan aktivitas {{ config('app.name') }}.
            </p>

        </div>


        <div class="information-grid">

            <article class="information-card information-card-large">

                <div class="information-image">
                    <img
                        src="https://images.unsplash.com/photo-1509062522246-3755977927d7?auto=format&fit=crop&w=1200&q=80"
                        alt="Suasana kegiatan pembelajaran di sekolah"
                    >
                    <span>KEGIATAN</span>
                </div>

                <div class="information-content">

                    <small>
                        INFORMASI SEKOLAH
                    </small>

                    <h3>
                        Membangun pengalaman belajar
                        melalui berbagai kegiatan siswa.
                    </h3>

                    <p>
                        Sekolah menjadi ruang bagi siswa untuk
                        berinteraksi, bekerja sama, mengeksplorasi
                        minat, dan mengembangkan potensi.
                    </p>

                    <a href="#ekstrakurikuler">
                        Lihat Kegiatan
                        <i class="bi bi-arrow-right"></i>
                    </a>

                </div>

            </article>


            <div class="information-side">

                <article class="mini-information-card">

                    <span class="mini-number">
                        01
                    </span>

                    <div>

                        <small>
                            PENDIDIKAN
                        </small>

                        <h3>
                            Pembelajaran
                            Berkarakter
                        </h3>

                        <p>
                            Mengembangkan kemampuan akademik
                            sekaligus karakter peserta didik.
                        </p>

                    </div>

                    <i class="bi bi-arrow-up-right"></i>

                </article>


                <article class="mini-information-card">

                    <span class="mini-number">
                        02
                    </span>

                    <div>

                        <small>
                            TEKNOLOGI
                        </small>

                        <h3>
                            Sekolah
                            Digital
                        </h3>

                        <p>
                            Memanfaatkan teknologi dalam
                            mendukung proses pendidikan.
                        </p>

                    </div>

                    <i class="bi bi-arrow-up-right"></i>

                </article>


                <article class="mini-information-card">

                    <span class="mini-number">
                        03
                    </span>

                    <div>

                        <small>
                            SISWA
                        </small>

                        <h3>
                            Pengembangan
                            Potensi
                        </h3>

                        <p>
                            Memberikan ruang untuk bakat,
                            kreativitas, dan prestasi.
                        </p>

                    </div>

                    <i class="bi bi-arrow-up-right"></i>

                </article>

            </div>

        </div>

    </div>

</section>



{{-- =========================================================
   VISI & MISI
   ========================================================= --}}
<section class="vision-section">

    <div class="container">

        <div class="vision-layout">

            <div class="vision-intro">

                <span class="section-kicker">
                    PROFIL SEKOLAH
                </span>

                <h2>
                    Visi dan
                    <strong>misi pendidikan.</strong>
                </h2>

                <p>
                    Setiap proses pendidikan membutuhkan arah
                    yang jelas. Visi menjadi tujuan yang ingin
                    dicapai, sedangkan misi menjadi langkah
                    nyata untuk mewujudkannya.
                </p>

                <img
                    src="https://images.unsplash.com/photo-1503676260728-1c00da094a0b?auto=format&fit=crop&w=1000&q=90"
                    alt="Pembelajaran sekolah"
                    loading="lazy"
                >

            </div>


            <div class="vision-content">

                {{-- VISI --}}
                <div class="vision-main">

                    <div class="vision-header">

                        <span>01</span>

                        <div>
                            <small>
                                ARAH PENDIDIKAN
                            </small>

                            <strong>
                                VISI SEKOLAH
                            </strong>
                        </div>

                    </div>

                    <h3>
                        Menjadi lingkungan pendidikan yang unggul,
                        berkarakter, adaptif, kreatif, dan mampu
                        mempersiapkan generasi menghadapi masa depan.
                    </h3>

                    <p>
                        Visi ini menjadi landasan dalam menciptakan
                        proses pembelajaran yang tidak hanya
                        berorientasi pada pencapaian akademik,
                        tetapi juga perkembangan karakter,
                        kreativitas, keterampilan, dan potensi
                        setiap peserta didik.
                    </p>

                </div>


                {{-- MISI --}}
                <div class="mission-section">

                    <div class="mission-heading">

                        <span>
                            02
                        </span>

                        <div>
                            <small>
                                LANGKAH NYATA
                            </small>

                            <strong>
                                MISI SEKOLAH
                            </strong>
                        </div>

                    </div>


                    <div class="mission-list">

                        <div class="mission-item">

                            <span>01</span>

                            <div>

                                <h4>
                                    Mengembangkan Potensi
                                </h4>

                                <p>
                                    Memberikan ruang bagi peserta didik
                                    untuk mengenali, mengembangkan, dan
                                    mengoptimalkan kemampuan yang dimiliki.
                                </p>

                            </div>

                        </div>


                        <div class="mission-item">

                            <span>02</span>

                            <div>

                                <h4>
                                    Membentuk Karakter
                                </h4>

                                <p>
                                    Menumbuhkan sikap disiplin,
                                    tanggung jawab, percaya diri,
                                    peduli, dan memiliki integritas.
                                </p>

                            </div>

                        </div>


                        <div class="mission-item">

                            <span>03</span>

                            <div>

                                <h4>
                                    Mendorong Kreativitas
                                </h4>

                                <p>
                                    Menciptakan pembelajaran yang
                                    mendorong rasa ingin tahu,
                                    keberanian mencoba, dan inovasi.
                                </p>

                            </div>

                        </div>


                        <div class="mission-item">

                            <span>04</span>

                            <div>

                                <h4>
                                    Beradaptasi dengan Teknologi
                                </h4>

                                <p>
                                    Memanfaatkan teknologi sebagai
                                    bagian dari proses belajar dan
                                    perkembangan pendidikan modern.
                                </p>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>



{{-- =========================================================
   EKSTRAKURIKULER
   ========================================================= --}}
<section
    class="extracurricular-section"
    id="ekstrakurikuler"
>

    <div class="container">

        <div class="section-heading">

            <div>

                <span class="section-kicker">
                    KEGIATAN SISWA
                </span>

                <h2>
                    Ekstrakurikuler
                    <strong>sekolah.</strong>
                </h2>

            </div>

            <p>
                Ruang bagi siswa untuk mengembangkan bakat,
                minat, kreativitas, karakter, dan kemampuan
                bekerja sama.
            </p>

        </div>


        <div class="extra-slider">

            <div class="extra-track">

                {{-- FUTSAL --}}
                <article class="extra-card">

                    <div class="extra-image">

                        <img
                            src="https://images.unsplash.com/photo-1553778263-73a83bab9b0c?auto=format&fit=crop&w=900&q=90"
                            alt="Ekstrakurikuler futsal"
                            loading="lazy"
                        >

                        <span>
                            01
                        </span>

                    </div>

                    <div class="extra-content">

                        <small>
                            SPORT
                        </small>

                        <h3>
                            Futsal
                        </h3>

                        <p>
                            Melatih kerja sama tim, disiplin,
                            strategi, dan sportivitas.
                        </p>

                    </div>

                </article>


                {{-- PRAMUKA --}}
                <article class="extra-card">

                    <div class="extra-image">

                        <img
                            src="https://images.unsplash.com/photo-1529390079861-591de354faf5?auto=format&fit=crop&w=900&q=90"
                            alt="Ekstrakurikuler pramuka"
                            loading="lazy"
                        >

                        <span>
                            02
                        </span>

                    </div>

                    <div class="extra-content">

                        <small>
                            CHARACTER
                        </small>

                        <h3>
                            Pramuka
                        </h3>

                        <p>
                            Membangun kemandirian, kepemimpinan,
                            kedisiplinan, dan kepedulian.
                        </p>

                    </div>

                </article>


                {{-- PMR --}}
                <article class="extra-card">

                    <div class="extra-image">

                        <img
                            src="https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?auto=format&fit=crop&w=900&q=90"
                            alt="Kegiatan PMR"
                            loading="lazy"
                        >

                        <span>
                            03
                        </span>

                    </div>

                    <div class="extra-content">

                        <small>
                            SOCIAL
                        </small>

                        <h3>
                            PMR
                        </h3>

                        <p>
                            Mengenal kepedulian sosial, kesehatan,
                            dan semangat membantu sesama.
                        </p>

                    </div>

                </article>


                {{-- SENI --}}
                <article class="extra-card">

                    <div class="extra-image">

                        <img
                            src="https://images.unsplash.com/photo-1514525253161-7a46d19cd819?auto=format&fit=crop&w=900&q=90"
                            alt="Ekstrakurikuler seni"
                            loading="lazy"
                        >

                        <span>
                            04
                        </span>

                    </div>

                    <div class="extra-content">

                        <small>
                            ART
                        </small>

                        <h3>
                            Seni
                        </h3>

                        <p>
                            Ruang untuk mengekspresikan kreativitas
                            melalui berbagai bentuk seni.
                        </p>

                    </div>

                </article>


                {{-- MUSIK --}}
                <article class="extra-card">

                    <div class="extra-image">

                        <img
                            src="https://images.unsplash.com/photo-1524650359799-842906ca1c06?auto=format&fit=crop&w=900&q=90"
                            alt="Ekstrakurikuler musik"
                            loading="lazy"
                        >

                        <span>
                            05
                        </span>

                    </div>

                    <div class="extra-content">

                        <small>
                            MUSIC
                        </small>

                        <h3>
                            Musik
                        </h3>

                        <p>
                            Mengembangkan musikalitas, kreativitas,
                            dan kepercayaan diri siswa.
                        </p>

                    </div>

                </article>


                {{-- ROBOTIK --}}
                <article class="extra-card">

                    <div class="extra-image">

                        <img
                            src="https://images.unsplash.com/photo-1485827404703-89b55fcc595e?auto=format&fit=crop&w=900&q=90"
                            alt="Ekstrakurikuler robotik"
                            loading="lazy"
                        >

                        <span>
                            06
                        </span>

                    </div>

                    <div class="extra-content">

                        <small>
                            TECHNOLOGY
                        </small>

                        <h3>
                            Robotik
                        </h3>

                        <p>
                            Mengenal teknologi, pemrograman,
                            kreativitas, dan pemecahan masalah.
                        </p>

                    </div>

                </article>

            </div>

        </div>

    </div>

</section>



{{-- =========================================================
   KONTAK
   ========================================================= --}}
<section
    class="contact-section"
    id="kontak"
>

    <div class="container">

        <div class="contact-header">

            <div>

                <span class="section-kicker">
                    HUBUNGI KAMI
                </span>

                <h2>
                    Informasi dan
                    <strong>kontak sekolah.</strong>
                </h2>

            </div>

            <p>
                Silakan hubungi sekolah untuk mendapatkan
                informasi mengenai layanan, kegiatan,
                pendaftaran, maupun informasi pendidikan lainnya.
            </p>

        </div>


        <div class="contact-grid">

            {{-- TELEPON --}}
            <div class="contact-card">

                <div class="contact-card-top">

                    <div class="contact-icon">
                        <i class="bi bi-telephone-fill"></i>
                    </div>

                    <span>
                        01
                    </span>

                </div>

                <small>
                    TELEPON
                </small>

                <h3>
                    08XX-XXXX-XXXX
                </h3>

                <p>
                    Hubungi sekolah untuk mendapatkan
                    informasi secara langsung.
                </p>

            </div>


            {{-- EMAIL --}}
            <div class="contact-card">

                <div class="contact-card-top">

                    <div class="contact-icon">
                        <i class="bi bi-envelope-fill"></i>
                    </div>

                    <span>
                        02
                    </span>

                </div>

                <small>
                    EMAIL
                </small>

                <h3>
                    info@sekolah.sch.id
                </h3>

                <p>
                    Kirim pertanyaan atau informasi
                    melalui email sekolah.
                </p>

            </div>


            {{-- ALAMAT --}}
            <div class="contact-card">

                <div class="contact-card-top">

                    <div class="contact-icon">
                        <i class="bi bi-geo-alt-fill"></i>
                    </div>

                    <span>
                        03
                    </span>

                </div>

                <small>
                    ALAMAT
                </small>

                <h3>
                    Alamat Sekolah
                </h3>

                <p>
                    Kunjungi lokasi sekolah untuk mendapatkan
                    informasi secara langsung.
                </p>

            </div>

        </div>


        {{-- MAP --}}
        <div class="map-wrapper">

            <div class="map-frame">

                <iframe
                    src="https://www.google.com/maps?q=Indonesia&output=embed"
                    width="100%"
                    height="100%"
                    style="border:0;"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>

            </div>

            <a
                href="https://www.google.com/maps"
                target="_blank"
                rel="noopener noreferrer"
                class="map-button"
            >
                <span>
                    <i class="bi bi-map-fill"></i>
                    Lihat Lokasi di Google Maps
                </span>

                <i class="bi bi-arrow-right"></i>
            </a>

        </div>

    </div>

</section>



{{-- =========================================================
   HERO SLIDER SCRIPT
   ========================================================= --}}
<script>

document.addEventListener('DOMContentLoaded', function () {

    const slides = document.querySelectorAll('.hero-slide');
    const dots = document.querySelectorAll('.hero-dot');

    const nextButton = document.querySelector('.hero-next');
    const prevButton = document.querySelector('.hero-prev');

    let currentSlide = 0;
    let autoSlide;


    function showSlide(index) {

        if (!slides.length) {
            return;
        }

        currentSlide =
            (index + slides.length) % slides.length;

        slides.forEach((slide, i) => {
            slide.classList.toggle(
                'active',
                i === currentSlide
            );
        });

        dots.forEach((dot, i) => {
            dot.classList.toggle(
                'active',
                i === currentSlide
            );
        });

    }


    function nextSlide() {
        showSlide(currentSlide + 1);
    }


    function prevSlide() {
        showSlide(currentSlide - 1);
    }


    function startAutoSlide() {

        clearInterval(autoSlide);

        autoSlide = setInterval(
            nextSlide,
            6000
        );

    }


    if (nextButton) {

        nextButton.addEventListener(
            'click',
            function () {

                nextSlide();
                startAutoSlide();

            }
        );

    }


    if (prevButton) {

        prevButton.addEventListener(
            'click',
            function () {

                prevSlide();
                startAutoSlide();

            }
        );

    }


    dots.forEach((dot, index) => {

        dot.addEventListener(
            'click',
            function () {

                showSlide(index);
                startAutoSlide();

            }
        );

    });


    showSlide(0);
    startAutoSlide();

});

</script>

@endsection