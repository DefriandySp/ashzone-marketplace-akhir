@extends('layouts.ecommerce')

@section('title')
<title>Checkout - AshZone</title>
@endsection

@section('content')
<!--================Home Banner Area =================-->
<section class="banner_area">
    <div class="banner_inner d-flex align-items-center">
        <div class="overlay"></div>
        <div class="container">
            <div class="banner_content text-center">
                <h2>Informasi Pengiriman</h2>
                <div class="page_link">
                    <a href="{{ url('/') }}">Home</a>
                    <a href="#">Checkout</a>
                </div>
            </div>
        </div>
    </div>
</section>
<!--================End Home Banner Area =================-->

<!--================Checkout Area =================-->
<section class="checkout_area section_gap">
    <div class="container">
        <div class="billing_details">
            <div class="row">
                <div class="col-lg-8">
                    <h3>Informasi Pengiriman</h3>
                    @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form class="row contact_form" action="{{ route('front.store_checkout') }}" method="post"
                        novalidate="novalidate">
                        @csrf
                        <div class="col-md-12 form-group p_star">
                            <label for="">Nama Penerima</label>
                            @if (auth()->guard('customer')->check())
                            <input type="name" class="form-control" id="first" name="costumer_name"
                                value="{{ auth()->guard('customer')->user()->name }}" required
                                {{ auth()->guard('customer')->check() ? 'readonly':'' }}>
                            @else
                            <input type="email" class="form-control" id="email" name="email" required>
                            @endif
                            <p class="text-danger">{{ $errors->first('email') }}</p>
                        </div>
                        <div class="col-md-6 form-group p_star">
                            <label for="">Nomor HP</label>
                            @if (auth()->guard('customer')->check())
                            <input type="nohp" class="form-control" id="first" name="phone_number"
                                value="{{ auth()->guard('customer')->user()->phone_number }}" required
                                {{ auth()->guard('customer')->check() ? 'readonly':'' }}>
                            @else
                            <input type="email" class="form-control" id="email" name="email" required>
                            @endif
                            <p class="text-danger">{{ $errors->first('email') }}</p>
                        </div>
                        <!-- <div class="col-md-6 form-group p_star">
                            <label for="">No Telepon</label>
                            <input type="text" class="form-control" id="number" name="customer_phone" required>
                            <p class="text-danger">{{ $errors->first('customer_phone') }}</p>
                        </div> -->
                        <div class="col-md-6 form-group p_star">
                            <label for="">Email</label>
                            @if (auth()->guard('customer')->check())
                            <input type="email" class="form-control" id="email" name="email"
                                value="{{ auth()->guard('customer')->user()->email }}" required
                                {{ auth()->guard('customer')->check() ? 'readonly':'' }}>
                            @else
                            <input type="email" class="form-control" id="email" name="email" required>
                            @endif
                            <p class="text-danger">{{ $errors->first('email') }}</p>
                        </div>
                        <div class="col-md-12 form-group p_star">
                            <label for="">Alamat Lengkap</label>
                            <input type="text" class="form-control" id="add1" name="customer_address" required>
                            <p class="text-danger">{{ $errors->first('customer_address') }}</p>
                        </div>
                        <div class="col-md-12 form-group p_star">
                            <label for="">Propinsi</label>
                            <select class="form-control" name="province_id" id="province_id" required>
                                <option value="">Pilih Propinsi</option>
                                <!-- LOOPING DATA PROVINCE UNTUK DIPILIH OLEH CUSTOMER -->
                                @if(!empty($provinces['rajaongkir']['results']))
                                @for($i = 0; $i< count($provinces['rajaongkir']['results']); $i++) <option
                                    value="{{ $provinces['rajaongkir']['results'][$i]['province_id'] }}">
                                    {{ $provinces['rajaongkir']['results'][$i]['province'] }}</option>
                                    @endfor
                                    @endif
                            </select>
                            <p class="text-danger">{{ $errors->first('province_id') }}</p>
                        </div>

                        <!-- ADAPUN DATA KOTA DAN KECAMATAN AKAN DI RENDER SETELAH PROVINSI DIPILIH -->
                        <div class="col-md-12 form-group p_star">
                            <label for="">Kabupaten / Kota</label>
                            <select class="form-control" name="city_id" id="city_id" required>
                                <option value="">Pilih Kabupaten/Kota</option>
                            </select>
                            <p class="text-danger">{{ $errors->first('city_id') }}</p>
                        </div>
                        <!-- <div class="col-md-12 form-group p_star">
                            <label for="">Kecamatan</label>
                            <select class="form-control" name="district_id" id="district_id" required>
                                <option value="">Pilih Kecamatan</option>
                            </select>
                            <p class="text-danger">{{ $errors->first('district_id') }}</p>
                        </div> -->
                        <div class="col-md-12 form-group p_star">
                            <label for="">Kurir</label>
                            <input type="hidden" name="weight" id="weight" value="{{ $weight }}">
                            <select class="form-control" name="courier" id="courier" required>
                                <option value="">Pilih Kurir</option>
                            </select>
                            <p class="text-danger">{{ $errors->first('courier') }}</p>
                        </div>
                        <!-- ADAPUN DATA KOTA DAN KECAMATAN AKAN DI RENDER SETELAH PROVINSI DIPILIH -->

                </div>
                <div class="col-lg-4">
                    <div class="order_box">
                        <h2>Ringkasan Pesanan</h2>
                        <ul class="list">
                            <li>
                                <a href="#">Product
                                    <span>Total</span>
                                </a>
                            </li>
                            @foreach ($carts as $cart)
                            <li>
                                <a href="#">{{ \Str::limit($cart['product_name'], 10) }}
                                    <span class="middle">x {{ $cart['qty'] }}</span>
                                    <span class="last">Rp {{ number_format($cart['product_price']) }}</span>
                                </a>
                            </li>
                            @endforeach
                        </ul>
                        <ul class="list list_2">
                            <li>
                                <a href="#">Subtotal
                                    <span>Rp {{ number_format($subtotal) }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">Pengiriman
                                    <span id="ongkir">Rp 0</span>
                                    <input type="hidden" id="cost" name="cost">
                                </a>
                            </li>
                            <li>
                                <a href="#">Total
                                    <span id="total">Rp {{ number_format($subtotal) }}</span>
                                    <input type="hidden" name="total_all" id="total_all" value="{{ $subtotal }}">
                                </a>
                            </li>
                        </ul>
                        <button class="main_btn">Bayar Pesanan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--================End Checkout Area =================-->
@endsection

@section('js')
<script>
//KETIKA SELECT BOX DENGAN ID province_id DIPILIH
$('#province_id').on('change', function() {
    //MAKA AKAN MELAKUKAN REQUEST KE URL /API/CITY
    //DAN MENGIRIMKAN DATA PROVINCE_ID
    $.ajax({
        url: "{{ url('/api/city') }}",
        type: "GET",
        data: {
            province_id: $(this).val()
        },
        success: function(html) {
            //SETELAH DATA DITERIMA, SELEBOX DENGAN ID CITY_ID DI KOSONGKAN
            $('#city_id').empty()
            $('#courier').empty()

            //KEMUDIAN APPEND DATA BARU YANG DIDAPATKAN DARI HASIL REQUEST VIA AJAX
            //UNTUK MENAMPILKAN DATA KABUPATEN / KOTA
            $('#courier').append('<option value="">Pilih Kurir</option>')
            $('#city_id').append('<option value="">Pilih Kabupaten/Kota</option>')
            $.each(html.data, function(key, item) {
                $('#city_id').append('<option value="' + item.city_id + '">' + item
                    .city_name +
                    '</option>')
            })
        }
    });
})

//LOGICNYA SAMA DENGAN CODE DIATAS HANYA BERBEDA OBJEKNYA SAJA
$('#city_id').on('change', function() {
    $('#courier').empty()
  
    $('#courier').append('<option value="">Pilih Kurir</option>');
    $('#courier').append('<option value="jne">JNE</option>');
    $('#courier').append('<option value="pos">POS INDONESIA</option>');
    $('#courier').append('<option value="tiki">TIKI</option>');

})

$('#courier').on('change', function() {
    // ini city from diganti ya def pakai sesseion utk mainkan data nya data distric di tb_costumer diganti ke city karna ongkir batas di citu 
    var city_from = 93;
    // endd

    var city_id = $('#city_id').val();
    var sub_total = $('#total_all').val();

    $.ajax({
        url: "{{ url('/api/cost') }}",
        type: "POST",
        data: {
            city_from: city_from,
            city_id: city_id,
            weight: $('#weight').val(),
            courier: $('#courier').val()
        },
        success: function(html) {
            $.each(html.data, function(key, item) {
                $('#cost').val(item.costs[0].cost[0].value);
                $('#ongkir').html("Rp " + item.costs[0].cost[0].value);
                var total = parseInt(sub_total) + parseInt(item.costs[0].cost[0].value);
                $('#total').html("Rp " + total);
            })
        }
    });
})
</script>
@endsection