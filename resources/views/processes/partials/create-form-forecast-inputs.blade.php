{{-- Forecast inputs are required from Stage 2 (ПО) --}}
@if ($stage >= 2 && count($selectedCountries))
    <div class="form__block">
        <h3 class="main-title main-title--marginless">{{ __('Forecasts') }}</h3>

        @foreach ($selectedCountries as $country)
            <div class="form__row">
                <x-form.inputs.default-input
                    labelText="{{ __('Forecast 1 year') . ' ' . $country->code }}"
                    inputName="{{ 'forecast_year_1_of_' . $country->code }}"
                    type="number"
                    min="1"
                    :isRequired="true" />

                <x-form.inputs.default-input
                    labelText="{{ __('Forecast 2 year') . ' ' . $country->code }}"
                    inputName="{{ 'forecast_year_2_of_' . $country->code }}"
                    type="number"
                    min="1"
                    :isRequired="true" />

                <x-form.inputs.default-input
                    labelText="{{ __('Forecast 3 year') . ' ' . $country->code }}"
                    inputName="{{ 'forecast_year_3_of_' . $country->code }}"
                    type="number"
                    min="1"
                    :isRequired="true" />
            </div>
        @endforeach
    </div>
@endif
