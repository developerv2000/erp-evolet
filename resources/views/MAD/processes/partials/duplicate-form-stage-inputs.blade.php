{{-- Stage 2 (ПО) inputs --}}
@if ($stage >= 2)
    <div class="form__block">
        <h3 class="main-title main-title--marginless">{{ __('Forecasts') }}</h3>

        <div class="form__row">
            <x-form.inputs.record-field-input
                labelText="Forecast 1 year"
                field="forecast_year_1"
                :model="$record"
                type="number"
                min="0"
                :isRequired="true" />

            <x-form.inputs.record-field-input
                labelText="Forecast 2 year"
                field="forecast_year_2"
                :model="$record"
                type="number"
                min="0"
                :isRequired="true" />

            <x-form.inputs.record-field-input
                labelText="Forecast 3 year"
                field="forecast_year_3"
                :model="$record"
                type="number"
                min="0"
                :isRequired="true" />
        </div>
    </div>

    <div class="form__block">
        <h3 class="main-title main-title--marginless">2ПО</h3>

        <div class="form__row">
            <x-form.inputs.record-field-input
                labelText="Down payment 1"
                field="down_payment_1"
                :model="$record" />

            <x-form.inputs.record-field-input
                labelText="Down payment 2"
                field="down_payment_2"
                :model="$record" />

            <x-form.inputs.record-field-input
                labelText="Down payment condition"
                field="down_payment_condition"
                :model="$record" />
        </div>

        <div class="form__row">
            <x-form.inputs.record-field-input
                labelText="Dossier status"
                field="dossier_status"
                :model="$record" />

            <x-form.inputs.record-field-input
                labelText="Year Cr/Be"
                field="clinical_trial_year"
                :model="$record" />

            <x-form.selects.selectize.id-based-multiple-select.record-relation-select
                labelText="Countries Cr/Be"
                inputName="clinicalTrialCountries[]"
                :model="$record"
                :options="$countriesOrderedByName" />
        </div>

        <div class="form__row">
            <x-form.inputs.record-field-input
                labelText="Country ich"
                field="clinical_trial_ich_country"
                :model="$record" />

            <div class="form-group"></div>
            <div class="form-group"></div>
        </div>
    </div>
@endif

{{-- Stage 3 (АЦ) inputs --}}
@if ($stage >= 3)
    <div class="form__block">
        <h3 class="main-title main-title--marginless">3АЦ</h3>

        <div class="form__row">
            <x-form.inputs.record-field-input
                labelText="Manufacturer price 1"
                field="manufacturer_first_offered_price"
                type="number"
                step="0.01"
                min="0.00"
                :model="$record"
                :isRequired="true" />

            <x-form.inputs.record-field-input
                labelText="Manufacturer price 2"
                field="manufacturer_followed_offered_price"
                type="number"
                step="0.01"
                min="0.00"
                :model="$record" />

            <x-form.selects.selectize.id-based-single-select.record-field-select
                labelText="Currency"
                field="currency_id"
                :model="$record"
                :options="$currencies"
                :isRequired="true" />
        </div>

        <div class="form__row">
            <x-form.inputs.record-field-input
                labelText="Our price 1"
                field="our_first_offered_price"
                type="number"
                step="0.01"
                min="0.00"
                :model="$record"
                :isRequired="true" />

            <x-form.inputs.record-field-input
                labelText="Our price 2"
                field="our_followed_offered_price"
                type="number"
                step="0.01"
                min="0.00"
                :model="$record" />

            {{-- Field is nullable until stage 5 --}}
            <x-form.selects.selectize.id-based-single-select.record-field-select
                labelText="MAH"
                field="marketing_authorization_holder_id"
                :model="$record"
                :options="$MAHs"
                :isRequired="$stage >= 5" />
        </div>

        <div class="form__row">
            {{-- Field is nullable until stage 5 --}}
            <x-form.inputs.record-field-input
                labelText="TM Eng"
                field="trademark_en"
                :model="$record"
                :isRequired="$stage >= 5" />

            {{-- Field is nullable until stage 5 --}}
            <x-form.inputs.record-field-input
                labelText="TM Rus"
                field="trademark_ru"
                :model="$record"
                :isRequired="$stage >= 5" />

            <div class="form-group"></div>
        </div>
    </div>
@endif

{{-- Stage 4 (СЦ) inputs --}}
@if ($stage >= 4)
    <div class="form__block">
        <h3 class="main-title main-title--marginless">4СЦ</h3>

        <div class="form__row">
            <x-form.inputs.record-field-input
                labelText="Agreed price"
                field="agreed_price"
                type="number"
                step="0.01"
                min="0.00"
                :model="$record"
                :isRequired="true" />

            <x-form.inputs.record-field-input
                labelText="Increased price"
                field="increased_price"
                type="number"
                step="0.01"
                min="0.00"
                :model="$record" />

            <div class="form-group"></div>
        </div>
    </div>
@endif
