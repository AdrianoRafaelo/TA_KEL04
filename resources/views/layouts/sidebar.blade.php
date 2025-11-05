<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <div class="sidebar-brand-wrapper d-none d-lg-flex align-items-center justify-content-center fixed-top">
    <a class="sidebarlogo brandlogo" href="index.html">SIPODA</a> <a class="sidebar-brand brand-logo-mini"
      href="index.html"><img src="assets/images/logo-mini.svg" alt="logo" /></a>
  </div>
  <ul class="nav">
    <li class="nav-item profile">
      <div class="profile-desc">
        <div class="profile-pic">
          <div class="count-indicator">
            <img class="img-xs rounded-circle " src="assets/images/faces/face15.jpg" alt="">
            <span class="count bg-success"></span>
          </div>
          <div class="profile-name">
            @php
              $user = \App\Models\FtiData::where('username', session('username'))->first();
              $displayName = $user ? $user->nama : (session('username') ?? 'User');
            @endphp
            <h5 class="mb-0 font-weight-normal" style="white-space: normal; word-wrap: break-word;">
              {{ $displayName }}
            </h5>
            <span>{{ session('role') ?? 'Member' }}</span>
          </div>
        </div>
        <a href="#" id="profile-dropdown" data-toggle="dropdown"><i class="mdi mdi-dots-vertical"></i></a>
        <div class="dropdown-menu dropdown-menu-right sidebar-dropdown preview-list" aria-labelledby="profile-dropdown">
          <form action="{{ url('/logout') }}" method="POST" class="dropdown-item preview-item"
            style="display: flex; align-items: center; padding: 0; background: none; border: none;">
            @csrf
            <div class="preview-thumbnail">
              <div class="preview-icon bg-dark rounded-circle">
                <i class="mdi mdi-logout text-danger"></i>
              </div>
            </div>
            <div class="preview-item-content">
              <button type="submit" class="btn btn-link p-0 m-0 text-small"
                style="color: black; font-weight: bold; text-decoration: none;">Logout</button>
            </div>
          </form>
        </div>
      </div>
    </li>
    <li class="nav-item nav-category">
      <span class="nav-link">Navigation</span>
    </li>
    <li class="nav-item menu-items">
      <a class="nav-link" href="{{ url('/') }}">
        <span class="menu-icon">
          <i class="mdi mdi-speedometer"></i>
        </span>
        <span class="menu-title">Dashboard</span>
      </a>
    </li>
    @if (session('role') == 'Admin')
      <li class="nav-item menu-items">
        <a class="nav-link" href="{{ url('/admin/roles') }}">
          <span class="menu-icon"><i class="mdi mdi-account-key"></i></span>
          <span class="menu-title">Kelola Role FTI</span>
        </a>
      </li>

      <li class="nav-item menu-items">
        <a class="nav-link" href="{{ url('pengumuman') }}">
          <span class="menu-icon"><i class="mdi mdi-bullhorn"></i></span>
          <span class="menu-title">Kelola Pengumuman</span>
        </a>
      </li>
      
    @endif
    @if (session('role') !== 'Admin' && session('role') !== 'Koordinator')
      <li class="nav-item menu-items">
        <a class="nav-link" href="{{ url('/matakuliah') }}">
          <span class="menu-icon">
            <i class="mdi mdi-notebook"></i>
          </span>
          <span class="menu-title">Matakuliah</span>
        </a>
      </li>
      <li class="nav-item menu-items">
        <a class="nav-link" href="{{ url('/fti-data') }}">
          <span class="menu-icon">
            <i class="mdi mdi-school"></i>
          </span>
          <span class="menu-title">Data FTI</span>
        </a>
      </li>
    @endif
    @if (session('role') !== 'Admin')
      <li class="nav-item menu-items">
        <a class="nav-link" data-toggle="collapse" href="#auth" aria-expanded="false" aria-controls="auth">
          <span class="menu-icon">
            <i class="mdi mdi-security"></i>
          </span>
          <span class="menu-medal">MBKM</span>
          <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="auth">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item"> <a class="nav-link" href="pages/samples/blank-page.html"> Mitra </a></li>
            <li class="nav-item"> <a class="nav-link" href="pages/samples/error-404.html"> Non-Mitra </a></li>
          </ul>
        </div>
      </li>
      @if (session('role') === 'Mahasiswa')
        <li class="nav-item menu-items">
          <a class="nav-link" href="{{ url('/kerja-praktik') }}">
            <span class="menu-icon">
              <i class="mdi mdi-domain"></i>
            </span>
            <span class="menu-title">Kerja Praktik</span>
          </a>
        </li>
      @endif
      @if (session('role') === 'Dosen')
        <li class="nav-item menu-items">
          <a class="nav-link" href="{{ url('/kerja-praktik-dosen') }}">
            <span class="menu-icon">
              <i class="mdi mdi-domain"></i>
            </span>
            <span class="menu-title">Kerja Praktik</span>
          </a>
        </li>
      @endif
      <!-- Menu untuk Mahasiswa -->
      @if (session('role') === 'Mahasiswa')
        <li class="nav-item menu-items">
          <a class="nav-link" href="{{ url('/ta-mahasiswa') }}">
            <span class="menu-icon">
              <i class="mdi mdi-file-document"></i>
            </span>
            <span class="menu-title">Tugas Akhir M</span>
          </a>
        </li>
      @endif

      <!-- Menu untuk Admin -->
      @if (session('role') == 'Admin')
        <li class="nav-item menu-items">
          <a class="nav-link" href="{{ url('/admin/roles') }}">
            <span class="menu-icon"><i class="mdi mdi-account-key"></i></span>
            <span class="menu-title">Kelola Role FTI</span>
          </a>
        </li>

      @endif

      <!-- Menu untuk Dosen -->
      @if (session('role') === 'Dosen')
        <li class="nav-item menu-items">
          <a class="nav-link" href="{{ url('/ta-dosen') }}">
            <span class="menu-icon">
              <i class="mdi mdi-file-document"></i>
            </span>
            <span class="menu-title">Tugas Akhir D</span>
          </a>
        </li>
      @endif
      @if (session('role') === 'Koordinator')
        <li class="nav-item menu-items">
          <a class="nav-link" href="{{ url('/kerja-praktik-koordinator') }}">
            <span class="menu-icon">
              <i class="mdi mdi-domain"></i>
            </span>
            <span class="menu-title">Kerja Praktik</span>
          </a>
        </li>
        <li class="nav-item menu-items">
          <a class="nav-link" href="{{ url('/ta-dosen') }}">
            <span class="menu-icon">
              <i class="mdi mdi-file-document"></i>
            </span>
            <span class="menu-title">Tugas Akhir</span>
          </a>
        </li>
      @endif
      <li class="nav-item menu-items">
        <a class="nav-link" href="pages/icons/mdi.html">
          <span class="menu-icon">
            <i class="mdi mdi-key-variant"></i>
          </span>
          <span class="menu-title">Akun</span>
        </a>
      </li>
    @endif
  </ul>
</nav>