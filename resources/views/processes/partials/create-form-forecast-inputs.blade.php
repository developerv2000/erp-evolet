{{-- Forecast inputs are required from Stage 2 (ПО) --}}
@if ($stage >= 2 && count($selectedCountries))
    @foreach ($selectedCountries as $country)
        <div class="form__row">
            <x-form.inputs.default-input
                labelText="{{ __('Forecast 1 year') . ' ' . $country->code }}"
                inputName="{{ 'forecast_year_1_' . $country->code }}"
                :isRequired="true" />

            <x-form.inputs.default-input
                labelText="{{ __('Forecast 2 year') . ' ' . $country->code }}"
                inputName="{{ 'forecast_year_2_' . $country->code }}"
                :isRequired="true" />

            <x-form.inputs.default-input
                labelText="{{ __('Forecast 3 year') . ' ' . $country->code }}"
                inputName="{{ 'forecast_year_3_' . $country->code }}"
                :isRequired="true" />
        </div>
    @endforeach
@endif
