@props([
    'action',
    'id' => 'edit-form',
    'method' => 'PATCH',
    'submitText' => 'Update',
    'submitIcon' => 'done_all',
])

<form
    {{ $attributes->merge(['class' => 'form edit-form']) }}
    action="{{ $action }}"
    id="{{ $id }}"
    method="POST"
    enctype="multipart/form-data"
    data-on-submit="show-spinner">

    @csrf
    @method($method)
    <input type="hidden" name="previous_url" value="{{ old('previous_url', url()->previous()) }}">

    {{ $slot }}

    <div class="form__buttons-wrapper">
        <x-misc.button class="form__submit" type="submit" icon="{{ $submitIcon }}">{{ __($submitText) }}</x-misc.button>
    </div>
</form>
