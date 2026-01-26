<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <div class="sidebar-brand-wrapper d-none d-lg-flex align-items-center justify-content-center fixed-top">
    <a class="sidebarlogo brandlogo" href="index.html" style="display: flex; align-items: center; gap: 8px;">
      <img src="{{ asset('img/logoSIPODA2.png') }}" alt="SIPODA Logo" style="height: 32px; width: auto;">
      <span>SIPODA</span>
    </a>
    <a class="sidebar-brand brand-logo-mini" href="index.html">
      <img src="assets/images/logo-mini.svg" alt="logo" />
    </a>
  </div>
  <ul class="nav">
    <li class="nav-item profile">
      <div class="profile-desc" style="background: rgba(20, 151, 211, 0.08); padding: 15px; border-radius: 10px; margin-bottom: 10px; border: 1px solid rgba(20, 151, 211, 0.2); transition: all 0.3s ease;">
        <div class="profile-pic">
          <div class="count-indicator">
            <img class="img-lg rounded-circle" src="assets/images/faces/face15.jpg" alt="" style="border: 3px solid #28a745; box-shadow: 0 0 10px rgba(40, 167, 69, 0.5);">
            <span class="count bg-success online-indicator"></span>
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
      </div>
    </li>
    <!-- Breadcrumb Indicator -->
    <li class="nav-item breadcrumb-indicator" style="margin-bottom: 15px;">
      <div class="breadcrumb-container" style="padding: 8px 15px; background: rgba(20, 151, 211, 0.1); border-radius: 6px; border-left: 2px solid #1497D3;">
        <nav aria-label="breadcrumb" style="margin: 0;">
          <ol class="breadcrumb" style="background: none; padding: 0; margin: 0; font-size: 12px;">
            <li class="breadcrumb-item" style="color: #1497D3;">
              <i class="mdi mdi-home" style="font-size: 14px; margin-right: 4px;"></i>
              SIPODA
            </li>
            <li class="breadcrumb-item active" style="color: #e5e7eb;" aria-current="page">
              @php
                $currentPath = request()->path();
                $breadcrumbText = 'Dashboard';

                if (str_contains($currentPath, 'admin/roles')) {
                  $breadcrumbText = 'Kelola Role FTI';
                } elseif (str_contains($currentPath, 'pengumuman')) {
                  $breadcrumbText = 'Kelola Pengumuman';
                } elseif (str_contains($currentPath, 'matakuliah')) {
                  $breadcrumbText = 'Matakuliah';
                } elseif (str_contains($currentPath, 'fti-data')) {
                  $breadcrumbText = 'Data FTI';
                } elseif (str_contains($currentPath, 'mbkm')) {
                  $breadcrumbText = 'MBKM';
                } elseif (str_contains($currentPath, 'kerja-praktik')) {
                  $breadcrumbText = 'Kerja Praktik';
                } elseif (str_contains($currentPath, 'ta-')) {
                  $breadcrumbText = 'Tugas Akhir';
                } elseif (str_contains($currentPath, 'pages/icons')) {
                  $breadcrumbText = 'Akun';
                }
              @endphp
              {{ $breadcrumbText }}
            </li>
          </ol>
        </nav>
      </div>
    </li>

    <li class="nav-item nav-category" style="border-bottom: 1px solid #1497D3; padding-bottom: 5px; margin-bottom: 10px;">
      <span class="nav-link" style="color: #1497D3 !important; font-weight: 600;">Navigation</span>
    </li>
    <li class="nav-item menu-items">
      <a class="nav-link @if(request()->is('/')) active @endif" href="{{ url('/') }}" data-toggle="tooltip" data-placement="right" title="Dashboard">
        <span class="menu-icon">
          <span class="icon-bg bg-purple rounded-circle">
            <i class="mdi mdi-speedometer text-white"></i>
          </span>
        </span>
        <span class="menu-title">Dashboard</span>
      </a>
    </li>
    <li class="nav-item menu-items">
      <a class="nav-link @if(request()->is('repository*')) active @endif" href="{{ url('/repository') }}" data-toggle="tooltip" data-placement="right" title="E-Repository">
        <span class="menu-icon">
          <span class="icon-bg bg-success rounded-circle">
            <i class="mdi mdi-file-document text-white"></i>
          </span>
        </span>
        <span class="menu-title">E-Repository</span>
      </a>
    </li>
    @if (session('role') == 'Admin')
      <li class="nav-item menu-items">
        <a class="nav-link @if(request()->is('admin/roles*')) active @endif" href="{{ url('/admin/roles') }}">
          <span class="menu-icon">
            <span class="icon-bg bg-blue rounded-circle">
              <i class="mdi mdi-account-key text-white"></i>
            </span>
          </span>
          <span class="menu-title">Kelola Role FTI</span>
        </a>
      </li>

      <li class="nav-item menu-items">
        <a class="nav-link @if(request()->is('pengumuman*')) active @endif" href="{{ url('pengumuman') }}" data-toggle="tooltip" data-placement="right" title="Kelola Pengumuman">
          <span class="menu-icon">
            <span class="icon-bg bg-warning rounded-circle">
              <i class="mdi mdi-bullhorn text-white"></i>
            </span>
          </span>
          <span class="menu-title">Kelola Pengumuman</span>
          <span class="notification-badge pulse">3</span>
        </a>
      </li>

      <li class="nav-item menu-items">
        <a class="nav-link @if(request()->is('matakuliah*')) active @endif" href="{{ url('/matakuliah') }}">
          <span class="menu-icon">
            <span class="icon-bg bg-orange rounded-circle">
              <i class="mdi mdi-notebook text-white"></i>
            </span>
          </span>
          <span class="menu-title">Kelola Matakuliah</span>
        </a>
      </li>

    @endif
    @if (session('role') !== 'Admin' && session('role') !== 'Koordinator')
      <li class="nav-item menu-items">
        <a class="nav-link @if(request()->is('matakuliah*')) active @endif" href="{{ url('/matakuliah') }}">
          <span class="menu-icon">
            <span class="icon-bg bg-orange rounded-circle">
              <i class="mdi mdi-notebook text-white"></i>
            </span>
          </span>
          <span class="menu-title">Matakuliah</span>
        </a>
      </li>
      <li class="nav-item menu-items">
        <a class="nav-link @if(request()->is('fti-data*')) active @endif" href="{{ url('/fti-data') }}">
          <span class="menu-icon">
            <span class="icon-bg bg-info rounded-circle">
              <i class="mdi mdi-school text-white"></i>
            </span>
          </span>
          <span class="menu-title">Data FTI</span>
        </a>
      </li>
    @endif
      @if (session('role') === 'Mahasiswa')
<li class="nav-item menu-items">
  <a class="nav-link @if(request()->is('mbkm*')) active @endif" data-toggle="collapse" href="#mbkmCollapse" aria-expanded="false" aria-controls="mbkmCollapse">
    <span class="menu-icon">
      <span class="icon-bg bg-success rounded-circle">
        <i class="mdi mdi-security text-white"></i>
      </span>
    </span>
    <span class="menu-medal">MBKM</span>
    <i class="menu-arrow"></i>
  </a>
  <div class="collapse" id="mbkmCollapse">
    <ul class="nav flex-column sub-menu">
      <li class="nav-item sub-menu-item">
        <a class="nav-link" href="{{ url('/mbkm/informasi-mhs') }}">
          <span class="sub-icon"><i class="mdi mdi-handshake"></i></span>
          <span class="sub-title">Mitra</span>
        </a>
      </li>
      <li class="nav-item sub-menu-item">
        <a class="nav-link" href="{{ url('/mbkm/informasi-nonmitra') }}">
          <span class="sub-icon"><i class="mdi mdi-account-group"></i></span>
          <span class="sub-title">Non-Mitra</span>
        </a>
      </li>
    </ul>
  </div>
</li>

        <li class="nav-item menu-items">
          <a class="nav-link @if(request()->is('kerja-praktik*')) active @endif" href="{{ url('/kerja-praktik') }}">
            <span class="menu-icon">
              <span class="icon-bg bg-teal rounded-circle">
                <i class="mdi mdi-domain text-white"></i>
              </span>
            </span>
            <span class="menu-title">Kerja Praktik</span>
          </a>
        </li>
      @endif

      @if (session('role') === 'Dosen')
<li class="nav-item menu-items">
  <a class="nav-link @if(request()->is('mbkm*')) active @endif"
     href="{{ url('/mbkm/dosen-konversi-matkul') }}">
    <span class="menu-icon">
      <span class="icon-bg bg-success rounded-circle">
        <i class="mdi mdi-security text-white"></i>
      </span>
    </span>
    <span class="menu-medal">MBKM</span>
  </a>
</li>

        <li class="nav-item menu-items">
          <a class="nav-link @if(request()->is('kerja-praktik-dosen*')) active @endif" href="{{ url('/kerja-praktik-dosen') }}">
            <span class="menu-icon">
              <span class="icon-bg bg-teal rounded-circle">
                <i class="mdi mdi-domain text-white"></i>
              </span>
            </span>
            <span class="menu-title">Kerja Praktik</span>
          </a>
        </li>
        <li class="nav-item menu-items">
          <a class="nav-link @if(request()->is('ta-dosen*')) active @endif" href="{{ url('/ta-dosen') }}">
            <span class="menu-icon">
              <span class="icon-bg bg-danger rounded-circle">
                <i class="mdi mdi-file-document text-white"></i>
              </span>
            </span>
            <span class="menu-title">Tugas Akhir D</span>
          </a>
        </li>
      @endif
      <!-- Menu untuk Mahasiswa -->
      @if (session('role') === 'Mahasiswa')
        <li class="nav-item menu-items">
          <a class="nav-link @if(request()->is('ta-mahasiswa*')) active @endif" href="{{ url('/ta-mahasiswa') }}">
            <span class="menu-icon">
              <span class="icon-bg bg-danger rounded-circle">
                <i class="mdi mdi-file-document text-white"></i>
              </span>
            </span>
            <span class="menu-title">Tugas Akhir M</span>
          </a>
        </li>

      @endif


      @if (session('role') === 'Koordinator')
        <li class="nav-item menu-items">
          <a class="nav-link @if(request()->is('kerja-praktik-koordinator*')) active @endif" href="{{ url('/kerja-praktik-koordinator') }}">
            <span class="menu-icon">
              <span class="icon-bg bg-teal rounded-circle">
                <i class="mdi mdi-domain text-white"></i>
              </span>
            </span>
            <span class="menu-title">Kerja Praktik</span>
          </a>
        </li>
<li class="nav-item menu-items">
  <a class="nav-link @if(request()->is('mbkm/pendaftaran-koordinator')) active @endif"
     href="{{ url('/mbkm/pendaftaran-koordinator') }}">
    <span class="menu-icon">
      <span class="icon-bg bg-success rounded-circle">
        <i class="mdi mdi-security text-white"></i>
      </span>
    </span>
    <span class="menu-medal">MBKM</span>
  </a>
</li>

        <li class="nav-item menu-items">
          <a class="nav-link @if(request()->is('koordinator-pendaftaran*')) active @endif" href="{{ url('/koordinator-pendaftaran') }}">
            <span class="menu-icon">
              <span class="icon-bg bg-danger rounded-circle">
                <i class="mdi mdi-file-document text-white"></i>
              </span>
            </span>
            <span class="menu-title">Tugas Akhir</span>
          </a>
        </li>
      @endif
      @if (session('role') === 'Koordinator')
        <li class="nav-item menu-items">
          <a class="nav-link @if(request()->is('matakuliah*')) active @endif" href="{{ url('/matakuliah') }}">
            <span class="menu-icon">
              <span class="icon-bg bg-orange rounded-circle">
                <i class="mdi mdi-notebook text-white"></i>
              </span>
            </span>
            <span class="menu-title">Kelola Matakuliah</span>
          </a>
        </li>
      @endif
      <li class="nav-item menu-items">
        <a class="nav-link @if(request()->is('pages/icons*')) active @endif" href="pages/icons/mdi.html">
          <span class="menu-icon">
            <span class="icon-bg bg-secondary rounded-circle">
              <i class="mdi mdi-key-variant text-white"></i>
            </span>
          </span>
          <span class="menu-title">Akun</span>
        </a>
      </li>
  </ul>
</nav>

<style>
  /* Sidebar background */
  .sidebar {
    background: linear-gradient(180deg, #1a1d2e 0%, #252837 100%) !important;
  }

  /* Sidebar brand/logo styling */
  .sidebar-brand-wrapper {
    background: linear-gradient(180deg, #1a1d2e 0%, #252837 100%) !important;
    border-bottom: 1px solid rgba(20, 151, 211, 0.2);
    min-height: 60px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  }

  /* Make brand wrapper consistent with sidebar width */
  .sidebar.sidebar-offcanvas ~ .sidebar-brand-wrapper {
    width: 260px;
    transition: width 0.3s ease;
  }

  .sidebar.sidebar-offcanvas.sidebar-collapsed ~ .sidebar-brand-wrapper {
    width: 70px;
  }

  .sidebarlogo.brandlogo {
    color: #1497D3 !important;
    font-weight: 700;
    font-size: 18px;
    text-decoration: none;
    transition: all 0.3s ease;
  }

  .sidebarlogo.brandlogo:hover {
    color: #0ea5e9 !important;
    transform: scale(1.05);
  }

  .sidebar-brand.brand-logo-mini img {
    filter: brightness(0) invert(1);
    opacity: 0.8;
    transition: all 0.3s ease;
  }

  .sidebar-brand.brand-logo-mini:hover img {
    opacity: 1;
    transform: scale(1.1);
  }

  /* Text color */
  .nav-link, .nav-link span {
    color: #e5e7eb !important;
  }

  /* Tampilkan dropdown saat hover */
.nav-item.dropdown:hover .dropdown-menu {
  display: block !important;
  margin-top: 0; /* supaya tidak loncat */
}

/* Online indicator animation */
.online-indicator {
  animation: pulse 2s infinite;
}

@keyframes pulse {
  0% {
    box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.7);
  }
  70% {
    box-shadow: 0 0 0 10px rgba(40, 167, 69, 0);
  }
  100% {
    box-shadow: 0 0 0 0 rgba(40, 167, 69, 0);
  }
}

/* Increase spacing between menu items */
.nav-item.menu-items {
  margin-bottom: 8px;
}

/* Icon backgrounds */
.icon-bg {
  padding: 6px;
  display: inline-block;
  border-radius: 8px;
  margin-right: 10px;
}

.icon-bg i {
  font-size: 18px;
}

/* Active menu glow effect */
.nav-link.active .icon-bg {
  box-shadow: 0 0 15px rgba(255, 255, 255, 0.3);
}

/* Active menu styling */
.nav-link.active {
  background: rgba(20, 151, 211, 0.15);
  color: #fff !important;
  padding: 12px 15px;
  position: relative;
}

.nav-link.active::before {
  content: '';
  position: absolute;
  left: 0;
  top: 0;
  bottom: 0;
  width: 4px;
  background: #1497D3;
  border-radius: 0 2px 2px 0;
}

/* Hover effects */
.nav-link:hover {
  background: rgba(255, 255, 255, 0.05);
  transform: translateX(-3px) scale(1.01);
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.nav-link {
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  padding: 10px 15px;
}

/* Submenu styling */
.sub-menu {
  background: rgba(255, 255, 255, 0.05);
  border-radius: 8px;
  margin-top: 5px;
  padding: 10px 0;
  position: relative;
}

.sub-menu::before {
  content: '';
  position: absolute;
  left: 20px;
  top: 0;
  bottom: 0;
  width: 2px;
  background: #1497D3;
}

.sub-menu-item {
  position: relative;
  margin-left: 30px;
}

.sub-menu-item::before {
  content: '';
  position: absolute;
  left: -10px;
  top: 50%;
  transform: translateY(-50%);
  width: 8px;
  height: 2px;
  background: #1497D3;
}

.sub-menu-item .nav-link {
  padding: 8px 15px;
  color: #e5e7eb;
  font-size: 14px;
}

.sub-menu-item .nav-link:hover {
  color: #fff;
  background: rgba(255, 255, 255, 0.05);
  transform: none;
}

.sub-icon {
  display: inline-block;
  width: 20px;
  text-align: center;
  margin-right: 10px;
  color: #1497D3;
}

.sub-title {
  vertical-align: middle;
}

/* Smooth collapse animation */
.collapse {
  transition: height 0.3s ease-out, opacity 0.3s ease-out;
}

/* Smooth scroll behavior */
html {
  scroll-behavior: smooth;
}

/* Notification badges */
.notification-badge {
  position: absolute;
  right: 10px;
  top: 50%;
  transform: translateY(-50%);
  background: #ff4757;
  color: white;
  border-radius: 50%;
  width: 18px;
  height: 18px;
  font-size: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
}

.pulse {
  animation: notificationPulse 2s infinite;
}

@keyframes notificationPulse {
  0% {
    transform: translateY(-50%) scale(1);
    box-shadow: 0 0 0 0 rgba(255, 71, 87, 0.7);
  }
  70% {
    transform: translateY(-50%) scale(1.1);
    box-shadow: 0 0 0 10px rgba(255, 71, 87, 0);
  }
  100% {
    transform: translateY(-50%) scale(1);
    box-shadow: 0 0 0 0 rgba(255, 71, 87, 0);
  }
}

/* Ripple effect */
.nav-link {
  position: relative;
  overflow: hidden;
}

.nav-link::before {
  content: '';
  position: absolute;
  top: 50%;
  left: 50%;
  width: 0;
  height: 0;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.3);
  transform: translate(-50%, -50%);
  transition: width 0.6s, height 0.6s;
}

.nav-link:active::before {
  width: 300px;
  height: 300px;
}

/* Profile section hover effect */
.profile-desc:hover {
  background: rgba(20, 151, 211, 0.12) !important;
  border-color: rgba(20, 151, 211, 0.4) !important;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(20, 151, 211, 0.15);
}

/* Dropdown item hover effects */
.dropdown-item:hover {
  background: rgba(139, 92, 246, 0.1) !important;
  color: #fff !important;
}

/* Tooltip styling */
.tooltip {
  font-size: 12px;
}

.tooltip-inner {
  background: #1497D3;
  color: white;
  border-radius: 4px;
}

.tooltip-arrow::before {
  border-right-color: #1497D3 !important;
}

</style>


@section('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();

    // Custom dropdown functionality
    const dropdownToggle = document.querySelector('.profile-dropdown-toggle');
    const dropdownMenu = document.querySelector('.profile-dropdown-menu');
    let dropdownTimeout;

    if (dropdownToggle && dropdownMenu) {
      // Toggle dropdown on click
      dropdownToggle.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();

        const isVisible = dropdownMenu.style.display === 'block';
        dropdownMenu.style.display = isVisible ? 'none' : 'block';
      });

      // Close dropdown when clicking outside
      document.addEventListener('click', function(e) {
        if (!dropdownToggle.contains(e.target) && !dropdownMenu.contains(e.target)) {
          dropdownMenu.style.display = 'none';
        }
      });

      // Optional: Close on ESC key
      document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
          dropdownMenu.style.display = 'none';
        }
      });

      // Smooth hover effects for dropdown items
      const dropdownItems = dropdownMenu.querySelectorAll('.dropdown-item');
      dropdownItems.forEach(item => {
        item.addEventListener('mouseenter', function() {
          this.style.background = 'rgba(139, 92, 246, 0.1)';
          this.style.transform = 'translateX(5px)';
        });

        item.addEventListener('mouseleave', function() {
          this.style.background = 'none';
          this.style.transform = 'translateX(0)';
        });
      });
    }
  });
</script>
@endsection