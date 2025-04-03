@props(['fullWidth' => false])

<div class="form__row-remove-button @if($fullWidth) form__row-remove-button--full-width @endif">
    <x-misc.material-symbol class="form__row-remove-button-icon" icon="close" title="{{ __('Delete') }}" />
</div>
