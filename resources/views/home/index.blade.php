@extends('home.main')

@section('main')
    <div class="container-xxl">
        <div class="p-5 mb-4 mt-2 bg-light rounded-3" bis_skin_checked="1">
            <div class="container-fluid py-5" bis_skin_checked="1">
                <h1 class="display-5 fw-bold">Online Shop!</h1>
                <p class="col-md-8 fs-4">Aplikasi ini dibuat oleh Muhammad Khoirul Afwan, yang merupakan project pertama
                    latihan
                    framework php Laravel 9</p>
                <button class="btn btn-primary btn-lg" type="button">Example button</button>
            </div>
        </div>
        <div class="row mt-5">

            <h3>Produk Terbaru</h3>

        </div>
        <div class="row mt-3">
            @foreach ($products as $product)
                <div for="linkProduk" class="col-6 col-md-4 col-lg-3 mb-3" style="cursor: pointer">
                    <div class="card h-100">
                        <img class="card-img-top" src="{{ asset('storage/' . $product->gambar) }}" alt="Card image cap">
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->nama }}</h5>
                            <p class="card-text">
                                IDR {{ number_format($product->harga, 2) }}
                            </p>
                            <button type="button" id="detail" class="btn btn-sm btn-outline-primary"
                                data-id="{{ $product->id }}">Detail</button>
                        </div>
                    </div>
                </div>
            @endforeach


        </div>
        <div class="row my-3">

            <a href="/produk" class="btn btn-outline-primary col-md-5 mx-auto">Lihat Produk lainya..</a>

        </div>
    </div>
@endsection
