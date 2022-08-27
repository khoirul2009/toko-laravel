<option value="">Pilih layanan pengiriman</option>
@foreach ($ongkir as $cost)
    <option value="{{ $cost->cost[0]->value }}">{{ $cost->service }} - IDR
        {{ number_format($cost->cost[0]->value, 2) }}</option>
@endforeach
