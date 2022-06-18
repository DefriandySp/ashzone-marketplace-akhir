<ul class="nav navbar-nav center_nav pull-right">
    <li class="nav-item {{Request::path() == '/' ? 'active' : ''}}">
        <a class="nav-link" href="{{ route('front.index') }}">Beranda</a>
    </li>
    <li class="nav-item {{Request::path() == 'product' ? 'active' : ''}}">
        <a class="nav-link" href="{{ route('front.product') }}">List Produk</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="https://api.whatsapp.com/send?phone=6281382920681&amp;text=Halo%20gan,%20Saya%20butuh%20bantuan%20terkait%20">Bantuan</a>
    </li>
</ul>