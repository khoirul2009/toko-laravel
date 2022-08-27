@extends('home.main')

@section('main')
    <div class="container-xxl">

        <form action="/createOrder" method="post">
            @csrf
            <div class="row mt-3">
                <div class="col-xxl">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Penerima</h5>
                        </div>
                        <div class="card-body">
                            <small class="text-light fw-semibold">Description list alignment</small>
                            <dl class="row mt-2">
                                <dt class="col-sm-3">Nama Pemesan</dt>
                                <dd class="col-sm-9">{{ Auth::user()->username }}</dd>

                                <dt class="col-sm-3">Alamat</dt>
                                <dd class="col-sm-9">
                                    <p>{{ Auth::user()->address }}</p>
                                </dd>
                                <dt class="col-sm-3">No Telepon</dt>
                                <dd class="col-sm-9">
                                    <p>+62 {{ Auth::user()->telp }}</p>
                                </dd>


                            </dl>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-xxl">
                    <div class="card">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="mb-0">Produk Dipesan</h5>

                        </div>
                        <div class="card-body">
                            @foreach ($carts as $cart)
                                <div class="row mb-3">
                                    <div class="col">
                                        <h6 class="mb-1">{{ $cart->product->nama }}</h6>
                                        <small>IDR {{ number_format($cart->product->harga, 2) }}</small>
                                    </div>
                                    <div class="col text-end">
                                        <span class="badge bg-primary">{{ $cart->jumlah }}</span>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>

                </div>
            </div>

            <div class="row">
                <div class="col-xxl mt-3">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Opsi Pembayaran</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-check mb-2">
                                <input class="form-check-input" value="mandiri" type="radio" name="jenis_pembayaran"
                                    id="mandiri" required>
                                <label class="form-check-label ms-3" for="mandiri">

                                    <img class="img-thumbnail " width="250px" height="90px"
                                        src="../assets/img/payment/mandiri.jpg" alt="">
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" value="ovo" type="radio" name="jenis_pembayaran"
                                    id="ovo" required>
                                <label class="form-check-label ms-3" for="ovo">

                                    <img class="img-thumbnail " width="250px" height="90px"
                                        src="../assets/img/payment/ovo.png" alt="">
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" value="gopay" type="radio" name="jenis_pembayaran"
                                    id="gopay" required>
                                <label class="form-check-label ms-3" for="gopay">

                                    <img class="img-thumbnail " width="250px" height="90px"
                                        src="../assets/img/payment/gopay.jpg" alt="">
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xxl mt-3">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Pengiriman</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @php
                                    $i = 0;
                                @endphp
                                @foreach ($pengiriman as $p)
                                    @foreach ($p as $item)
                                        <div class="form-check mb-2 col-md-3 col-6">
                                            <input class="form-check-input" type="radio" name="pengiriman"
                                                id="{{ $i }}" data-cost="{{ $item->cost[0]->value }}"
                                                onclick="handleClick(this)" value="{{ $item->description }}" required>
                                            <label class="form-check-label ms-3" for="{{ $i }}">
                                                <h6>{{ $item->description }}</h6>
                                                <p>Ongkir : IDR {{ number_format($item->cost[0]->value, 2) }}</p>


                                            </label>
                                        </div>
                                        @php
                                            $i++;
                                            
                                        @endphp
                                    @endforeach
                                @endforeach

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden" name="ongkir" id="ongkir">
            <input type="hidden" name="total" id="total">
            <div class="row mt-3 p-2 mb-5">
                <button class="btn btn-primary" type="submit">Buat Pesanan</button>
            </div>

        </form>
    </div>
    <script>
        function handleClick(elm) {
            let data = parseInt(elm.getAttribute('data-cost'));
            let total = parseInt({{ $totalHarga }});
            $('#ongkir').val(data);
            $('#total').val(data + total);
        }
    </script>
@endsection
