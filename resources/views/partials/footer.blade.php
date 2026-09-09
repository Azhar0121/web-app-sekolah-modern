<link rel="stylesheet" href="{{ asset('css/footer.css') }}">

<footer class="school-footer" id="kontak">

    {{-- DECORATION --}}
    <div class="footer-decoration footer-decoration-1"></div>
    <div class="footer-decoration footer-decoration-2"></div>


    <div class="footer-container">

        {{-- =========================
            PROFIL SEKOLAH
        ========================== --}}
        <div class="footer-brand-column">

            <div class="footer-brand">

                {{-- LOGO SEKOLAH --}}
                <div class="footer-logo">

                    <svg
                        viewBox="0 0 64 64"
                        xmlns="http://www.w3.org/2000/svg"
                        aria-label="Logo Sekolah"
                    >

                        {{-- SHIELD --}}
                        <path
                            d="M32 4
                               L55 12
                               V29
                               C55 44 45 54 32 60
                               C19 54 9 44 9 29
                               V12
                               Z"
                            fill="#ffffff"
                        />

                        {{-- INNER SHIELD --}}
                        <path
                            d="M32 10
                               L49 16
                               V29
                               C49 40 42 48 32 53
                               C22 48 15 40 15 29
                               V16
                               Z"
                            fill="#0f2747"
                        />

                        {{-- STAR --}}
                        <path
                            d="M32 15
                               L34.5 21
                               L41 21
                               L36 25
                               L38 31
                               L32 27
                               L26 31
                               L28 25
                               L23 21
                               L29.5 21
                               Z"
                            fill="#ffffff"
                        />

                        {{-- BOOK --}}
                        <path
                            d="M19 34
                               C23 32 27 33 32 36
                               C37 33 41 32 45 34
                               V44
                               C41 42 37 42 32 45
                               C27 42 23 42 19 44
                               Z"
                            fill="#ffffff"
                        />

                        {{-- BOOK CENTER --}}
                        <path
                            d="M32 36 V45"
                            stroke="#0f2747"
                            stroke-width="1.8"
                            stroke-linecap="round"
                        />

                    </svg>

                </div>


                {{-- NAMA SEKOLAH --}}
                <div class="footer-brand-text">

                    <strong>SEKOLAH MODERN</strong>

                    <span>
                        Sistem Informasi Sekolah
                    </span>

                </div>

            </div>


            {{-- DESCRIPTION --}}
            <p class="footer-description">

                Portal resmi <strong>Sekolah Modern</strong> yang menyediakan
                informasi, layanan pendidikan, kegiatan siswa, dan
                berbagai layanan sekolah secara digital.

            </p>


            {{-- SOCIAL MEDIA --}}
            <div class="footer-social-wrapper">

                <span class="footer-social-label">
                    Ikuti Kami
                </span>

                <div class="footer-social">

                    <a
                        href="https://www.facebook.com/"
                        target="_blank"
                        rel="noopener noreferrer"
                        aria-label="Facebook"
                    >
                        <i class="bi bi-facebook"></i>
                    </a>


                    <a
                        href="https://www.instagram.com/"
                        target="_blank"
                        rel="noopener noreferrer"
                        aria-label="Instagram"
                    >
                        <i class="bi bi-instagram"></i>
                    </a>


                    <a
                        href="https://www.youtube.com/"
                        target="_blank"
                        rel="noopener noreferrer"
                        aria-label="YouTube"
                    >
                        <i class="bi bi-youtube"></i>
                    </a>

                </div>

            </div>

        </div>


        {{-- =========================
            KONTAK SEKOLAH
        ========================== --}}
        <div class="footer-contact-column">

            <div class="footer-section-heading">

                <span class="footer-heading-line"></span>

                <div>

                    <span class="footer-heading-label">
                        HUBUNGI KAMI
                    </span>

                    <h3>
                        Kontak Sekolah
                    </h3>

                </div>

            </div>


            <div class="footer-contact-grid">

                {{-- TELEPON --}}
                <div class="footer-contact-item">

                    <div class="footer-contact-icon">
                        <i class="bi bi-telephone-fill"></i>
                    </div>

                    <div class="footer-contact-content">

                        <span>
                            Telepon
                        </span>

                        <strong>
                            08XX-XXXX-XXXX
                        </strong>

                    </div>

                </div>


                {{-- EMAIL --}}
                <div class="footer-contact-item">

                    <div class="footer-contact-icon">
                        <i class="bi bi-envelope-fill"></i>
                    </div>

                    <div class="footer-contact-content">

                        <span>
                            Email
                        </span>

                        <strong>
                            info@sekolah.sch.id
                        </strong>

                    </div>

                </div>


                {{-- ALAMAT --}}
                <div class="footer-contact-item footer-contact-address">

                    <div class="footer-contact-icon">
                        <i class="bi bi-geo-alt-fill"></i>
                    </div>

                    <div class="footer-contact-content">

                        <span>
                            Alamat Sekolah
                        </span>

                        <strong>
                            Alamat Sekolah
                        </strong>

                    </div>

                </div>

            </div>


            {{-- GOOGLE MAP --}}
            <a
                href="https://www.google.com/maps"
                target="_blank"
                rel="noopener noreferrer"
                class="footer-map-button"
            >

                <span class="footer-map-icon">
                    <i class="bi bi-map-fill"></i>
                </span>


                <span class="footer-map-text">

                    <strong>
                        Lihat Lokasi Sekolah
                    </strong>

                    <small>
                        Buka di Google Maps
                    </small>

                </span>


                <i class="bi bi-arrow-up-right footer-map-arrow"></i>

            </a>

        </div>

    </div>


    {{-- =========================
        FOOTER BOTTOM
    ========================== --}}
    <div class="footer-bottom">

        <div class="footer-bottom-container">

            <p>
                &copy; {{ date('Y') }}

                <strong>
                    SEKOLAH MODERN
                </strong>.

                Semua hak dilindungi.
            </p>

            <span>
                Sistem Informasi Sekolah
            </span>

        </div>

    </div>

</footer>
