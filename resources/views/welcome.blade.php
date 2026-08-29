@extends('layouts.public')

@section('title', config('app.name'))

@section('content')

{{-- =========================================================
HERO
========================================================= --}}

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<section class="hero">


<div class="hero-decoration hero-decoration-one"></div>
<div class="hero-decoration hero-decoration-two"></div>
<div class="hero-grid-pattern"></div>

<div class="hero-container">

    <div class="hero-content">

        <div class="hero-eyebrow">
            <span></span>
            SISTEM INFORMASI SEKOLAH
        </div>

        <h1>
            Pendidikan Modern,
            <span>Masa Depan Gemilang.</span>
        </h1>

        <p class="hero-description">
            Selamat datang di sekolah modern.
            Sebuah lingkungan pendidikan yang dirancang untuk membantu
            peserta didik berkembang secara akademik, membangun karakter,
            mengembangkan kreativitas, serta mempersiapkan diri menghadapi
            tantangan masa depan.
        </p>

        <div class="hero-cta-group">

            <a href="{{ url('/login') }}" class="main-login-button">

                <span class="login-icon">
                    &#8599;
                </span>

                <span class="login-text">
                    <small>AKSES PORTAL INTERNAL</small>
                    <strong>Masuk ke Portal</strong>
                </span>

                <span class="login-arrow">
                    &rarr;
                </span>

            </a>

            <a href="{{ route('ppdb.index') }}" class="main-ppdb-button">

                <span class="login-icon">
                    &#128196;
                </span>

                <span class="login-text">
                    <small>TAHUN AJARAN BARU</small>
                    <strong>PPDB Online</strong>
                </span>

                <span class="login-arrow">
                    &rarr;
                </span>

            </a>

        </div>

        <div class="hero-note">
            <span class="check">&check;</span>
            Portal resmi {{ config('app.name') }}
        </div>

        <div class="hero-mini-stats">

            <div>
                <strong>01</strong>
                <span>Platform<br>Digital</span>
            </div>

            <div>
                <strong>24/7</strong>
                <span>Akses<br>Informasi</span>
            </div>

            <div>
                <strong>∞</strong>
                <span>Ruang<br>Belajar</span>
            </div>

        </div>

    </div>


    <div class="hero-image-wrapper">

        <div class="hero-image-shadow"></div>

        <div class="image-frame">

            <img
                src="https://file.data.kemendikdasmen.go.id/sekolahkita/69/6996/69969076-3.jpg"
                alt="Gedung {{ config('app.name') }}"
            >

            <div class="image-gradient"></div>

            <div class="image-caption">

                <span>DIGITAL SCHOOL</span>

                <strong>
                    Membangun generasi unggul melalui pendidikan
                    yang berkarakter, kreatif, dan adaptif.
                </strong>

            </div>

        </div>

        <div class="hero-floating-card">

            <div class="floating-card-icon">
                &#10022;
            </div>

            <div>
                <small>SEKOLAH MASA KINI</small>
                <strong>Belajar • Berkembang • Berprestasi</strong>
            </div>

        </div>

    </div>

</div>


</section>

{{-- =========================================================
QUICK FEATURES
========================================================= --}}

<section class="quick-section">


<div class="quick-container">

    <div class="quick-intro">

        <span>DIGITAL SCHOOL</span>

        <strong>
            Satu ekosistem untuk mendukung kebutuhan sekolah.
        </strong>

    </div>


    <div class="quick-item">

        <div class="quick-icon">
            &check;
        </div>

        <div>
            <strong>Terintegrasi</strong>
            <span>Sistem sekolah dalam satu platform</span>
        </div>

    </div>


    <div class="quick-item">

        <div class="quick-icon">
            &#9671;
        </div>

        <div>
            <strong>Efisien</strong>
            <span>Informasi lebih cepat dan terstruktur</span>
        </div>

    </div>


    <div class="quick-item">

        <div class="quick-icon">
            &#10022;
        </div>

        <div>
            <strong>Modern</strong>
            <span>Teknologi untuk pendidikan masa kini</span>
        </div>

    </div>

</div>


</section>

{{-- =========================================================
TENTANG SEKOLAH
========================================================= --}}

<section class="school-about-section">


<div class="section-orb section-orb-left"></div>
<div class="section-orb section-orb-right"></div>

<div class="school-about-container">

    <div class="school-about-heading">

        <div class="section-label">
            TENTANG SEKOLAH
        </div>

        <h2>
            Ruang untuk
            <span>tumbuh, belajar,</span>
            dan menemukan potensi terbaik.
        </h2>

        <div class="heading-line"></div>

        <p>
            Sekolah ini merupakan lingkungan pendidikan
            yang menempatkan proses belajar sebagai perjalanan untuk
            mengenal diri, mengembangkan kemampuan, membangun karakter,
            dan mempersiapkan peserta didik menghadapi kehidupan di
            masa depan.
        </p>

        <p>
            Di dalamnya, peserta didik tidak hanya diarahkan untuk
            memperoleh pengetahuan akademik, tetapi juga diberikan
            ruang untuk bertanya, mencoba, berkreasi, bekerja sama,
            serta belajar dari berbagai pengalaman.
        </p>

    </div>


    <div class="school-about-content">

        <div class="about-main-card">

            <div class="about-card-number">
                01
            </div>

            <div class="about-card-content">

                <span>OUR SCHOOL</span>

                <h3>
                    Sekolah yang
                    <strong>mempersiapkan masa depan.</strong>
                </h3>

                <p>
                    Sekolah modern hadir sebagai tempat
                    peserta didik untuk memperoleh pengalaman belajar
                    yang lebih luas. Pembelajaran tidak hanya berfokus
                    pada pencapaian nilai, tetapi juga pada bagaimana
                    siswa mampu berpikir, berkomunikasi, bekerja sama,
                    mengambil keputusan, dan bertanggung jawab terhadap
                    proses yang mereka jalani.
                </p>

            </div>

            <div class="about-card-mark">
                +
            </div>

        </div>


        <div class="about-small-grid">

            <div class="about-small-card">

                <div class="small-card-icon">
                    &#10022;
                </div>

                <strong>
                    Karakter
                </strong>

                <p>
                    Membentuk pribadi yang disiplin, bertanggung jawab,
                    percaya diri, berintegritas, dan peduli terhadap
                    lingkungan sekitar.
                </p>

            </div>


            <div class="about-small-card dark-card">

                <div class="small-card-icon">
                    &#9671;
                </div>

                <strong>
                    Kompetensi
                </strong>

                <p>
                    Mengembangkan kemampuan akademik, keterampilan,
                    komunikasi, berpikir kritis, dan kemampuan
                    menyelesaikan masalah.
                </p>

            </div>

        </div>

    </div>

</div>


<div class="about-bottom-strip">

    <div class="about-highlight-card">
        <span>01</span>
        <strong>Karakter</strong>
        <small>Pribadi yang berintegritas</small>
    </div>

    <div class="about-highlight-card">
        <span>02</span>
        <strong>Kompetensi</strong>
        <small>Kemampuan untuk terus berkembang</small>
    </div>

    <div class="about-highlight-card">
        <span>03</span>
        <strong>Kreativitas</strong>
        <small>Berani mencipta dan berinovasi</small>
    </div>

    <div class="about-highlight-card">
        <span>04</span>
        <strong>Prestasi</strong>
        <small>Memberikan kemampuan terbaik</small>
    </div>

</div>


</section>

{{-- =========================================================
VISI MISI
========================================================= --}}

<section class="vision-section">


<div class="vision-container">

    <div class="vision-intro">

        <div class="section-label">
            ARAH PENDIDIKAN
        </div>

        <h2>
            Visi &amp;
            <span>Misi Sekolah.</span>
        </h2>

        <p>
            Setiap perjalanan pendidikan membutuhkan arah yang jelas.
            Visi menjadi gambaran masa depan yang ingin dicapai,
            sedangkan misi menjadi langkah nyata yang dilakukan
            untuk mewujudkannya.
        </p>

        <div class="vision-quote">

            <span class="quote-mark">“</span>

            <p>
                Pendidikan yang baik bukan hanya menghasilkan
                peserta didik yang mampu memahami pelajaran,
                tetapi juga pribadi yang memiliki karakter,
                kreativitas, dan kesiapan menghadapi masa depan.
            </p>

        </div>

    </div>


    <div class="vision-content">

        {{-- VISI --}}
        <div class="vision-card vision-main-card">

            <div class="vision-card-top">

                <div class="vision-title-group">

                    <span>01</span>

                    <strong>
                        VISI SEKOLAH
                    </strong>

                </div>

                <small>
                    OUR DIRECTION
                </small>

            </div>

            <h3>
                Menjadi lingkungan pendidikan yang unggul,
                berkarakter, adaptif, kreatif, dan mampu
                mempersiapkan generasi menghadapi masa depan.
            </h3>

            <div class="vision-card-line"></div>

            <p>
                Visi ini menjadi landasan dalam menciptakan proses
                pembelajaran yang tidak hanya berorientasi pada
                pencapaian akademik, tetapi juga perkembangan karakter,
                kreativitas, keterampilan, dan potensi setiap peserta
                didik.
            </p>

        </div>


        {{-- MISI --}}
        <div class="mission-wrapper">

            <div class="mission-heading">

                <div>
                    <span>02</span>
                    <strong>MISI SEKOLAH</strong>
                </div>

                <small>
                    OUR ACTIONS
                </small>

            </div>


            <div class="mission-list">

                <div class="mission-card">

                    <div class="mission-number">
                        01
                    </div>

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


                <div class="mission-card">

                    <div class="mission-number">
                        02
                    </div>

                    <div>
                        <h4>
                            Membentuk Karakter
                        </h4>

                        <p>
                            Menumbuhkan sikap disiplin, tanggung jawab,
                            percaya diri, peduli, dan memiliki
                            integritas dalam kehidupan sehari-hari.
                        </p>
                    </div>

                </div>


                <div class="mission-card">

                    <div class="mission-number">
                        03
                    </div>

                    <div>
                        <h4>
                            Mendorong Kreativitas
                        </h4>

                        <p>
                            Menciptakan pembelajaran yang mendorong
                            rasa ingin tahu, keberanian mencoba,
                            inovasi, dan kemampuan menghasilkan ide.
                        </p>
                    </div>

                </div>


                <div class="mission-card">

                    <div class="mission-number">
                        04
                    </div>

                    <div>
                        <h4>
                            Beradaptasi dengan Teknologi
                        </h4>

                        <p>
                            Memanfaatkan teknologi sebagai bagian
                            dari proses belajar dan perkembangan
                            pendidikan modern.
                        </p>
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


</section>

{{-- =========================================================
NILAI SEKOLAH
========================================================= --}}

<section class="values-section">


<div class="values-container">

    <div class="values-heading">

        <div>

            <div class="section-label">
                NILAI YANG DIBANGUN
            </div>

            <h2>
                Membentuk pribadi,
                <span>bukan hanya nilai.</span>
            </h2>

        </div>

        <p>
            Pendidikan yang baik tumbuh dari keseimbangan antara
            pengetahuan, karakter, kreativitas, kemampuan beradaptasi,
            dan keberanian untuk memberikan yang terbaik.
        </p>

    </div>


    <div class="values-grid">

        <div class="value-card value-card-large">

            <div class="value-card-number">
                01
            </div>

            <div class="value-icon">
                <i class="bi bi-person-check-fill"></i>
            </div>

            <h3>
                Karakter
            </h3>

            <p>
                Membiasakan sikap positif yang menjadi bekal peserta
                didik dalam kehidupan sehari-hari dan masa depan.
            </p>

            <span class="value-line"></span>

        </div>


        <div class="value-card">

            <div class="value-card-number">
                02
            </div>

            <div class="value-icon">
                <i class="bi bi-lightbulb-fill"></i>
            </div>

            <h3>
                Kreatif
            </h3>

            <p>
                Mendorong keberanian untuk mencoba, menciptakan,
                mengeksplorasi ide, dan menemukan solusi.
            </p>

            <span class="value-line"></span>

        </div>


        <div class="value-card value-card-large">

            <div class="value-card-number">
                03
            </div>

            <div class="value-icon">
                <i class="bi bi-arrow-repeat"></i>
            </div>

            <h3>
                Adaptif
            </h3>

            <p>
                Mempersiapkan peserta didik agar mampu menghadapi
                perubahan zaman dan perkembangan teknologi.
            </p>

            <span class="value-line"></span>

        </div>


        <div class="value-card">

            <div class="value-card-number">
                04
            </div>

            <div class="value-icon">
                <i class="bi bi-trophy-fill"></i>
            </div>

            <h3>
                Berprestasi
            </h3>

            <p>
                Menumbuhkan semangat untuk berkembang dan memberikan
                kemampuan terbaik dalam berbagai bidang.
            </p>

            <span class="value-line"></span>

        </div>

    </div>

</div>


</section>

{{-- =========================================================
EKSTRAKURIKULER
========================================================= --}}

<section class="extracurricular-section">


<div class="extra-background-shape"></div>

<div class="extra-container">

    <div class="extra-heading">

        <div>

            <div class="section-label">
                KEGIATAN SISWA
            </div>

            <h2>
                Temukan passion
                <span>di luar kelas.</span>
            </h2>

        </div>

        <div class="extra-heading-right">

            <p>
                Kegiatan ekstrakurikuler menjadi ruang bagi siswa
                untuk mengeksplorasi minat, mengembangkan bakat,
                membangun kepercayaan diri, dan belajar bekerja
                sama dengan orang lain.
            </p>

            <div class="scroll-hint">
                <span>GESER UNTUK MELIHAT</span>
                <strong>&rarr;</strong>
            </div>

        </div>

    </div>


    <div class="extra-slider">

        <div class="extra-track">

            <article class="extra-card">

                <div class="extra-image">

                    <img
                        src="https://images.unsplash.com/photo-1553778263-73a83bab9b0c?auto=format&fit=crop&w=900&q=85"
                        alt="Ekstrakurikuler futsal"
                        loading="lazy"
                    >

                    <div class="extra-overlay"></div>

                    <span class="extra-number">
                        01
                    </span>

                    <span class="extra-category">
                        SPORT
                    </span>

                </div>

                <div class="extra-content">

                    <h3>Futsal</h3>

                    <p>
                        Melatih kerja sama tim, disiplin,
                        strategi, dan sportivitas.
                    </p>

                    <span class="extra-arrow">
                        &rarr;
                    </span>

                </div>

            </article>


            <article class="extra-card">

                <div class="extra-image">

                    <img
                        src="https://images.unsplash.com/photo-1529390079861-591de354faf5?auto=format&fit=crop&w=900&q=85"
                        alt="Ekstrakurikuler pramuka"
                        loading="lazy"
                    >

                    <div class="extra-overlay"></div>

                    <span class="extra-number">
                        02
                    </span>

                    <span class="extra-category">
                        CHARACTER
                    </span>

                </div>

                <div class="extra-content">

                    <h3>Pramuka</h3>

                    <p>
                        Membangun kemandirian, kepemimpinan,
                        kedisiplinan, dan kepedulian.
                    </p>

                    <span class="extra-arrow">
                        &rarr;
                    </span>

                </div>

            </article>


            <article class="extra-card">

                <div class="extra-image">

                    <img
                        src="https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?auto=format&fit=crop&w=900&q=85"
                        alt="Kegiatan PMR sekolah"
                        loading="lazy"
                    >

                    <div class="extra-overlay"></div>

                    <span class="extra-number">
                        03
                    </span>

                    <span class="extra-category">
                        SOCIAL
                    </span>

                </div>

                <div class="extra-content">

                    <h3>PMR</h3>

                    <p>
                        Mengenal kepedulian sosial, kesehatan,
                        dan semangat membantu sesama.
                    </p>

                    <span class="extra-arrow">
                        &rarr;
                    </span>

                </div>

            </article>


            <article class="extra-card">

                <div class="extra-image">

                    <img
                        src="https://images.unsplash.com/photo-1514525253161-7a46d19cd819?auto=format&fit=crop&w=900&q=85"
                        alt="Ekstrakurikuler seni"
                        loading="lazy"
                    >

                    <div class="extra-overlay"></div>

                    <span class="extra-number">
                        04
                    </span>

                    <span class="extra-category">
                        ART
                    </span>

                </div>

                <div class="extra-content">

                    <h3>Seni</h3>

                    <p>
                        Ruang untuk mengekspresikan kreativitas
                        melalui berbagai bentuk seni.
                    </p>

                    <span class="extra-arrow">
                        &rarr;
                    </span>

                </div>

            </article>


            <article class="extra-card">

                <div class="extra-image">

                    <img
                        src="https://images.unsplash.com/photo-1524650359799-842906ca1c06?auto=format&fit=crop&w=900&q=85"
                        alt="Ekstrakurikuler musik"
                        loading="lazy"
                    >

                    <div class="extra-overlay"></div>

                    <span class="extra-number">
                        05
                    </span>

                    <span class="extra-category">
                        MUSIC
                    </span>

                </div>

                <div class="extra-content">

                    <h3>Musik</h3>

                    <p>
                        Mengembangkan musikalitas, kreativitas,
                        dan kepercayaan diri siswa.
                    </p>

                    <span class="extra-arrow">
                        &rarr;
                    </span>

                </div>

            </article>


            <article class="extra-card">

                <div class="extra-image">

                    <img
                        src="https://images.unsplash.com/photo-1485827404703-89b55fcc595e?auto=format&fit=crop&w=900&q=85"
                        alt="Ekstrakurikuler teknologi dan robotik"
                        loading="lazy"
                    >

                    <div class="extra-overlay"></div>

                    <span class="extra-number">
                        06
                    </span>

                    <span class="extra-category">
                        TECHNOLOGY
                    </span>

                </div>

                <div class="extra-content">

                    <h3>Robotik</h3>

                    <p>
                        Mengenal teknologi, pemrograman,
                        kreativitas, dan pemecahan masalah.
                    </p>

                    <span class="extra-arrow">
                        &rarr;
                    </span>

                </div>

            </article>

        </div>

    </div>

</div>


</section>

{{-- =========================================================
KONTAK SEKOLAH
========================================================= --}}

<section class="portal-section">


<div class="portal-decoration portal-decoration-one"></div>
<div class="portal-decoration portal-decoration-two"></div>

<div class="portal-container">

    {{-- HEADER KONTAK --}}

    <div class="portal-copy">

        <div class="portal-label">
            <span></span>
            KONTAK SEKOLAH
        </div>

        <h2>
            Hubungi
            <span>{{ config('app.name') }}.</span>
        </h2>

        <p class="portal-description">
            Silakan hubungi sekolah untuk mendapatkan informasi
            mengenai kegiatan, layanan, pendaftaran, maupun
            informasi pendidikan lainnya.
        </p>

    </div>


    {{-- KARTU KONTAK --}}

    <div class="school-contact-grid">

        {{-- TELEPON --}}

        <div class="contact-card">

            <div class="contact-card-top">

                <div class="contact-icon">
                    <i class="bi bi-telephone-fill"></i>
                </div>

                <span class="contact-number">
                    01
                </span>

            </div>

            <div class="contact-card-content">

                <small>
                    TELEPON
                </small>

                <strong>
                    08XX-XXXX-XXXX
                </strong>

                <p>
                    Hubungi sekolah untuk mendapatkan
                    informasi secara langsung.
                </p>

            </div>

        </div>


        {{-- EMAIL --}}

        <div class="contact-card">

            <div class="contact-card-top">

                <div class="contact-icon">
                    <i class="bi bi-envelope-fill"></i>
                </div>

                <span class="contact-number">
                    02
                </span>

            </div>

            <div class="contact-card-content">

                <small>
                    EMAIL
                </small>

                <strong>
                    info@sekolah.sch.id
                </strong>

                <p>
                    Kirim pertanyaan atau informasi
                    melalui email sekolah.
                </p>

            </div>

        </div>


        {{-- ALAMAT --}}

        <div class="contact-card">

            <div class="contact-card-top">

                <div class="contact-icon">
                    <i class="bi bi-geo-alt-fill"></i>
                </div>

                <span class="contact-number">
                    03
                </span>

            </div>

            <div class="contact-card-content">

                <small>
                    ALAMAT SEKOLAH
                </small>

                <strong>
                    Alamat sekolah dapat ditambahkan di sini
                </strong>

                <p>
                    Kunjungi lokasi sekolah untuk mendapatkan
                    informasi secara langsung.
                </p>

            </div>

        </div>

    </div>


    {{-- GOOGLE MAPS --}}

    <div class="school-map-wrapper">

        <div class="school-map">


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

        </div>


        <a
            href="https://www.google.com/maps"
            target="_blank"
            rel="noopener noreferrer"
            class="portal-login-button"
        >

            <span>
                <i class="bi bi-map-fill"></i>
                Lihat Lokasi di Google Maps
            </span>

            <strong>
                &rarr;
            </strong>

        </a>

    </div>

</div>


</section>

@endsection
