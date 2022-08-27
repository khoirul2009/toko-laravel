<div class="modal fade" id="detailOrder" aria-labelledby="modalToggleLabel" tabindex="-1" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <form id="buktiPembayaran" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" id="id" value="{{ $transaksi->id }}">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalToggleLabel">Detail Pesanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <dl class="row">
                        <dt class="col-sm-3">Pemesan</dt>
                        <dd class="col-sm-9">{{ $transaksi->users->name }}</dd>
                        <dt class="col-sm-3">Alamat</dt>
                        <dd class="col-sm-9">{{ $transaksi->users->address }}</dd>
                        <dt class="col-sm-3">Pengiriman</dt>
                        <dd class="col-sm-9">{{ $transaksi->pengiriman }}</dd>
                        <dt class="col-sm-3">Pembayaran</dt>
                        <dd class="col-sm-9">{{ $transaksi->pembayaran }}</dd>
                        <dt class="col-sm-3">Ongkos Kirim</dt>
                        <dd class="col-sm-9">IDR {{ number_format($transaksi->ongkir, 2) }}</dd>
                        <dt class="col-sm-3">Total Bayar</dt>
                        <dd class="col-sm-9">IDR {{ number_format($transaksi->total_bayar, 2) }}</dd>
                        <dt class="col-sm-3">Status</dt>
                        <dd class="col-sm-9">
                            @php
                                if ($transaksi->status == 1) {
                                    echo 'Menunggu Pembayaran';
                                } elseif ($transaksi->status == 2) {
                                    echo 'Menunggu pesanan diproses';
                                } elseif ($transaksi->status == 3) {
                                    echo 'Pesanan telah dikirim';
                                } elseif ($transaksi->status == 4) {
                                    echo 'Pesanan telah diterima';
                                } elseif ($transaksi->status == 0) {
                                    echo 'Pesanan dibatalkan';
                                }
                            @endphp
                        </dd>
                        @if ($transaksi->status != 1)
                            <div class="row text-center m-auto mt-3" style="height: 70%; width: 70%">
                                <h6>Bukti Pembayaran</h6>
                                <img src="{{ asset('storage/' . $transaksi->bukti_pembayaran) }}" alt="">
                            </div>
                        @endif
                        <div class="row">
                            <div class="col-12">

                                <h6 class="mt-3">Produk yang dipesan</h6>
                                <ul class="list-group m-3">
                                    @foreach ($produk as $p)
                                        <li class="list-group-item d-flex">
                                            <div>
                                                <h6>{{ $p[0]->product->nama }}</h6>
                                                <p>IDR {{ number_format($p[0]->product->harga, 2) }}</p>

                                            </div>
                                            <div class="ms-auto">
                                                <span class="badge bg-primary">{{ $p[0]->jumlah }}</span>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>

                            </div>
                            <div class="col-12">

                                @if ($transaksi->status == 2)
                                    <label for="invoice" class="form-label">Resi Pengiriman</label>
                                    <input type="text" class="form-control mx-3" name="resi" id="resi"
                                        required placeholder="Masukkan Resi Pengiriman...">
                                @endif
                            </div>
                        </div>


                    </dl>
                </div>

                <div class="modal-footer">
                    @if ($transaksi->status == 2)
                        <button class="btn btn-primary button-1" type="submit">
                            Kirim Pesanan
                        </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $(document).on('submit', '#buktiPembayaran', function(e) {
            e.preventDefault();
            let form = new FormData(this);
            handleSubmit(form);

        });



        function handleSubmit(form) {

            $.ajax({
                url: "{{ route('send.order') }}",
                type: 'post',
                data: form,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $('.button-1').attr('disabled', true);
                },
                success: function(res) {
                    $('#detailOrder').modal('toggle');
                    if (res.success) {
                        swal({
                            title: 'Success',
                            text: res.message,
                            icon: 'success',
                            button: 'OK'
                        });
                    }
                    table.ajax.reload();
                },
                error: function(err) {
                    console.log(err);
                    $('.button-1').attr('disabled', false);

                }
            });
        }
    })
</script>
