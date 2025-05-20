<h3 class="main-title main-title--marginless">{{ __('ATX') }}</h3>

<div class="form__row">
    <x-form.inputs.default-input
        labelText="ATX"
        inputName="name"
        initialValue="{{ $atx?->name }}"
        :isRequired="true" />

    <x-form.inputs.default-input
        labelText="Our ATX"
        inputName="short_name"
        initialValue="{{ $atx?->short_name }}" />

    <div class="form-group"></div>
</div>
