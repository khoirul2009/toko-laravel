@extends('home.main')

@section('main')
    <div class="container-fluid">
        <div class="row component">

        </div>



    </div>

    <script>
        $(document).ready(function() {
            homeComponent();
            keranjang();


            let formatter = new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'IDR'
            });


            $(document).on('click', '#keranjang', function(e) {
                e.preventDefault();

            });


            $(document).on('click', '#category', function(e) {
                e.preventDefault();
                var category = $(this).attr('href').split('category=')[1];
                var element = $(this);
                var search = $('#search').val();
                // orderByCategory(category);
                $.ajax({
                    url: "/produk/fetch_data?category=" + category + "&search=" + search,
                    beforeSend: function() {
                        $('.list-group a').removeClass('active');
                        $('#loading').removeClass('d-none');
                    },
                    success: function(data) {
                        $('#dataProduk').html(data);
                        element.addClass('active');
                        $('.list-group').addClass('selected');
                        $('#loading').addClass('d-none');

                    }
                });

            });


            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                fetch_data(page);
            });

            function fetch_data(page) {

                var search = $('#search').val();
                var category = $('.active').attr('href').split('category=')[1];

                $.ajax({
                    url: "/produk/fetch_data?category=" + category + "&page=" + page + "&search=" + search,
                    beforeSend: function() {
                        $('#loading').removeClass('d-none');
                    },
                    success: function(data) {
                        $('#dataProduk').html(data);
                        $('#loading').addClass('d-none');
                    }
                });


            }

            function homeComponent() {
                $.ajax({
                    url: "{{ route('home.component') }}",
                    beforeSend: function() {
                        $('#loading').removeClass('d-none');
                    },
                    success: function(data) {
                        $('.component').html(data);
                        $('#loading').addClass('d-none');
                    },
                    error: function(err) {
                        console.log(err);
                    }
                })
            }

            $(document).on('submit', '#formSearch', function(e) {
                e.preventDefault();
                var search = $('#search').val();
                search_data(search);
            });

            function search_data(search) {
                var category = $('.active').attr('href').split('category=')[1];

                $.ajax({
                    url: "/produk/fetch_data?category=" + category + "&search=" + search,
                    beforeSend: function() {
                        $('#loading').removeClass('d-none');
                    },
                    success: function(data) {
                        $('#dataProduk').html(data);
                        $('#loading').addClass('d-none');
                    }
                });


            }

            function keranjang() {
                $.ajax({
                    url: '/keranjang',
                    success: function(data) {
                        $('.keranjang').html(data);

                    },
                    error: function(err) {
                        if (err.status == 401) {

                            swal({
                                    title: err.responseJSON.message,
                                    text: "Kamu belum login",
                                    icon: "warning",
                                    buttons: true,
                                    dangerMode: true,
                                })
                                .then((login) => {
                                    if (login) {
                                        window.location.replace("{{ route('login') }}");
                                    }
                                });
                        }
                    }
                });
            }

            $(document).on('submit', '#addToCart', function(e) {
                e.preventDefault();
                let form = new FormData(this);
                addtocart(form);
            });

            function addtocart(form) {
                $.ajax({
                    url: '/addtocart',
                    type: 'post',
                    data: form,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        $('#btnCart').prop('disabled', true);
                    },
                    success: function(res) {
                        console.log(res);
                        if (res.success) {
                            swal({
                                title: 'Success',
                                text: res.message,
                                icon: 'success',
                                button: 'OK'
                            });
                        }
                        $('#btnCart').prop('disabled', false);
                        $('#detailProduk').modal('toggle');
                        keranjang();


                    },
                    error: function(err) {

                        // console.log(err);
                        if (err.status == 401) {
                            swal({
                                    title: err.responseJSON.message,
                                    text: "Kamu belum login",
                                    icon: "error",
                                    buttons: true,
                                    dangerMode: true,
                                })
                                .then((login) => {
                                    if (login) {
                                        window.location.replace("{{ route('login') }}");
                                    }
                                });
                        }
                        if (err.status == 422) {
                            $('#jumlah').addClass('is-invalid');
                            $('.error-jumlah').html(err.responseJSON.errors.jumlah);
                        }
                        if (err.status == 444) {
                            swal({
                                title: "Gagal",
                                text: err.responseText,
                                icon: "warning",
                                buttons: true,
                                dangerMode: true,
                            })
                        }
                        $('#btnCart').prop('disabled', false);

                    }

                })
            }

            $(document).on('click', '#delete', function() {
                swal({
                        title: "Anda yakin?",
                        text: "Mengapus produk dari keranjang",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            deletecart($(this).data('id'));
                        } else {
                            swal("Produk tidak jadi dihapus!");
                        }
                    });
            });

            function deletecart(selectedId) {
                let id = selectedId;
                $.ajax({
                    url: '/deletecart/' + id,
                    type: 'delete',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        swal(res.message, {
                            icon: "success",
                        });
                        $('.btn-close').click();
                        keranjang();

                    },
                    error: function(err) {
                        console.log(err);
                    }
                })
            }

            $(document).on('click', '#keranjang', function() {
                showCart();
            });

            function showCart() {
                $.ajax({
                    url: '/keranjang',
                    success: function(data) {
                        $('.keranjang').html(data);
                        $('#btnCanvas').click();
                    },
                    error: function(err) {
                        if (err.status == 401) {
                            swal({
                                    title: err.responseJSON.message,
                                    text: "Kamu belum login",
                                    icon: "error",
                                    buttons: true,
                                    dangerMode: true,
                                })
                                .then((login) => {
                                    if (login) {
                                        window.location.replace("{{ route('login') }}");
                                    }
                                });
                        }
                    }
                });

            }

            $(document).on('click', '#detail', function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                getProduct(id);

            });

            function getProduct(id) {
                $.ajax({
                    url: "/produk/" + id,
                    success: function(data) {
                        $('#product-detail').html(data);
                        $('#detailProduk').modal('show');

                    },
                    error: function(err) {
                        console.log(err);
                    }
                })
            }






        })
    </script>
@endsection
