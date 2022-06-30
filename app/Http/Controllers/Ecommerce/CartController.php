<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Product;
use App\Province;
use App\City;
use App\Customer;
use App\Order;
use App\OrderDetail;
use Illuminate\Support\Str;
use DB;
use App\Mail\CustomerRegisterMail;
use Illuminate\Support\Facades\Auth;
use Mail;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rules\Unique;

class CartController extends Controller
{
    private function getCarts()
    {
        $carts = json_decode(request()->cookie('e-carts'), true);
        $carts = $carts != '' ? $carts:[];
        return $carts;
    }

    public function addToCart(Request $request)
    {
        $this->validate($request, [
            'product_id' => 'required|exists:products,id', 
            'qty' => 'required|integer' 
        ]);

        $carts = json_decode($request->cookie('e-carts'), true); 
    
        if ($carts && array_key_exists($request->product_id, $carts)) {
            $carts[$request->product_id]['qty'] += $request->qty;
        } else {
            $product = Product::find($request->product_id);
            $carts[$request->product_id] = [
                'qty' => $request->qty,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'product_price' => $product->price,
                'product_image' => $product->image,
                'weight' => $product->weight
            ];
        }

        $cookie = cookie('e-carts', json_encode($carts), 2880);
        return redirect()->back()->with(['success' => 'Produk Telah Ditambahkan ke Keranjang'])->cookie($cookie);
    }

    public function listCart()
    {
        $carts = $this->getCarts();
        $subtotal = collect($carts)->sum(function($q) {
            return $q['qty'] * $q['product_price']; 
        });

        return view('ecommerce.cart', compact('carts', 'subtotal'));
    }

    public function updateCart(Request $request)
    {
        $carts = $this->getCarts();

        if($request->product_id == ''){
            return redirect()->route('front.product');
        }else{
            foreach ($request->product_id as $key => $row) {
                if ($request->qty[$key] == 0) {
                    unset($carts[$row]);
                } else {
                    $carts[$row]['qty'] = $request->qty[$key];
                }
            }
            $cookie = cookie('e-carts', json_encode($carts), 2880);
            return redirect()->route('front.product')->cookie($cookie);
        }
    }

    public function getCourier(Request $request)
    {
        $input = $request->all();
       $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.rajaongkir.com/starter/cost",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_SSL_VERIFYPEER => 0,
             CURLOPT_POSTFIELDS => "origin=".$input['city_from']."&destination=".$input['city_id']."&weight=".$input['weight']."&courier=".$input['courier']."",
            CURLOPT_HTTPHEADER => array(
                "content-type: application/x-www-form-urlencoded",
                "key: 4e06fbae528e77b0fb70b9919fe96891"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        $cost = json_decode($response,TRUE);
        
        if($cost['rajaongkir']['status']['code'] == 200){
            return response()->json(['status' => 'success', 'data' => $cost['rajaongkir']['results']]);
        }else{
            return response()->json(['status' => 'failed', 'data' => "data tidak ada"]);
        }
    }

    public function checkout()
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.rajaongkir.com/starter/province",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HTTPHEADER => array(
                "key: 4e06fbae528e77b0fb70b9919fe96891"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        $provinces = json_decode($response,TRUE);
        $carts = $this->getCarts(); 
        $subtotal = collect($carts)->sum(function($q) {
            return $q['qty'] * $q['product_price'];
        });
        $weight = collect($carts)->sum(function($q) {
            return $q['qty'] * $q['weight'];
        });
        if (!auth()->guard('customer')->check()){
            return redirect()->route('customer.login')->with(['error' => 'Anda Belum Login, Silahkan Login Terlebih Dahulu']);
            // var_dump('auth::guest');
        }
        return view('ecommerce.checkout', compact('provinces', 'carts', 'subtotal', 'weight'));
    }

    public function getProvince(Request $request)
    {
        $input = $request->all();
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.rajaongkir.com/starter/province?id=".$input['province_id']."",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HTTPHEADER => array(
                "key: 4e06fbae528e77b0fb70b9919fe96891"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        $city = json_decode($response,TRUE);
        if($city['rajaongkir']['status']['code'] == 200){
            return response()->json(['status' => 'success', 'data' => $city['rajaongkir']['results']]);
        }else {
            return response()->json(['status' => 'failed', 'data' =>"data tidak ada"]);

        }
    }

    public function getCity(Request $request)
    {
        $input = $request->all();
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.rajaongkir.com/starter/city?province=".$input['province_id']."",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HTTPHEADER => array(
                "key: 4e06fbae528e77b0fb70b9919fe96891"
            ),
        ));

       $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        $city = json_decode($response,TRUE);
        if($city['rajaongkir']['status']['code'] == 200){
            return response()->json(['status' => 'success', 'data' => $city['rajaongkir']['results']]);
        }else {
            return response()->json(['status' => 'failed', 'data' =>"data tidak ada"]);

        }
    }

    public function processCheckout(Request $request)
    {
        $this->validate($request, [
            'customer_name' => 'required|string|max:100',
            'customer_phone' => 'required',
            'email' => 'required|email',
            'customer_address' => 'required|string',
            'province_id' => 'required',
            'city_id' => 'required',
            'courier' => 'required'
        ]);


        $input = $request->all(); 
        //DATABASE TRANSACTION BERFUNGSI UNTUK MEMASTIKAN SEMUA PROSES SUKSES UNTUK KEMUDIAN DI COMMIT AGAR DATA BENAR BENAR DISIMPAN, JIKA TERJADI ERROR MAKA KITA ROLLBACK AGAR DATANYA SELARAS
        DB::beginTransaction();
        try {
            $customer = Customer::where('email', $input['email'])->first();
            //JIKA DIA TIDAK LOGIN DAN DATA EMAIL CUSTOMERNYA ADA
            if (!auth()->guard('customer')->check() && $customer) {
                return redirect('/member/login')->with(['error' => 'Email Anda Telah Terdaftar, Silahkan Login Terlebih Dahulu']);
            }

            $carts = $this->getCarts();
            
            $subtotal = collect($carts)->sum(function($q) {
                return $q['qty'] * $q['product_price'];
            });

            if (!auth()->guard('customer')->check()) {
                $password = Str::random(8); 
                $customer = Customer::create([
                    'name' => $input['customer_name'],
                    'email' => $input['email'],
                    'password' => $password, 
                    'phone_number' => $input['customer_phone'],
                    'address' => $input['customer_address'],
                    'city_id' => $input['city_id'],
                    'activate_token' => Str::random(30),
                    'status' => false
                ]);
            }

            // $shipping = explode('-', $request->courier);
            $order = Order::create([
                'invoice' => Str::random(4) . '-' . time(), 
                'customer_id' => $customer->id,
                'customer_name' => $input['customer_name'],
                'customer_phone' => $input['customer_phone'],
                'customer_address' => $input['customer_address'],
                // 'category_name' => $input['customer_address'],
                'city_id' => $input['city_id'],
                'subtotal' => $subtotal,
                'cost' => $input['cost'], 
                'shipping' => $input['courier']
            ]);

            foreach ($carts as $row) {
                //AMBIL DATA PRODUK BERDASARKAN PRODUCT_ID
                $product = Product::find($row['product_id']);
                //SIMPAN DETAIL ORDER
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $row['product_id'],
                    'price' => $row['product_price'],
                    'qty' => $row['qty'],
                    'weight' => $product->weight
                ]);
            }
            
            //TIDAK TERJADI ERROR, MAKA COMMIT DATANYA UNTUK MENINFORMASIKAN BAHWA DATA SUDAH FIX UNTUK DISIMPAN
            DB::commit();

            $carts = [];
            $cookie = cookie('e-carts', json_encode($carts), 2880);
            
            if (!auth()->guard('customer')->check()) {
                Mail::to($input['email'])->send(new CustomerRegisterMail($customer, $password));
            }
            return redirect(route('front.finish_checkout', $order->invoice))->cookie($cookie);
        } catch (\Exception $e) {
            //JIKA TERJADI ERROR, MAKA ROLLBACK DATANYA
            DB::rollback();
            //DAN KEMBALI KE FORM TRANSAKSI SERTA MENAMPILKAN ERROR
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    public function checkoutFinish($invoice)
    {
        $order = Order::with(['city.province'])->where('invoice', $invoice)->first();
        if (Order::where('invoice', $invoice)->exists()){
            return view('ecommerce.checkout_finish', compact('order'));
        }else {
            return redirect()->back();
        }    
    }
}