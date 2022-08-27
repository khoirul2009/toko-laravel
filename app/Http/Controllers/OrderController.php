<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class OrderController extends Controller
{


    public function deletecart(Request $request, Order $order)
    {
        if ($request->ajax()) {
            Order::destroy($order->id);
            return ['success' => true, 'message' => 'Produk dari keranjang berhasil dihapus'];
        }
        abort(404);
    }

    public function addtocart(Request $request)
    {
        if ($request->ajax()) {
            $stok =  DB::table('products')->select('stok')->where('id', $request->id_product)->first();

            if ($request->jumlah < 1) {
                return response('Jumlah yang dipesan tidak boleh kurang dari 1', 444);
            }

            if ($stok->stok < 1) {
                return response('Stok Barang habis', 444);
            }
            // status order
            // 1. cart
            // 2. ordered
            // 3. received
            $request->validate([
                'jumlah'    => 'required'
            ]);

            $data = [
                'id_user' => Auth::id(),
                'id_product' => $request->id_product,
                'jumlah'    => $request->jumlah,
                'status'    => 1
            ];


            if (count(Order::where('id_user', Auth::id())->where('id_product', $request->id_product)->whereRaw('status != 0 AND status != 2')->get()) < 1) {
                Order::create($data);
                return [
                    'success'   => true,
                    'message'   => 'Produk ditambahkan ke keranjang'
                ];
            }
            Order::where('id_product', $request->id_product)->where('kode_transaksi', null)->increment('jumlah', $request->jumlah);

            return [
                'success'   => true,
                'message'   => 'Produk ditambahkan ke keranjang'
            ];
        }
        abort(404);
    }
    public function checkout()
    {
        $totalHarga = DB::table('orders')
            ->join('products', 'orders.id_product', '=', 'products.id')
            ->select(DB::raw('SUM(products.harga * orders.jumlah) as total_harga'))
            ->where('status', 1)
            ->where('id_user', Auth::id())
            ->get();

        if (Auth::user()->telp == '' || Auth::user()->city == '' || Auth::user()->province == '' || Auth::user()->address == '') {
            return redirect('/profile')->with('message', 'Silahkan lengkapi profile anda terlebih dahulu');
        }

        $carts = Order::with(['product'])->where('status', 1)->where('id_user', Auth::id())->get();
        if (count($carts) < 1) {
            return redirect('/');
        }
        return view('checkout.index', [
            'title' => 'Checkout',
            'carts' => $carts,
            'pengiriman' => self::getOngkir(['jne', 'pos', 'tiki']),
            'totalHarga' => $totalHarga[0]->total_harga

        ]);
    }

    public static function getOngkir($courier)
    {
        $berat = DB::table('orders')
            ->join('products', 'orders.id_product', '=', 'products.id')
            ->select(DB::raw('SUM(products.berat) as total_berat'))
            ->where('status', 1)
            ->where('id_user', Auth::id())
            ->get();

        $arrOngkir = [];
        for ($i = 0; $i < count($courier); $i++) {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.rajaongkir.com/starter/cost",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => "origin=344&destination=" . Auth::user()->city . "&weight=" . $berat[0]->total_berat . "&courier=" . $courier[$i],
                CURLOPT_HTTPHEADER => array(
                    "content-type: application/x-www-form-urlencoded",
                    "key: f97d497c160d73f434afb01c0c350e25"
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                abort(500);
                echo "cURL Error #:" . $err;
            } else {
                $ongkir = json_decode($response)->rajaongkir->results[0]->costs;
                array_push($arrOngkir, $ongkir);
            }
        }
        return $arrOngkir;
    }
}
