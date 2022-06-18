  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('home') }}" class="brand-link">
        <img src="{{ asset('admin-lte/dist/img/zonedicon.png')}}" alt="AdminAsh Logo" class="brand-image img-circle elevation-3"
            style="opacity: .8">
        <span class="brand-text font-weight-light">Admin Dashboard</span>
    </a>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->

        <!-- Sidebar Menu -->
        <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Add icons to the links using the .nav-icon class
                with font-awesome or any other icon font library -->
            <li class="nav-item">
                <a href="{{ route('home') }}" class="nav-link {{Request::path() == 'administrator/home' ? 'active' : ''}}">
                    <i class="nav-icon fas fa-home"></i>
                    <p>Dashboard</p>
                </a>
            </li>
            <li class="nav-header">MANAJEMEN PRODUK</li>
            <li class="nav-item">
                <a href="{{ route('category.index') }}" class="nav-link {{Request::path() == 'administrator/category' ? 'active' : ''}}">
                    <i class="nav-icon fas fa-th"></i>
                    <p>Kategori Brand</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('product.index') }}" class="nav-link {{Request::path() == 'administrator/product' ? 'active' : ''}}">
                    <i class="nav-icon fas fa-th"></i>
                    <p>Daftar Produk</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('orders.index') }}" class="nav-link {{Request::path() == 'administrator/orders' ? 'active' : ''}}">
                    <i class="nav-icon fas fa-th"></i>
                    <p>Daftar Pesanan</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('report.order') }}" class="nav-link {{Request::path() == 'administrator/reports/order' ? 'active' : ''}}">
                    <i class="nav-icon fas fa-book"></i>
                    <p>Laporan Order</p>
                </a>
            </li>
                </ul>
            </li>
            
        </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
  <!-- Main Sidebar Container -->