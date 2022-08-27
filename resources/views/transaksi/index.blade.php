@extends('home.main')

@section('main')
    <div class="container-xxl">
        <div class="row mt-3">
            <div class="col">

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Transaksi</h5>
                    </div>
                    <div class="table-responsive">
                        <div class="card-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <td>No</td>
                                        <td>Kode Transaksi</td>
                                        <td>Total Bayar</td>
                                        <td>Status</td>

                                        <td>Aksi</td>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="modal">

    </div>
    <script>
        $(document).ready(function() {
            $(document).on('click', '.detail', function() {
                let id = $(this).data('id');
                $.ajax({
                    url: '/detailTransaksi/' + id,
                    beforeSend: function() {
                        $('.detail').attr('disabled', true);
                    },
                    success: function(data) {
                        $('#modal').html(data);;
                        $('#detailOrder').modal('toggle');
                        $('.detail').attr('disabled', false);
                    },
                    error: function(err) {
                        console.log(err);
                    }
                })
            });
        });

        let table = new DataTable('.table', {
            serverSide: true,
            processing: true,
            responsive: true,
            ajax: "{{ route('orders.user') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'kode_transaksi'
                },
                {
                    data: 'total_bayar',
                    render: function(data) {
                        let formater = new Intl.NumberFormat('en-US', {
                            style: 'currency',
                            currency: 'IDR'
                        });
                        return (
                            formater.format(data)
                        )
                    }
                },
                {
                    data: 'status',
                    render: function(data) {
                        if (data == 1) {
                            return ('Menunggu Pembayaran')
                        } else if (data == 2) {
                            return ('Menunggu Konfirmasi')
                        } else if (data == 3) {
                            return ('Pesanan dalam pengiriman')
                        } else if (data == 4) {
                            return ('Barang sudah diterima')
                        } else if (data == 0) {
                            return ('Pesanan dibatalkan')
                        }
                    }
                },
                {
                    data: 'id',
                    render: function(data, type, row) {
                        return (
                            '<button type="button" data-bs-toggle="modal" data-id=' + row.id +
                            ' data-bs-target="#edit" class="detail btn btn-success btn-sm">Detail</button>'
                        )
                    }
                }


            ]
        })
    </script>
@endsection
