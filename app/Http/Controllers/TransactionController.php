<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index()
    {
        return view('transaksi.index', [
            'title' => 'Transaksi'
        ]);
    }
    public function create(Request $request)
    {

        $data = [
            'id_user'           => Auth::id(),
            'kode_transaksi'    => 'TRC-' . time() . '',
            'pembayaran'        => $request->jenis_pembayaran,
            'total_bayar'       => $request->total,
            'status'            => 1,
            'pengiriman'        => $request->pengiriman,
            'ongkir'            => $request->ongkir
        ];

        // mengambil data yang ada dalam keranjang

        $order = Order::where('id_user', Auth::id())
            ->where('status', 1)
            ->get();

        // update stok produk dengan mengurangi jumlah produk yang dipesan

        foreach ($order as $o) {
            Product::where('id', $o->id_product)
                ->decrement('stok', $o->jumlah);
        }

        // membuat transaksi baru

        Transaction::create($data);

        // mengupdate kode_transaksi pada orders dan status orders

        Order::where('id_user', Auth::id())
            ->where('status', 1)
            ->update([
                'kode_transaksi'  => 'TRC-' . time() . '',
                'status'          => 2
            ]);

        return redirect('/transaksi');
    }
    public function detail(Request $request, Transaction $transaction)
    {
        if ($request->ajax()) {
            $produk = [];
            $trasaksi = Transaction::with(['order', 'users'])->where('id', $transaction->id)->first();

            foreach ($trasaksi->order as $order) {
                array_push($produk, Order::with(['product'])->where('id', $order->id)->get());
            }
            return view('transaksi.detail', [
                'transaksi' => $trasaksi,
                'produk'    => $produk

            ])->render();
        }
        abort(404);
    }

    public function buktiPembayaran(Request $request)
    {
        if ($request->ajax()) {
            $validatedData = $request->validate([
                'bukti_pembayaran' => 'image|required'
            ]);
            if ($request->file('bukti_pembayaran')) {
                $validatedData['bukti_pembayaran'] = $request->file('bukti_pembayaran')->store('nota-images');
            }

            $updatedData = [
                'bukti_pembayaran'  => $validatedData['bukti_pembayaran'],
                'status'            => 2
            ];

            Transaction::where('id', $request->id)
                ->update($updatedData);
            return ['success' => true, 'message' => 'bukti pembayaran berhasil diupload'];
        }
    }
    public function cancel(Request $request)
    {
        if ($request->ajax()) {
            $order = Order::where('kode_transaksi', $request->kode_transaksi)
                ->get();

            // update stok produk dengan mengurangi jumlah produk yang dipesan

            foreach ($order as $o) {
                Product::where('id', $o->id_product)
                    ->increment('stok', $o->jumlah);
            }

            Order::where('kode_transaksi', $request->kode_transaksi)
                ->update([
                    'status' => 0
                ]);
            Transaction::where('id', $request->id)->update([
                'status' => 0
            ]);
            return ['success' => true, 'message' => 'Pesanan berhasil dibatalkan'];
        }
    }
    public function detailOrder(Request $request, Transaction $transaction)
    {
        if ($request->ajax()) {
            $produk = [];
            $trasaksi = Transaction::with(['order', 'users'])->where('id', $transaction->id)->first();

            foreach ($trasaksi->order as $order) {
                array_push($produk, Order::with(['product'])->where('id', $order->id)->get());
            }
            return view('admin.detailorder', [
                'transaksi' => $trasaksi,
                'produk'    => $produk

            ])->render();
        }
        abort(404);
    }
    public function sendOrder(Request $request)
    {
        if ($request->ajax()) {
            $request->validate([
                'resi'  => 'required'
            ]);

            Transaction::where('id', $request->id)
                ->update([
                    'status' => 3,
                    'resi'  => $request->resi
                ]);
            return ['success' => true, 'message' => 'Pesanan berhasil dikirim'];
        }
        abort(404);
    }
    public function receiveOrder(Request $request)
    {
        if ($request->ajax()) {
            Transaction::where('id', $request->id)
                ->update([
                    'status' => 4
                ]);
            return ['success' => true, 'message'  => 'Pesanan telah diterima'];
        }
    }
}
