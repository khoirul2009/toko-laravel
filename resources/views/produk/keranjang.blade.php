@php
$arrTotal = [];
@endphp

<div class="">

    @if (count($carts) > 0)
        @foreach ($carts as $cart)
            <div class="row mb-3">
                <div class="col">
                    <h6 class="mb-1">{{ $cart->product->nama }}</h6>
                    <small>IDR {{ number_format($cart->product->harga, 2) }}</small>
                </div>
                <div class="col text-end">
                    <span class="badge bg-primary">{{ $cart->jumlah }}</span>
                    <button id="delete" data-id="{{ $cart->id }}" type="button"
                        class="btn btn-xs btn-danger">delete</button>
                </div>
            </div>
            @php
                array_push($arrTotal, $cart->product->harga * $cart->jumlah);
            @endphp
        @endforeach
    @else
        <div class="row">
            <div class="col">
                <p>Keranjang masih kosong...</p>
            </div>
        </div>
    @endif


</div>
<div class=" d-flex">
    <div class="mt-5 d-flex">

        <div>
            @if (count($carts) > 0)
                <a href="/checkout" class="btn btn-success me-2">Checkout</a>
            @else
                <button disabled class="btn btn-success me-2">Checkout</button>
            @endif


        </div>
        <div class="ms-5">
            <h5>Total Bayar : IDR {{ number_format(array_sum($arrTotal), 2) }}</h5>
        </div>
    </div>

</div>
</div>
