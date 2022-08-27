<div class="card" style="height: 204.03px">
    <div class="card-header">
        <h5 class="mb-0">Total Bayar</h5>
    </div>
    <div class="card-body">
        <dl class="row">
            <dt class="col-sm-4">Total Harga Barang </dt>
            <dd class="col-sm-8">
                IDR {{ number_format($total, 2) }}
                <input type="hidden" value="{{ $total }}" id="totalBarang">
            </dd>
        </dl>
        <dl class="row">
            <dt class="col-sm-4">Total Bayar </dt>
            <dd class="col-sm-8" id="bayar"></dd>
        </dl>

    </div>
</div>

<script>
    $(document).ready(function() {
        $(document).on('change', '#pengiriman', async function() {
            await getOngkir();

        });

        $(document).on('change', '#service', function() {
            setOngkir();
        });

        function setOngkir() {
            $.ajax({
                url: '/total',
                data: {
                    ongkir: $('#service').val()
                },
                dataType: 'JSON',
                success: function(res) {
                    let ongkir = $('#service').val();
                    if (ongkir != '') {

                        $('#bayar').html('IDR ' + res.totalBayar);
                    }

                },
                error: function(err) {
                    console.log(err);
                }
            })
        }

        getOngkir();


        function getOngkir() {
            let type = $('#pengiriman').val()
            $.ajax({
                url: '/ongkir/' + type,
                beforeSend: function() {
                    $('#service').prop('disabled', true);
                },
                success: function(data) {
                    $('#service').html(data);
                    $('#service').prop('disabled', false);
                },
                error: function(err) {
                    console.log(err);
                }
            })
        }
    })
</script>
