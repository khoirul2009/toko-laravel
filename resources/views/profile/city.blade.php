@if ($user->city == null)
    <option selected value="">Pilih kota/kabupaten anda</option>
@endif
@foreach ($city as $c)
    @if ($user->city == $c->city_id)
        <option selected value="{{ $c->city_id }}">{{ $c->city_name }}</option>
    @else
        <option value="{{ $c->city_id }}">{{ $c->city_name }}</option>
    @endif
@endforeach
