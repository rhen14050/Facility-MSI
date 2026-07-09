<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="{{ route('dashboard') }}" class="brand-link">
    <!-- <center> -->
    <img src="{{ asset('public/images/favicon3.png') }}"
         alt="PMI Logo"
         class="brand-image img-circle elevation-3">
      <!-- <i class="fa fa-home"></i> -->
    <span class="brand-text font-weight-light">Facility MSI Inventory</span>
    <!-- </center> -->
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
             with font-awesome or any other icon font library -->
        <li class="nav-item has-treeview">
          <a href="../RapidX/" class="nav-link">
            <i class="nav-icon fas fa-arrow-left"></i>
            <p>
              Return to RapidX
            </p>
          </a>
        </li>

        <li class="nav-item has-treeview">
          <a href="{{ route('dashboard') }}" class="nav-link">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>
              Dashboard
            </p>
          </a>
        </li>

        <!-- <li class="nav-item has-treeview">
          <a href="{{ route('parts') }}" class="nav-link">
            <i class="nav-icon fas fa-file-excel"></i>
            <p>
              Reports
            </p>
          </a>
        </li> -->

        <li class="nav-item has-treeview">
          <a href="{{ route('inventoriesv2') }}" class="nav-link">
            <i class="nav-icon fas fa-list"></i>
            <p>
              Inventory
            </p>
          </a>
        </li>

      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>