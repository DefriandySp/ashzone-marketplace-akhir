<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Order</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<body>
    <h5>Laporan Order Periode Brand {{$brand->name}} ({{ $date[0] }} - {{ $date[1] }})</h5>
    <hr>
    <table width="100%" class="table-hover table-bordered">
        <thead>
            <tr>
                <th>InvoiceID</th>
                <th>Pelanggan</th>
                <th>Produk</th>
                <th>Total</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @php $total = 0; @endphp
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
                    
                    <td>Rp {{ number_format($row->price * $row->qty) }}</td>
                    <td>{{ $row->order->created_at->format('d-m-Y') }}</td>
                </tr>

                @php $total += ($row->price * $row->qty) @endphp
            @empty
            <tr>
                <td colspan="6" class="text-center">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3">Total</td>
                <td>Rp {{ number_format($total) }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>
</body>
</html>