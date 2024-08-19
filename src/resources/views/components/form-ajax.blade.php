@props(['method'])
@php
    $method = $method ?? 'POST';
    $fmethod = in_array(strtolower($method), ['get', 'post']) ? $method : 'POST';
@endphp
<form method="{{ $fmethod }}" {{ $attributes->merge(['class' => 'form-ajax']) }}>
    @method($method)
    @csrf
    {{ $slot }}
</form>
