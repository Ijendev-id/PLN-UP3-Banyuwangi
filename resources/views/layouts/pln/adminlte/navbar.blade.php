<aside class="main-sidebar sidebar-dark-blue elevation-4" style="background-color:#151e3c">
  <!-- Brand Logo -->
  <div class="brand-link d-flex justify-content-center align-items-center py-3" style="background:none;">
    <img src="{{ asset('asset_halaman_desa/img/pln.png') }}"
         alt="Logo PLN"
         style="width:80px; height:auto; border-radius:0; display:block;">
  </div>

  <!-- Sidebar -->
   <div class="sidebar">
    <!-- Sidebar user panel -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="{{ asset('asset_halaman_desa/img/orang.png') }}" class="img-circle elevation-2" alt="User Image">
      </div>

      <div class="info">
        @auth
          @php
            $user = Auth::user();
            $rawRole = method_exists($user, 'getRoleNames')
                        ? ($user->getRoleNames()->first() ?? 'tamu')
                        : ($user->role ?? 'tamu');
            $roleKey = strtolower($rawRole);
            $roleMap = [
              'admin'   => ['Admin',   'badge-danger'],
              'petugas' => ['Petugas', 'badge-warning'],
              'tamu'    => ['Tamu',    'badge-secondary'],
            ];
            [$roleLabel, $roleBadge] = $roleMap[$roleKey] ?? [ucfirst($roleKey), 'badge-info'];
          @endphp

          <a href="#" class="d-block" style="color:#f0f0f0;">
            {{ $user->nama_lengkap ?? $user->name ?? 'Pengguna' }}
          </a>
          <span class="badge {{ $roleBadge }}">{{ $roleLabel }}</span>
        @endauth

        @guest
          <a href="{{ route('login') }}" class="d-block" style="color:#f0f0f0;">Masuk</a>
          <span class="badge badge-secondary">Tamu</span>
        @endguest
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

        {{-- Dashboard --}}
        <li class="nav-item">
          <a href="{{ route('dashboard') }}"
             class="nav-link {{ request()->routeIs('dashboard') ? 'active-link' : '' }}">
            <i class="nav-icon fas fa-house-chimney"></i>
            <p>Dashboard</p>
          </a>
        </li>

        {{-- Data Gardu (langsung) --}}
        <li class="nav-item">
        <a href="{{ route('gardu.index') }}"
            class="nav-link
            {{ (request()->routeIs('gardu.*')
                && !request()->routeIs(['gardu.history.*','gardu.qr','gardu.findByKode']))
                ? 'active-link' : '' }}">
            <i class="nav-icon fas fa-database"></i>
            <p>Data Gardu</p>
        </a>
        </li>

        {{-- Data Historis (dropdown) --}}
        @php
          $isHistMenuOpen = request()->routeIs('omt-pengukuran.history.*')
                            || request()->routeIs('pemeliharaan.history.*')
                            || request()->routeIs('gardu.history.*');
        @endphp
        <li class="nav-item has-treeview {{ $isHistMenuOpen ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ $isHistMenuOpen ? 'active-link' : '' }}">
            <i class="nav-icon fas fa-history"></i>
            <p>
              Data Historis
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>

          <ul class="nav nav-treeview">
            {{-- Historis OMT Pengukuran --}}
            <li class="nav-item">
              <a href="{{ route('omt-pengukuran.history.index') }}"
                 class="nav-link {{ request()->routeIs('omt-pengukuran.history.*') ? 'active-link' : '' }}">
                <i class="nav-icon fas fa-clipboard-check"></i>
                <p>Historis Pengukuran</p>
              </a>
            </li>

            {{-- Historis OMT Pemeliharaan (ganti href jika routenya sudah ada) --}}
            <li class="nav-item">
              <a href="{{ Route::has('pemeliharaan.history.index') ? route('pemeliharaan.history.index') : '#' }}"
                 class="nav-link {{ request()->routeIs('pemeliharaan.history.*') ? 'active-link' : '' }}">
                <i class="nav-icon fas fa-tools"></i>
                <p>Historis Pemeliharaan</p>
              </a>
            </li>

            {{-- Historis Data Gardu (ganti href jika routenya sudah ada) --}}
            <li class="nav-item">
              <a href="{{ Route::has('gardu.history.index') ? route('gardu.history.index') : '#' }}"
                 class="nav-link {{ request()->routeIs('gardu.history.*') ? 'active-link' : '' }}">
                <i class="nav-icon fas fa-database"></i>
                <p>Historis Data Gardu</p>
              </a>
            </li>
          </ul>
        </li>
        {{-- Tombol Scan QR Gardu (di bawah Data Historis) --}}
        {{-- Tombol Scan QR Gardu (di bawah Data Historis) --}}
        <li class="nav-item">
            <a href="{{ Route::has('gardu.qr') ? route('gardu.qr') : '#' }}"
                class="nav-link {{ request()->routeIs('gardu.qr') ? 'active-link' : '' }}">
                <i class="nav-icon fas fa-qrcode"></i>
                <p>Scan QR Gardu</p>
            </a>
            </li>

        {{-- Monitoring --}}


      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>

{{-- Style link aktif (kuning PLN-ish) --}}
<style>
  /* Warna aktif utama */
  .nav-link.active-link {
    background: #FFC107 !important;   /* amber */
    color: #0b132b !important;        /* gelap, kontras enak dibaca */
    border-radius: 8px;
  }
  .nav-link.active-link i {
    color: #0b132b !important;
  }

  /* Hover efek lembut untuk semua link */
  .nav-sidebar .nav-link:hover {
    background: rgba(255, 193, 7, 0.10); /* amber 10% */
  }

  /* Anak treeview aktif: warna lebih soft */
  .nav-sidebar .nav-treeview > .nav-item > .nav-link.active-link {
    background: rgba(255, 193, 7, 0.20) !important; /* amber 20% */
    color: #FFC107 !important;
  }

  /* Ikon di submenu tetap terlihat */
  .nav-treeview .nav-link i.nav-icon {
    width: 1.25rem;
    text-align: center;
  }
</style>
