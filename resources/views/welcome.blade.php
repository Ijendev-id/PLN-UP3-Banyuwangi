<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="description" content="">
    <meta name="author" content="">

    <title>PLN UP3 | Landing</title>

    <!-- CSS FILES -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700&family=Open+Sans&display=swap" rel="stylesheet">

    <link href="{{ asset('landing/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('landing/css/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('landing/css/templatemo-topic-listing.css') }}" rel="stylesheet">
</head>

<body id="top">

<main>

    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
            <img src="{{ asset('landing/images/pln.png') }}"
                alt="PLN UP3 Banyuwangi"
                class="me-2"
                style="height:36px;width:auto;">
            <span>PLN UP3 BWI</span>
            </a>

            <div class="d-lg-none ms-auto me-4">
            <a href="{{ route('login') }}" class="navbar-icon bi-person smoothscroll"></a>
            </div>

            <div class="d-none d-lg-block">
            <a href="{{ route('login') }}" class="navbar-icon bi-person smoothscroll"></a>
            </div>
        </div>
        </nav>

    <!-- Hero Section -->
    <section class="hero-section d-flex justify-content-center align-items-center" id="section_1">
        <div class="container">
            <div class="row">

                <div class="col-lg-8 col-12 mx-auto">
                    <h2 class="text-white text-center">Sistem Manajemen Data Gardu</h2>
                    <h6 class="text-center">PLN UP3 Banyuwangi</h6>


                </div>

            </div>
        </div>
    </section>

    <!-- Featured Section -->
    <section class="featured-section">
        <div class="container">
            <div class="row justify-content-center">

                <div class="col-lg-4 col-12 mb-4 mb-lg-0">
                    <div class="custom-block bg-white shadow-lg">
                        <a href="{{ url('topics-detail') }}">
                            <div class="d-flex">
                                <div>
                                    <h5 class="mb-2">PLN UP3 Bayuwangi </h5>
                                    <p class="mb-0">PLN UP3 Banyuwangi adalah Unit Pelaksana Pelayanan Pelanggan (UP3) milik PT PLN (Persero) yang menangani layanan kelistrikan distribusi dan pelayanan pelanggan untuk wilayah Banyuwangi. Dalam struktur PLN, UP3 membawahi beberapa Unit Layanan Pelanggan (ULP) di wilayah kerja tersebut..</p>
                                </div>

                            </div>
                            <img src="{{ asset('landing/images/topics/pln.png') }}" class="custom-block-image img-fluid" alt="">
                        </a>
                    </div>
                </div>

                <div class="col-lg-6 col-12">
                    <div class="custom-block custom-block-overlay">
                        <div class="d-flex flex-column h-100">
                        {{-- background image / cover --}}
                        <img src="{{ asset('landing/images/businesswoman-using-tablet-analysis.jpg') }}"
                            class="custom-block-image img-fluid" alt="Ilustrasi monitoring data">

                        <div class="custom-block-overlay-text d-flex">
                            <div class="text-center text-lg-start w-100">
                            {{-- TEKS di atas tombol --}}
                            <h5 class="text-white mb-3">
                                Sistem Monitoring dan Manajemen Data Gardu UP3 Banyuwangi
                            </h5>

                            {{-- GAMBAR di bawah teks, di atas tombol --}}
                            <img src="{{ asset('landing/images/12.png') }}"
                                alt="Logo PLN UP3 Banyuwangi"
                                class="img-fluid my-2"
                                style="max-width:100%;">

                            {{-- Tombol Masuk --}}
                            <a href="{{ route('login') }}" class="btn custom-btn mt-3">Masuk</a>
                            </div>
                        </div>

                        <div class="section-overlay"></div>
                        </div>
                    </div>
                    </div>

            </div>
        </div>
    </section>

    <!-- Explore Section -->


</main>

<footer class="site-footer section-padding">
    <div class="container">
        <div class="row align-items-center">
            <!-- Kolom Logo -->
            <div class="col-lg-3 col-12 text-lg-start text-center mb-3 mb-lg-0">
                <a href="{{ url('/') }}">
                    <img src="{{ asset('landing/images/pln.png') }}"
                         class="custom-block-image img-fluid"
                         alt="Logo PLN UP3 Banyuwangi"
                         style="max-width:150px;">
                </a>
            </div>

            <!-- Kolom Information (sejajar dengan logo, di tengah secara vertikal) -->
            <div class="col-lg-6 col-12 text-center">
                <h6 class="site-footer-title mb-3">Information</h6>
                <p class="text-white mb-1">
                    <a href="https://www.instagram.com/plnup3banyuwangi/" class="site-footer-link">
                        @plnup3banyuwangi
                    </a>
                </p>
                <p class="text-white">
                    <a href="https://share.google/YuALD9e7hn1yXFfEx" class="site-footer-link">
                        JL. Nusantara No. 1, Banyuwangi, Jawa Timur, Indonesia 68412
                    </a>
                </p>
            </div>

            <!-- Kolom Kosong untuk keseimbangan layout -->
            <div class="col-lg-3 d-none d-lg-block"></div>
        </div>

        <!-- Copyright di tengah bawah -->
        <div class="row mt-4">
            <div class="col-12 text-center">
                <p class="copyright-text">
                    Copyright © 2025 PLN UP3 Banyuwangi
                    <br>Web By:
                    <a rel="nofollow" href="https://www.instagram.com/ijendev.id/" target="_blank">
                        UP3 Banyuwangi
                    </a>
                </p>
            </div>
        </div>
    </div>
</footer>


<!-- JAVASCRIPT FILES -->
<script src="{{ asset('landing/js/jquery.min.js') }}"></script>
<script src="{{ asset('landing/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('landing/js/jquery.sticky.js') }}"></script>
<script src="{{ asset('landing/js/click-scroll.js') }}"></script>
<script src="{{ asset('landing/js/custom.js') }}"></script>

</body>
</html>
