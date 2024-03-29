@extends('layouts.admin')

@section('title')
<title>Laporan Order</title>
@endsection

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Orders</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Laporan Order</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container">
            <div class="row">
                <!-- BAGIAN INI AKAN MENG-HANDLE TABLE LIST PRODUCT  -->
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Laporan Order</h4>
                        </div>
                        <div class="card-body">
                            @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                            @endif

                            @if (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif

                            <form action="{{ route('report.brand-order') }}" method="get">
                                <div class="input-group mb-3 col-md-4 float-right">
                                    <input type="text" id="created_at" name="date" class="form-control">
                                    <div class="input-group-append">
                                        <button class="btn btn-secondary" type="submit">Filter</button>
                                    </div>
                                    <a target="_blank" class="btn btn-primary ml-2" id="exportpdf">Export PDF</a>
                                </div>
                            </form>
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered">
                                    <thead>
                                        <tr>
                                            <th>InvoiceID</th>
                                            <th>Pelanggan</th>
                                            <th>Produk</th>
                                            <th>Brand</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                            <th>Tanggal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($orders as $row)
                                        <tr>
                                            <td><strong>{{ $row->order->invoice }}</strong></td>
                                            <td>
                                                <strong>{{ $row->order->customer_name }}</strong><br>
                                                <div class="small">
                                                    <strong>Telp:</strong> {{ $row->order->customer_phone }}<br>
                                                    <strong>Alamat:</strong> {{ $row->order->customer_address }} <br> {{ $row->order->customer->city->name }}, {{ $row->order->customer->city->province->name }}<br>
                                                </div>
                                            </td>
                                            <td>
                                                <div>{{$row->product->name??''}} x {{$row->qty}}</div>
                                            </td> 
                                            <td>
                                                <div>{{$row->product->brand->name??''}}</div>
                                            </td> 
                                            
                                            <td>Rp {{ number_format($row->price * $row->qty) }}</td>
                                            <td>{!! $row->order->status_label !!}</td>
                                            <td>{{ $row->order->created_at->format('d-m-Y') }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center">Tidak ada data</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- BAGIAN INI AKAN MENG-HANDLE TABLE LIST CATEGORY  -->
            </div>
        </div>
    </section>
</div>
<!-- /.content-wrapper -->
@endsection

@section('js')
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script>
    $(document).ready(function() {
        @if(!empty($start))
        let start = moment('{{$start}}')
        let end = moment('{{$end}}')
        @else
        let start = moment().startOf('month')
        let end = moment().endOf('month')
        @endif

        $('#exportpdf').attr('href', '<?=url('')?>/administrator/reports/reportbrandorder/' + start.format('YYYY-MM-DD') + '+' + end.format('YYYY-MM-DD'))

        $('#created_at').daterangepicker({
            startDate: start,
            endDate: end
        }, function(first, last) {

            $('#exportpdf').attr('href', '<?=url('')?>/administrator/reports/reportbrandorder/' + first.format('YYYY-MM-DD') + '+' + last.format('YYYY-MM-DD'))
        })
    })
</script>
@endsection