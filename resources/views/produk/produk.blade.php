<style>
    .detail:hover {
        border: solid 3px #696cff;
        border-radius: 2%;

    }
</style>
@if (count($products) < 1)
    <div class="row">
        <p class="text-center my-3">Produk tidak ditemukan</p>
    </div>
@else
    @foreach ($products as $product)
        <a id="detail" href="" data-id="{{ $product->id }}" class="col-6 px-0 col-md-4 col-lg-4 text-dark detail"
            style="cursor: pointer">
            <div class="card"">
                <img class="card-img-top" height="300px" src="{{ asset('storage/' . $product->gambar) }}"
                    alt="Card image cap">
                <div class="card-body">
                    <h5 class="card-title">{{ $product->nama }}</h5>
                    <p class="card-text">
                        IDR {{ number_format($product->harga, 2) }}
                    </p>


                </div>
            </div>
        </a>
    @endforeach
    {{ $products->onEachSide(1)->links() }}

@endif
