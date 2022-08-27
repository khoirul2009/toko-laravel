<div class="modal fade" id="detailProduk" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel4">Detail Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <img class="img-thumbnail mx-auto rounded-3" src="{{ asset('storage/' . $product->gambar) }}"
                            alt="Card image cap">
                    </div>
                    <div class="col-md-6">
                        <h5 id="namaProduk">{{ $product->nama }}</h5>
                        <h5>Stok : <span id="stokProduk">{{ $product->stok }}</span></h5>
                        <p><span id="hargaProduk">IDR {{ number_format($product->harga, 2) }}</span> | <span
                                id="kategoriProduk">{{ $product->category->nama }}</span>
                        </p>
                        <p class="card-text">
                            Berat : {{ $product->berat }} gram
                        </p>
                        <div id="deskripsiProduk">{{ $product->deskripsi }}</div>
                        <div class="mt-2">
                            <form id="addToCart">
                                @csrf
                                <label for="" class="form-label">Jumlah</label>
                                <input type="hidden" name="id_product" value="{{ $product->id }}">
                                <input type="number" id="jumlah" class="form-control" style="width: 100px"
                                    name="jumlah" min="1">
                                <div class="invalid-feedback error-jumlah">

                                </div>
                                <button type="submit" id="btnCart"
                                    class="btn btn-success d-flex align-center pb-0 pt-3 mt-2">
                                    <i class='bx bxs-cart-add me-2'></i>
                                    <p>Tambahkan Ke Keranjang</p>
                                </button>

                            </form>
                        </div>

                    </div>
                </div>
            </div>
            <div class="modal-footer">

            </div>

        </div>
    </div>
</div>
