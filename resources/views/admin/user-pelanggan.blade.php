@extends('admin.main')

@section('main')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">User Pelanggan</h4>
                </div>
                <div class="table-responsive">

                    <div class="card-body">
                        <table id="table" class="table">
                            <thead>
                                <tr>
                                    <td>No</td>
                                    <td>Username</td>
                                    <td>Nama</td>
                                    <td>Email</td>
                                    <td>No Telephone</td>

                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        let table = new DataTable('#table', {
            serverSide: true,
            processing: true,
            responsive: true,
            ajax: "{{ route('user.pelanggan') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                }, {
                    data: 'username'
                },
                {
                    data: 'name'
                },
                {
                    data: 'email'
                },
                {
                    data: 'telp',
                    render: function(data) {
                        return ('+62' + data)
                    }
                }
            ]
        })
    </script>
@endsection
