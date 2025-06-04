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
                labelText="MOQ"
                field="moq"
                type="number"
                min="0"
                :model="$product"
                :isRequired="true" />

            <x-form.inputs.record-field-input
                labelText="Dossier status"
                field="dossier_status"
                :model="$record" />

            <x-form.inputs.record-field-input
                labelText="Year Cr/Be"
                field="clinical_trial_year"
                :model="$record" />
        </div>

        <div class="form__row">
            <x-form.selects.selectize.id-based-multiple-select.record-relation-select
                labelText="Countries Cr/Be"
                inputName="clinicalTrialCountries[]"
                :model="$record"
                :options="$countriesOrderedByName" />

            <x-form.inputs.record-field-input
                labelText="Country ich"
                field="clinical_trial_ich_country"
                :model="$record" />

            <div class="form-group"></div>
        </div>
    </div>
@endif

{{-- Stage 3 (АЦ) inputs --}}
@if ($stage >= 3)
    <div class="form__block">
        <h3 class="main-title main-title--marginless">3АЦ</h3>

        <div class="form__row">
            {{-- Readonly when 'manufacturer_followed_offered_price' is filled --}}
            <x-form.inputs.record-field-input
                labelText="Manufacturer price 1"
                field="manufacturer_first_offered_price"
                type="number"
                step="0.01"
                min="0.00"
                :model="$record"
                :isRequired="true"
                :readonly="$record->manufacturer_followed_offered_price ? true : false" />

            {{-- Required when its was already filled --}}
            <x-form.inputs.record-field-input
                labelText="Manufacturer price 2"
                field="manufacturer_followed_offered_price"
                type="number"
                step="0.01"
                min="0.00"
                :model="$record"
                :isRequired="$record->manufacturer_followed_offered_price != null" />

            <x-form.selects.selectize.id-based-single-select.record-field-select
                labelText="Currency"
                field="currency_id"
                :model="$record"
                :options="$currencies"
                :initialValue="$record->currency_id ?: $defaultSelectedCurrencyID"
                :isRequired="true" />
        </div>

        <div class="form__row">
            {{-- Readonly when 'our_followed_offered_price' is filled --}}
            <x-form.inputs.record-field-input
                labelText="Our price 1"
                field="our_first_offered_price"
                type="number"
                step="0.01"
                min="0.00"
                :model="$record"
                :isRequired="true"
                :readonly="$record->our_followed_offered_price ? true : false" />

            {{-- Required when its was already filled --}}
            <x-form.inputs.record-field-input
                labelText="Our price 2"
                field="our_followed_offered_price"
                type="number"
                step="0.01"
                min="0.00"
                :model="$record"
                :isRequired="$record->our_followed_offered_price != null" />

            {{-- Field is nullable until stage 5 --}}
            <x-form.selects.selectize.id-based-single-select.record-field-select
                labelText="MAH"
                field="marketing_authorization_holder_id"
                :model="$record"
                :options="$MAHs"
                :initialValue="$record->marketing_authorization_holder_id ?: $defaultSelectedMAHID"
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

{{-- Comment inputs --}}
<div class="form__block">
    <div class="form__row">
        <x-form.simditor-textareas.default-textarea
            class="simditor--image-uploadable"
            data-image-upload-folder="img/comments"
            inputName="comment"
            :labelText="$statusUpdatedToStopped ? 'Reason for stop' : 'Add new comment'"
            :isRequired="$statusUpdatedToStopped" />

        @if ($record->lastComment)
            <div class="form-group standard-label-group">
                <label class="label">
                    <p class="label__text">{{ __('Last comment') }}</p>
                </label>

                <div class="simditor-text edit-form__last-comment">{!! $record->lastComment->body !!}</div>
            </div>
        @endif
    </div>
</div>
