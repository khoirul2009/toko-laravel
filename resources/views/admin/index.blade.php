@extends('admin.main')

@section('main')
    <div class="row">
        <div class="col">
            <h4>Dashboard</h4>
            <div class="row">
                <div class="col-md-3 col-6">

                    <div class="card mb-3">
                        <div class="card-header">
                            Order
                        </div>
                        <div class="card-body d-flex">
                            <i class='bx bxs-book-bookmark fs-1'></i>
                            <p class="fs-3 ms-auto mb-0">{{ $order->order_count }}</p>
                        </div>

                    </div>
                </div>
                <div class="col-md-3 col-6">

                    <div class="card mb-3">
                        <div class="card-header">
                            Pelanggan
                        </div>
                        <div class="card-body d-flex">
                            <i class='bx bxs-user-account fs-1'></i>
                            <p class="fs-3 ms-auto mb-0">{{ $pelanggan->user_count }}</p>
                        </div>

                    </div>
                </div>
                <div class="col-md-3 col-6">

                    <div class="card mb-3">
                        <div class="card-header">
                            Produk
                        </div>
                        <div class="card-body d-flex">
                            <i class='bx bxs-store fs-1'></i>
                            <p class="fs-3 ms-auto mb-0">{{ $produk->produk_count }}</p>
                        </div>

                    </div>
                </div>
                <div class="col-md-3 col-6">

                    <div class="card mb-3">
                        <div class="card-header">
                            Stok Produk
                        </div>
                        <div class="card-body d-flex">
                            <i class='bx bxs-package fs-1'></i>
                            <p class="fs-3 ms-auto mb-0">{{ $stok->stok_produk }}</p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">

            <div class="row">
                <div class="col-md-3 col-6">

                    <div class="card mb-3">
                        <div class="card-header">
                            Total Transaksi
                        </div>
                        <div class="card-body d-flex">
                            <i class='bx bxs-book-bookmark fs-1'></i>
                            <p class="ms-auto mb-0">Rp.
                                {{ $total_transaksi->total_transaksi == null ? 0 : number_format($total_transaksi->total_transaksi, 2) }}
                            </p>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
