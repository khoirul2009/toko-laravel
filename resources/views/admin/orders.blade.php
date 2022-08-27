@extends('admin.main')

@section('main')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Order</h4>

                </div>
                <div class="table-responsive">
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <td>No</td>
                                    <td>Kode Transaksi</td>
                                    <td>Pemesan</td>
                                    <td>Pembayaran</td>
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
    <div id="modal">

    </div>
    <script>
        $(document).on('click', '.detail', function() {
            let id = $(this).data('id');
            $.ajax({
                url: '/admin/detailTransaksi/' + id,
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
            });
        });

        let table = new DataTable('.table', {
            serverSide: true,
            processing: true,
            responsive: true,
            ajax: "{{ route('orders.admin') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'kode_transaksi'
                },
                {
                    data: 'users.name'
                },
                {
                    data: 'pembayaran'
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
                        if (data == 0) {
                            return ('<span class="badge bg-danger">Pesanan dibatalkan</span>');
                        } else if (data == 1) {
                            return ('<span class="badge bg-warning">Belum dibayar</span>');
                        } else if (data == 2) {
                            return ('<span class="badge bg-info">Pesanan sudah dibayar</span>');
                        } else if (data == 3) {
                            return ('<span class="badge bg-success">Pesanan telah dikirim</span>');
                        } else if (data == 4) {
                            return ('<span class="badge bg-primary">Pesanan telah diterima</span>');
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
        });
    </script>
@endsection
