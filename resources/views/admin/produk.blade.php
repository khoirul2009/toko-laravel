@extends('admin.main')

@section('main')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h4>Produk</h4>
                    <button class="btn btn-primary " data-bs-toggle="modal" data-bs-target="#Modal">Tambah Produk</button>

                </div>
                <div class="table-responsive">
                    <div class="card-body">
                        <table id="table" class="table">
                            <thead>
                                <tr>
                                    <td>No</td>
                                    <td>Nama</td>
                                    <td>Kategori</td>
                                    <td>Harga</td>

                                    <td>Stok</td>
                                    <td>Status</td>
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

    <div class="modal fade" id="Modal" tabindex="-1" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-xl " role="document">
            <form id="formCreate" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel3">Tambah Produk</h5>
                        <button type="button" id="btnClose" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col mb-3">
                                <label for="nameLarge" class="form-label">Nama Produk</label>
                                <input type="text" id="nama" name="nama" class="form-control"
                                    placeholder="Masukkkan Name">
                                <div class="invalid-feedback error-nama">

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-3">
                                <label for="nameLarge" class="form-label">Slug Produk</label>
                                <input type="text" id="slug" name="slug" class="form-control"
                                    placeholder="Masukkkan Slug" readonly>
                                <div class="invalid-feedback error-slug">

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3">
                                <label for="exampleFormControlSelect1" class="form-label">Kategori </label>
                                <select class="form-select" name="id_kategori" id="exampleFormControlSelect1"
                                    aria-label="Default select example">
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->nama }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback error-id_kategori">

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-3">
                                <label for="nameLarge" class="form-label">Harga Produk</label>
                                <input type="number" min="1" id="harga" name="harga" class="form-control"
                                    placeholder="Masukkkan Harga">
                                <div class="invalid-feedback error-harga">

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-3">
                                <label for="nameLarge" class="form-label">Berat Produk (gram)</label>
                                <input type="number" min="1" id="berat" name="berat" class="form-control"
                                    placeholder="Masukkkan berat">
                                <div class="invalid-feedback error-berat">

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-3">
                                <label for="nameLarge" class="form-label">Stok Produk</label>
                                <input type="number" min="1" id="stok" name="stok" class="form-control"
                                    placeholder="Masukkkan Stok" onchange="">
                                <div class="invalid-feedback error-stok">

                                </div>
                            </div>
                        </div>
                        <img src="" class="img-preview img-thumbnail" alt="">
                        <div class="row">
                            <div class="col mb-3">
                                <label for="nameLarge" class="form-label">Gambar Produk</label>
                                <input type="file" id="gambar" name="gambar" class="form-control"
                                    onchange="previewImg()">
                                <div class="invalid-feedback error-gambar">

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-3">
                                <label for="nameLarge" class="form-label">Deskripsi Produk</label>
                                <textarea name="deskripsi" class="form-control" id="" cols="30" rows="10"></textarea>
                                <div class="invalid-feedback error-deskripsi">

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btnClose" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" class="btn btn-primary add-product">Add Product</button>
                    </div>
                </div>

            </form>


        </div>
    </div>
    <div class="modal fade" id="edit" tabindex="-1" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-xl" role="document">
            <form id="formEdit" method="post" enctype="multipart/form-data">
                @method('put')
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel3">Edit Produk</h5>
                        <button type="button" id="btnClose" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col mb-3">
                                <label for="nameLarge" class="form-label">Nama Produk</label>
                                <input type="text" id="nama" name="nama" class="form-control"
                                    placeholder="Masukkkan Name">
                                <div class="invalid-feedback error-nama">

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-3">
                                <label for="nameLarge" class="form-label">Slug Produk</label>
                                <input type="text" id="slug" name="slug" class="form-control"
                                    placeholder="Masukkkan Slug" readonly>
                                <input type="hidden" id="slug2">
                                <div class="invalid-feedback error-slug">

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3">
                                <label for="exampleFormControlSelect1" class="form-label">Kategori </label>
                                <select class="form-select" name="id_kategori" id="id_kategori"
                                    aria-label="Default select example">
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->nama }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback error-id_kategori">

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-3">
                                <label for="nameLarge" class="form-label">Harga Produk</label>
                                <input type="number" min="1" id="harga" name="harga" class="form-control"
                                    placeholder="Masukkkan Harga">
                                <div class="invalid-feedback error-harga">

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-3">
                                <label for="nameLarge" class="form-label">Berat Produk</label>
                                <input type="number" min="1" id="berat" name="berat" class="form-control"
                                    placeholder="Masukkkan berat">
                                <div class="invalid-feedback error-berat">

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-3">
                                <label for="nameLarge" class="form-label">Stok Produk</label>
                                <input type="number" min="1" id="stok" name="stok" class="form-control"
                                    placeholder="Masukkkan Stok">
                                <div class="invalid-feedback error-stok">

                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="gambarLama" id="gambarLama">
                        <img src="" class="img-preview2 img-thumbnail" alt="">
                        <div class="row">
                            <div class="col mb-3">
                                <label for="nameLarge" class="form-label">Gambar Produk</label>
                                <input type="file" id="gambar2" name="gambar" class="form-control"
                                    onchange="previewImg2()">
                                <div class="invalid-feedback error-gambar">

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-3">
                                <label for="nameLarge" class="form-label">Deskripsi Produk</label>
                                <textarea name="deskripsi" class="form-control" id="deskripsi" cols="30" rows="10"></textarea>
                            </div>
                            <div class="invalid-feedback error-deskripsi">

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btnClose" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" class="btn btn-primary save-product">Save changes</button>
                    </div>
                </div>

            </form>


        </div>
    </div>
    <script>
        function previewImg() {
            const image = document.querySelector("#gambar");
            const imgPreview = document.querySelector(".img-preview");


            imgPreview.style.display = "block";

            const blob = URL.createObjectURL(image.files[0]);
            imgPreview.src = blob;

        }

        function previewImg2() {
            const image = document.querySelector("#gambar2");
            const imgPreview = document.querySelector(".img-preview2");


            imgPreview.style.display = "block";

            const blob = URL.createObjectURL(image.files[0]);
            imgPreview.src = blob;

        }





        $('#formCreate').on('submit', function(e) {
            e.preventDefault();


            $.ajax({
                url: '/admin/produk',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'post',
                dataType: 'json',
                data: new FormData(this),
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $('.add-product').attr('disabled', true);
                    $('#formCreate .form-control').removeClass('is-invalid');
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
                    $('#formCreate textarea').val('');
                    $('#formCreate img').css('display', 'none');
                    table.ajax.reload();
                    $('.add-product').attr('disabled', false);
                },
                error: function(err) {
                    $('.add-product').attr('disabled', false);
                    console.log(err);
                    let status = err.status;
                    let errors = err.responseJSON.errors;
                    if (status == 422) {
                        if (errors.nama) {
                            $('#formCreate #nama').addClass('is-invalid');
                            $('#formCreate .error-nama').html(errors.nama);

                        }
                        if (errors.slug) {
                            $('#formCreate #slug').addClass('is-invalid');
                            $('#formCreate .error-slug').html(errors.slug);

                        }
                        if (errors.id_kategori) {
                            $('#formCreate #id_kategori').addClass('is-invalid');
                            $('#formCreate .error-id_kategori').html(errors.id_kategori);

                        }
                        if (errors.harga) {
                            $('#formCreate #harga').addClass('is-invalid');
                            $('#formCreate .error-harga').html(errors.harga);

                        }
                        if (errors.berat) {
                            $('#formCreate #berat').addClass('is-invalid');
                            $('#formCreate .error-berat').html(errors.berat);

                        }
                        if (errors.stok) {
                            $('#formCreate #stok').addClass('is-invalid');
                            $('#formCreate .error-stok').html(errors.stok);

                        }
                        if (errors.gambar) {
                            $('#formCreate #gambar').addClass('is-invalid');
                            $('#formCreate .error-gambar').html(errors.gambar);

                        }
                        if (errors.deskripsi) {
                            $('#formCreate #deskripsi').addClass('is-invalid');
                            $('#formCreate .error-deskripsi').html(errors.deskripsi);

                        }

                    }
                }

            });
        });

        $('#formCreate #btnClose').on('click', function() {
            resetInputCreate();

        });

        $('#formEdit #btnClose').on('click', function() {
            $('#formEdit input').removeClass('is-invalid');
            $('#edit .img-preview2').attr('src', "");
            $('#edit #gambar2').val(null);
        });


        function resetInputCreate() {
            $('#formCreate input').removeClass('is-invalid');
            $('#formCreate img').css('display', 'none');
            $('#formCreate input').val('');
        }



        $('#formCreate #nama').on('change', function() {
            let nama = $(this).val();
            $.ajax({
                url: '/admin/produk/slug?nama=' + nama,
                method: 'get',
                success: function(data) {
                    $('#formCreate #slug').val(data.slug);
                }
            })
        });
        $('#formEdit #nama').on('change', function() {
            let nama = $(this).val();
            $.ajax({
                url: '/admin/produk/slug?nama=' + nama,
                method: 'get',
                success: function(data) {
                    $('#formEdit #slug').val(data.slug);
                }
            })
        });


        let table = new DataTable('#table', {
            serverSide: true,
            processing: true,
            responsive: true,
            ajax: "{{ route('product.index') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'nama'
                },
                {
                    data: 'category.nama'
                },
                {
                    data: 'harga',
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
                    data: 'stok'
                },
                {
                    data: 'stok',
                    render: function(data) {
                        if (data < 1) {
                            return (
                                '<span class="badge rounded-fill bg-danger">Kosong</span>'
                            )
                        } else {

                            return (
                                '<span class="badge rounded-fill bg-success">Ready</span>'
                            )
                        }
                    }
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
                },
            ]
        });

        $(document).on('click', '.delete', function() {
            let nama = $(this).data('nama');
            $('#namaProduk').html(nama);
            $('#formDelete #slug').val(nama);

        });

        $(document).on('click', '.edit', function() {
            let id = $(this).data('id');
            $.ajax({
                url: '/getProducts/' + id,
                success: function(res) {
                    let result = res[0];
                    $('#edit #nama').val(result.nama);
                    $('#edit #slug').val(result.slug);
                    $('#edit #slug2').val(result.slug);
                    $('#edit #id_kategori').val(result.id_kategori);
                    $('#edit #harga').val(result.harga);
                    $('#edit #berat').val(result.berat);
                    $('#edit #stok').val(result.stok);
                    $('#edit #gambarLama').val(result.gambar);
                    $('#edit #deskripsi').val(result.deskripsi);
                    $('#edit .img-preview2').attr('src', "{{ asset('storage') }}/" + result.gambar +
                        "");

                },
                error: function(err) {
                    console.log(err);
                }
            })
        })

        $('#formEdit').on('submit', function(e) {
            e.preventDefault();
            let slug = $('#formEdit #slug2').val();
            $.ajax({
                url: '/admin/produk/' + slug,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'post',
                dataType: 'json',
                data: new FormData(this),
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $('.save-product').attr('disabled', true);
                    $('#formEdit input').removeClass('is-invalid');
                },
                success: function(res) {
                    swal({
                        title: "Success",
                        text: res.message,
                        icon: "success",
                        button: "Ok",
                    });
                    $('#formEdit #btnClose').click();
                    table.ajax.reload();
                    $('.save-product').attr('disabled', false);
                    // console.log(res);
                },
                error: function(err) {
                    let errors = err.responseJSON.errors;
                    if (errors.nama) {
                        $('#formEdit #nama').addClass('is-invalid');
                        $('#formEdit .error-nama').html(errors.nama);

                    }
                    if (errors.slug) {
                        $('#formEdit #slug').addClass('is-invalid');
                        $('#formEdit .error-slug').html(errors.slug);

                    }
                    if (errors.id_kategori) {
                        $('#formEdit #id_kategori').addClass('is-invalid');
                        $('#formEdit .error-id_kategori').html(errors.id_kategori);

                    }
                    if (errors.harga) {
                        $('#formEdit #harga').addClass('is-invalid');
                        $('#formEdit .error-harga').html(errors.harga);

                    }
                    if (errors.berat) {
                        $('#formEdit #berat').addClass('is-invalid');
                        $('#formEdit .error-berat').html(errors.berat);

                    }
                    if (errors.stok) {
                        $('#formEdit #stok').addClass('is-invalid');
                        $('#formEdit .error-stok').html(errors.stok);

                    }
                    if (errors.gambar) {
                        $('#formEdit #gambar').addClass('is-invalid');
                        $('#formEdit .error-gambar').html(errors.gambar);

                    }
                    if (errors.deskripsi) {
                        $('#formEdit #deskripsi').addClass('is-invalid');
                        $('#formEdit .error-deskripsi').html(errors.deskripsi);

                    }
                    $('.save-product').attr('disabled', false);
                }
            });

        });
        $('#formDelete').on('submit', function(e) {
            e.preventDefault();
            let slug = $('#formDelete #slug').val();
            $.ajax({
                url: '/admin/produk/' + slug,
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
                    console.log(err);
                }
            })
        });
    </script>
@endsection
