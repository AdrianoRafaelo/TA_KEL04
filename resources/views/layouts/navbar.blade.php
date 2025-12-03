<nav class="navbar p-0 fixed-top d-flex flex-row" style="background: linear-gradient(90deg, #1a1d2e 0%, #252837 100%) !important; border-bottom: 1px solid rgba(20, 151, 211, 0.2);">
  <div class="navbar-brand-wrapper d-flex d-lg-none align-items-center justify-content-center" style="background: linear-gradient(90deg, #1a1d2e 0%, #1e2130 100%) !important;">
    <a class="navbar-brand brand-logo-mini" href="index.html" style="color: #1497D3 !important;"><img src="assets/images/logo-mini.svg" alt="logo" style="filter: brightness(0) invert(1); opacity: 0.8;" /></a>
  </div>
  <div class="navbar-menu-wrapper flex-grow d-flex align-items-stretch">
    <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize" style="color: #e5e7eb !important; border: none; background: rgba(20, 151, 211, 0.1); transition: all 0.3s ease;">
      <span class="mdi mdi-menu"></span>
    </button>
    <ul class="navbar-nav navbar-nav-right">
      <li class="nav-item dropdown" style="border-left: 1px solid rgba(20, 151, 211, 0.2);">
        <a class="nav-link dropdown-toggle" id="userDropdown" href="#" data-toggle="dropdown" style="color: #e5e7eb !important;">
          <i class="mdi mdi-account-circle"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="userDropdown" style="background: rgba(26, 29, 46, 0.95) !important; border: 1px solid rgba(20, 151, 211, 0.3); backdrop-filter: blur(10px); min-width: 180px;">
          <div class="dropdown-header" style="color: #1497D3; font-weight: 600; padding: 12px 15px; border-bottom: 1px solid rgba(20, 151, 211, 0.2);">
            <i class="mdi mdi-account" style="margin-right: 8px;"></i>
            @php
              $user = \App\Models\FtiData::where('username', session('username'))->first();
              $displayName = $user ? $user->nama : (session('username') ?? 'User');
            @endphp
            {{ $displayName }}
          </div>
          <a class="dropdown-item" href="{{ url('/profile') }}" style="color: #e5e7eb; transition: all 0.3s ease; padding: 10px 15px;" onmouseover="this.style.background='rgba(20, 151, 211, 0.1)'" onmouseout="this.style.background='transparent'">
            <i class="mdi mdi-account-outline" style="color: #1497D3; margin-right: 10px;"></i>
            Profile
          </a>

          <div class="dropdown-divider" style="border-color: rgba(20, 151, 211, 0.2);"></div>
          <form action="{{ url('/logout') }}" method="POST" style="margin: 0;">
            @csrf
            <button type="submit" class="dropdown-item" style="color: #e5e7eb; background: none; border: none; width: 100%; text-align: left; cursor: pointer; transition: all 0.3s ease; padding: 10px 15px;" onmouseover="this.style.background='rgba(239, 68, 68, 0.1)'" onmouseout="this.style.background='transparent'">
              <i class="mdi mdi-logout" style="color: #ef4444; margin-right: 10px;"></i>
              Logout
            </button>
          </form>
        </div>
      </li>
    </ul>
    <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas" style="color: #e5e7eb !important; border: none; background: rgba(20, 151, 211, 0.1); transition: all 0.3s ease;">
      <span class="mdi mdi-format-line-spacing"></span>
    </button>
  </div>
</nav>

<style>
  /* Navbar additional styling */
  .navbar-toggler:hover {
    background: rgba(20, 151, 211, 0.2) !important;
    transform: scale(1.05);
  }

  .nav-link:hover {
    color: #1497D3 !important;
  }

  .count {
    animation: pulse 2s infinite;
  }

  @keyframes pulse {
    0% {
      box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
    }
    70% {
      box-shadow: 0 0 0 10px rgba(16, 185, 129, 0);
    }
    100% {
      box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
    }
  }

  .count.bg-danger {
    animation: pulseDanger 2s infinite;
  }

  @keyframes pulseDanger {
    0% {
      box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7);
    }
    70% {
      box-shadow: 0 0 0 10px rgba(239, 68, 68, 0);
    }
    100% {
      box-shadow: 0 0 0 0 rgba(239, 68, 68, 0);
    }
  }

  /* Dropdown menu animations */
  .dropdown-menu {
    animation: fadeInDown 0.3s ease-out;
  }

  @keyframes fadeInDown {
    from {
      opacity: 0;
      transform: translateY(-10px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }
</style>