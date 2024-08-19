@props(['forname'])
<div class="form-error-message form-text text-xs text-danger font-weight-bold" for-name="{{ $forname ?? '' }}">
    {{ $slot }}
</div>
