@extends('admin.main')

@section('main')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h4>Kategori</h4>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#formAdd">Tambah Kategori</button>
                </div>
                <div class="table-responsive">
                    <div class="card-body">
                        <table id="table" class="table">
                            <thead>
                                <tr>
                                    <td>No</td>
                                    <td>Nama Kategori</td>
                                    <td>Aksi</td>
                                </tr>
                            <tbody>

                            </tbody>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="delete" tabindex="-1" aria-modal="true" role="dialog">
        <div class="modal-dialog" role="document">
            <form id="formDelete">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Konfirmasi</h5>
                    </div>
                    <div class="modal-body">
                        <p>Yakin ingin menghapus? <strong id="namaProduk"></strong></p>
                        <input type="hidden" name="slug" id="slug">
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btnClose" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="formAdd" tabindex="-1" aria-modal="true" role="dialog">
        <div class="modal-dialog" role="documnet">
            <form id="formCreate" method="post">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel3">Tambah Kategori</h5>
                        <button type="button" id="btnClose" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col mb-3">
                                <label for="nama" class="form-label">Nama Kategori</label>
                                <input type="text" name="nama" id="nama" class="form-control"
                                    placeholder="Masukkan nama kategori">
                                <div class="invalid-feedback error-nama">

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-3">
                                <label for="slug" class="form-label">Slug</label>
                                <input type="text" name="slug" id="slug" class="form-control" readonly>
                                <div class="invalid-feedback error-slug">

                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btnClose" class="btn btn-outline-secondary"
                            data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary submit">Save</button>
                    </div>

                </div>
            </form>

        </div>

    </div>
    <div class="modal fade" id="edit" tabindex="-1" aria-modal="true" role="dialog">
        <div class="modal-dialog" role="documnet">
            <form id="formUpdate" method="post">
                @method('put')
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel3">Tambah Kategori</h5>
                        <button type="button" id="btnClose" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col mb-3">
                                <label for="nama" class="form-label">Nama Kategori</label>
                                <input type="text" name="nama" id="nama" class="form-control"
                                    placeholder="Masukkan nama kategori">
                                <div class="invalid-feedback error-nama">

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-3">
                                <label for="slug" class="form-label">Slug</label>
                                <input type="text" name="slug" id="slug" class="form-control" readonly>
                                <input type="hidden" name="" id="slug2">
                                <div class="invalid-feedback error-slug">


                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btnClose" class="btn btn-outline-secondary"
                            data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>

                </div>
            </form>

        </div>

    </div>

    <script>
        $('#formCreate').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: '/admin/kategori',
                type: 'post',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: new FormData(this),
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $('#formCreate input').removeClass('is-invalid');
                    $('#formCreate .submit').attr('disabled', true);

                },
                success: function(res) {
                    swal({
                        title: "Success",
                        text: res.message,
                        icon: "success",
                        button: "Ok",
                    });
                    $('#formCreate #btnClose').click();
                    $('#formCreate input').val('');
                    $('#formCreate .submit').attr('disabled', false);
                    table.ajax.reload();
                },
                error: function(err) {
                    $('#formCreate .submit').attr('disabled', false);
                    console.log(err);
                    let errors = err.responseJSON.errors;

                    if (errors.nama) {
                        $('#formCreate #nama').addClass('is-invalid');
                        $('#formCreate .error-nama').html(errors.nama);

                    }
                    if (errors.slug) {
                        $('#formCreate #slug').addClass('is-invalid');
                        $('#formCreate .error-slug').html(errors.slug);

                    }
                    if (errors.icon) {
                        $('#formCreate #icon').addClass('is-invalid');
                        $('#formCreate .error-icon').html(errors.icon);

                    }
                }

            })

        });

        $('#formUpdate').on('submit', function(e) {
            e.preventDefault();
            let slug = $('#formUpdate #slug2').val();
            $.ajax({
                url: '/admin/kategori/' + slug,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: 'post',
                data: new FormData(this),
                processData: false,
                contentType: false,
                success: function(res) {
                    swal({
                        title: "Success",
                        text: res.message,
                        icon: "success",
                        button: "Ok",
                    });
                    $('#formUpdate #btnClose').click();
                    table.ajax.reload();
                },
                error: function(err) {
                    let errors = err.responseJSON.errors;
                    if (errors.nama) {
                        $('#formUpdate #nama').addClass('is-invalid');
                        $('#formUpdate .error-nama').html(errors.nama);

                    }
                    if (errors.slug) {
                        $('#formUpdate #slug').addClass('is-invalid');
                        $('#formUpdate .error-slug').html(errors.slug);

                    }
                    if (errors.icon) {
                        $('#formUpdate #icon').addClass('is-invalid');
                        $('#formUpdate .error-icon').html(errors.icon);

                    }
                }
            })
        })


        $(document).on('click', '.delete', function() {
            let nama = $(this).data('nama');
            $('#namaProduk').html(nama);
            $('#formDelete #slug').val(nama);

        });

        $(document).on('click', '.edit', function() {
            let id = $(this).data('id');
            $.ajax({
                url: '/getCategories/' + id,
                success: function(res) {
                    let result = res[0];
                    $('#edit #nama').val(result.nama);
                    $('#edit #slug').val(result.slug);
                    $('#edit #slug2').val(result.slug);
                    $('#edit #icon').val(result.icon);
                },
                error: function(err) {
                    console.log(err);
                }
            })
        })

        $('#formCreate #nama').on('change', function() {
            let nama = $(this).val();
            $.ajax({
                url: '/admin/kategori/slug?nama=' + nama,
                method: 'get',
                success: function(data) {
                    $('#formCreate #slug').val(data.slug);
                },
                error: function(err) {
                    console.log(err);
                }
            })
        });
        $('#formUpdate #nama').on('change', function() {
            let nama = $(this).val();
            $.ajax({
                url: '/admin/kategori/slug?nama=' + nama,
                method: 'get',
                success: function(data) {
                    $('#formUpdate #slug').val(data.slug);
                },
                error: function(err) {
                    console.log(err);
                }
            })
        });

        $('#formCreate #btnClose').on('click', function() {
            $('#formCreate input').removeClass('is-invalid');
        });
        $('#formUpdate #btnClose').on('click', function() {
            $('#formUpdate input').removeClass('is-invalid');
        });

        let table = new DataTable('#table', {
            serverSide: true,
            processing: true,
            responsive: true,
            ajax: "{{ route('product.categories') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'nama'
                },
                {
                    data: 'id',
                    render: function(data, type, row) {
                        return (
                            '<button type="button" data-bs-toggle="modal" data-id=' + row.id +
                            ' data-bs-target="#edit" class="edit btn btn-warning btn-sm">Edit</button>' +
                            '<button type="button" data-nama=' + row.slug +
                            ' data-bs-toggle="modal" data-bs-target="#delete" class="delete btn btn-danger btn-sm ms-2">Delete</button>'
                        )
                    }
                }
            ]
        });

        $('#formDelete').on('submit', function(e) {
            e.preventDefault();
            let slug = $('#formDelete #slug').val();
            $.ajax({
                url: '/admin/kategori/' + slug,
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                type: 'delete',
                success: function(res) {
                    swal({
                        title: "Success",
                        text: res.message,
                        icon: "success",
                        button: "Ok",
                    });
                    $('#btnClose').click();
                    table.ajax.reload();
                },
                error: function(err) {


                }
            })
        });
    </script>
@endsection
