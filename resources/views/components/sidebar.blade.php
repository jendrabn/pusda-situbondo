<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a class="brand-link" href="javascript:;">
    <img class="brand-text img-fluid" src="{{ asset('img/logo.png') }}" alt="Logo" />
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel d-flex mt-3 mb-3 pb-3">
      <div class="image">
        <img class="img-circle elevation-2" src="{{ auth()->user()->photo }}" alt="User Image" />
      </div>
      <div class="info">
        <a class="d-block"
          href="{{ route('profile') }}">{{ Str::limit(Str::words(auth()->user()->name, 2, ''), 15, '.') }}</a>
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav>
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" data-accordion="false" role="menu">

        @role('Administrator')
          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
              href="{{ route('admin.dashboard') }}">
              <i class="fas fa-tachometer-alt nav-icon"></i>
              <p>Dashboard</p>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.bps.*') ? 'active' : '' }}"
              href="{{ route('admin.bps.index') }}">
              <i class="fas fa-th-large nav-icon"></i>
              <p>BPS</p>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.rpjmd.*') ? 'active' : '' }}" href="#">
              <i class="nav-icon fas fa-briefcase"></i>
              <p>
                RPJMD
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              @foreach (\App\Models\KategoriSkpd::pluck('nama', 'id') as $id => $nama)
                <li class="nav-item">
                  <a class="nav-link {{ request()->is('admin/rpjmd/' . $id) ? 'active' : '' }}"
                    href="{{ route('admin.rpjmd.index', $id) }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>{{ $nama }}</p>
                  </a>
                </li>
              @endforeach
              <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/rpjmd') ? 'active' : '' }}"
                  href="{{ route('admin.rpjmd.index') }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>RPJMD</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.delapankeldata.*') ? 'active' : '' }}" href="#">
              <i class="nav-icon fas fa-book"></i>
              <p>
                8 Kel. Data
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              @foreach (\App\Models\KategoriSkpd::pluck('nama', 'id') as $id => $nama)
                <li class="nav-item">
                  <a class="nav-link {{ request()->is('admin/delapankeldata/category/' . $id) ? 'active' : '' }}"
                    href="{{ route('admin.delapankeldata.category', $id) }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>{{ $nama }}</p>
                  </a>
                </li>
              @endforeach
              <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/delapankeldata') ? 'active' : '' }}"
                  href="{{ route('admin.delapankeldata.index') }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>8 Kel. Data</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.indikator.*') ? 'active' : '' }}"
              href="{{ route('admin.indikator.index') }}">
              <i class="fas fa-layer-group nav-icon"></i>
              <p>Indikator</p>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
              href="{{ route('admin.users.index') }}">
              <i class="fas fa-users nav-icon"></i>
              <p>Users</p>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.treeview.*') || request()->routeIs('admin.uraian.*') ? 'active' : '' }}"
              href="#">
              <i class="nav-icon fas fa-table"></i>
              <p>
                Master Tabel
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul
              class="nav nav-treeview {{ request()->routeIs('admin.treeview.*') || request()->routeIs('admin.uraian.*') ? 'd-block' : '' }}">
              <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.treeview.delapankeldata.*') ? 'active' : '' }}"
                  href="{{ route('admin.treeview.delapankeldata.index') }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Menu 8 Kel. Data</p>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.treeview.rpjmd.*') ? 'active' : '' }}"
                  href="{{ route('admin.treeview.rpjmd.index') }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Menu RPJMD</p>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.treeview.indikator.*') ? 'active' : '' }}"
                  href="{{ route('admin.treeview.indikator.index') }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Menu Indikator</p>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.treeview.bps.*') ? 'active' : '' }}"
                  href="{{ route('admin.treeview.bps.index') }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Menu BPS</p>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.uraian.delapankeldata.*') ? 'active' : '' }}"
                  href="{{ route('admin.uraian.delapankeldata.index') }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Form 8 Kel. Data</p>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.uraian.rpjmd.*') ? 'active' : '' }}"
                  href="{{ route('admin.uraian.rpjmd.index') }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Form RPJMD</p>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.uraian.indikator.*') ? 'active' : '' }}"
                  href="{{ route('admin.uraian.indikator.index') }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Form Indikator</p>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.uraian.bps.*') ? 'active' : '' }}"
                  href="{{ route('admin.uraian.bps.index') }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Form BPS</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.skpd.*') ? 'active' : '' }}"
              href="{{ route('admin.skpd.index') }}">
              <i class="fas fa-briefcase nav-icon"></i>
              <p>SKPD</p>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link {{ request()->is('admin/audit-logs') || request()->is('admin/audit-logs/*') ? 'active' : '' }}"
              href="{{ route('admin.audit-logs.index') }}">
              <i class="nav-icon fas fa-file-alt">
              </i>
              <p>
                Audit Logs
              </p>
            </a>
          </li>
        @endrole

        @role('SKPD')
          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin_skpd.dashboard') ? 'active' : '' }}"
              href="{{ route('admin_skpd.dashboard') }}">
              <i class="fas fa-tachometer-alt nav-icon"></i>
              <p>Dashboard</p>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin_skpd.delapankeldata.*') ? 'active' : '' }}"
              href="{{ route('admin_skpd.delapankeldata.index') }}">
              <i class="fas fa-book nav-icon"></i>
              <p>8 Kel. Data</p>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin_skpd.rpjmd.*') ? 'active' : '' }}"
              href="{{ route('admin_skpd.rpjmd.index') }}">
              <i class="fas fa-briefcase nav-icon"></i>
              <p>RPJMD</p>
            </a>
          </li>
        @endrole

        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('profile') ? 'active' : '' }}" href="{{ route('profile') }}">
            <i class="fas fa-user nav-icon"></i>
            <p>Profil</p>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fas fa-sign-out-alt nav-icon"></i>
            <p>Logout</p>
            <form class="d-none" id="logout-form" action="{{ route('logout') }}" method="POST">
              @csrf
            </form>
          </a>
        </li>
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>
