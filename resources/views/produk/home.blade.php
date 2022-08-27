<div class="col-lg-2 col-md-12 mt-5">
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">Kategori</h4>
        </div>
        <div class="list-group rounded-0">
            <a href="/produk/fetch_data?category=" id="category"
                class="list-group-item list-group-item-action active all">All</a>
            @foreach ($categories as $category)
                <a href="/produk/fetch_data?category={{ $category->slug }}" id="category"
                    class="list-group-item list-group-item-action">{{ $category->nama }}</a>
            @endforeach

        </div>

    </div>
</div>
<div class="col-lg-7 col-md-12">

    <div class="row mt-5">

        <div class="col">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col">
                            <h4 class="mb-0 ">Daftar Produk</h4>
                        </div>
                        <div class="col-md-4">

                            <form id="formSearch" class="d-flex">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="tf-icons bx bx-search"></i></span>
                                    <input type="text" id="search" class="form-control" placeholder="Search...">
                                </div>
                            </form>
                        </div>

                    </div>

                </div>
            </div>
        </div>


    </div>
    <div class="row mt-3" id="dataProduk">






    </div>

</div>
<div class="col-lg-3 mt-5 ">
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">Keranjang</h4>
        </div>
        <div class="card-body keranjang">
            @if (!auth()->check())
                <p>Kamu belum login silahkan login terlebih dahulu <a href="/login">Login</a></p>
            @endif
        </div>

    </div>
</div>

<script>
    $(document).ready(function() {
        keranjang();

        fetch_data();

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

        function fetch_data() {
            $.ajax({
                url: "/produk/fetch_data",
                beforeSend: function() {
                    $('#loading').removeClass('d-none');
                },
                success: function(data) {
                    $('#dataProduk').html(data);
                    $('#loading').addClass('d-none');
                }
            });


        }
    });
</script>
