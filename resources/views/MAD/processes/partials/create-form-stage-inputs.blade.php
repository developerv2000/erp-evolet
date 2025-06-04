{{-- Stage 2 (ПО) inputs --}}
@if ($stage >= 2)
    <div class="form__block">
        <h3 class="main-title main-title--marginless">2ПО</h3>

        <div class="form__row">
            <x-form.inputs.default-input
                labelText="Down payment 1"
                inputName="down_payment_1" />

            <x-form.inputs.default-input
                labelText="Down payment 2"
                inputName="down_payment_2" />

            <x-form.inputs.default-input
                labelText="Down payment condition"
                inputName="down_payment_condition" />
        </div>

        <div class="form__row">
            <x-form.inputs.record-field-input
                labelText="MOQ"
                field="moq"
                type="number"
                min="0"
                :model="$product"
                :isRequired="true" />

            <x-form.inputs.default-input
                labelText="Dossier status"
                inputName="dossier_status" />

            <x-form.inputs.default-input
                labelText="Year Cr/Be"
                inputName="clinical_trial_year" />
        </div>

        <div class="form__row">
            <x-form.selects.selectize.id-based-multiple-select.default-select
                labelText="Countries Cr/Be"
                inputName="clinicalTrialCountries[]"
                :options="$countriesOrderedByName" />

            <x-form.inputs.default-input
                labelText="Country ich"
                inputName="clinical_trial_ich_country" />

            <div class="form-group"></div>
        </div>
    </div>
@endif

{{-- Stage 3 (АЦ) inputs --}}
@if ($stage >= 3)
    <div class="form__block">
        <h3 class="main-title main-title--marginless">3АЦ</h3>

        <div class="form__row">
            <x-form.inputs.default-input
                labelText="Manufacturer price 1"
                inputName="manufacturer_first_offered_price"
                type="number"
                step="0.01"
                min="0.00"
                :isRequired="true" />

            <x-form.inputs.default-input
                labelText="Manufacturer price 2"
                inputName="manufacturer_followed_offered_price"
                type="number"
                step="0.01"
                min="0.00" />

            <x-form.selects.selectize.id-based-single-select.default-select
                labelText="Currency"
                inputName="currency_id"
                :options="$currencies"
                :initialValue="$defaultSelectedCurrencyID"
                :isRequired="true" />
        </div>

        <div class="form__row">
            <x-form.inputs.default-input
                labelText="Our price 1"
                inputName="our_first_offered_price"
                type="number"
                step="0.01"
                min="0.00"
                :isRequired="true" />

            <x-form.inputs.default-input
                labelText="Our price 2"
                inputName="our_followed_offered_price"
                type="number"
                step="0.01"
                min="0.00" />

            {{-- Field is nullable until stage 5 --}}
            <x-form.selects.selectize.id-based-single-select.default-select
                labelText="MAH"
                inputName="marketing_authorization_holder_id"
                :options="$MAHs"
                :initialValue="$defaultSelectedMAHID"
                :isRequired="$stage >= 5" />
        </div>

        <div class="form__row">
            {{-- Field is nullable until stage 5 --}}
            <x-form.inputs.default-input
                labelText="TM Eng"
                inputName="trademark_en"
                :isRequired="$stage >= 5" />

            {{-- Field is nullable until stage 5 --}}
            <x-form.inputs.default-input
                labelText="TM Rus"
                inputName="trademark_ru"
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
            <x-form.inputs.default-input
                labelText="Agreed price"
                inputName="agreed_price"
                type="number"
                step="0.01"
                min="0.00"
                :isRequired="true" />

            <x-form.inputs.default-input
                labelText="Increased price"
                inputName="increased_price"
                type="number"
                step="0.01"
                min="0.00" />

            <div class="form-group"></div>
        </div>
    </div>
@endif
