<x-form-templates.create-template class="product-searches-create-form" :action="route('product-searches.store')">
    <div class="form__block">
        <div class="form__row">
            <x-form.selects.selectize.id-based-single-select.default-select
                labelText="Country"
                inputName="country_id"
                :options="$countriesOrderedByUsageCount"
                optionCaptionField="code"
                :isRequired="true" />

            <x-form.selects.selectize.id-based-single-select.default-select
                labelText="Generic"
                inputName="inn_id"
                :options="$inns"
                :isRequired="true" />

            <x-form.selects.selectize.id-based-single-select.default-select
                labelText="Form"
                inputName="form_id"
                :options="$productForms"
                :isRequired="true" />
        </div>

        <div class="form__row">
            <x-form.inputs.default-input
                class="specific-formatable-input"
                labelText="Dosage"
                inputName="dosage" />

            <x-form.inputs.default-input
                class="specific-formatable-input"
                labelText="Pack"
                inputName="pack" />

            <div class="form-group"></div>
        </div>
    </div>

    {{-- Container used to hold similar products, after AJAX request --}}
    <div class="form__block similar-records-wrapper"></div>

    <div class="form__block">
        <div class="form__row">
            <x-form.selects.selectize.id-based-multiple-select.default-select
                labelText="MAH"
                inputName="marketing_authorization_holder_ids[]"
                :options="$MAHs"
                :isRequired="true" />

            <x-form.selects.selectize.id-based-single-select.default-select
                labelText="Status"
                inputName="status_id"
                :options="$statuses"
                :initialValue="$defaultSelectedStatusID"
                :isRequired="true" />

            <x-form.selects.selectize.id-based-single-select.default-select
                labelText="Priority"
                inputName="priority_id"
                :options="$priorities"
                :initialValue="$defaultSelectedPriorityID"
                :isRequired="true" />
        </div>

        <div class="form__row">
            <x-form.selects.selectize.id-based-single-select.default-select
                labelText="Portfolio manager"
                inputName="portfolio_manager_id"
                :options="$portfolioManagers" />

            <x-form.selects.selectize.id-based-single-select.default-select
                labelText="Analyst"
                inputName="analyst_user_id"
                :options="$analystUsers" />

            <x-form.selects.selectize.id-based-multiple-select.default-select
                labelText="Additional search countries"
                inputName="additionalSearchCountries[]"
                :options="$countriesOrderedByUsageCount"
                optionCaptionField="code" />
        </div>

        <div class="form__row">
            <x-form.inputs.default-input
                labelText="Additional search info"
                inputName="additional_search_information" />

            <div class="form-group"></div>
            <div class="form-group"></div>
        </div>
    </div>

    <div class="form__block">
        <div class="form__row">
            <x-form.radio-buttons.default-radio-buttons
                class="radio-group--horizontal"
                labelText="Source EU"
                inputName="source_eu"
                :options="$booleanOptions"
                :initialValue="true"
                :isRequired="true" />

            <x-form.radio-buttons.default-radio-buttons
                class="radio-group--horizontal"
                labelText="Source IN"
                inputName="source_in"
                :options="$booleanOptions"
                :initialValue="true"
                :isRequired="true" />

            <div class="form-group"></div>
        </div>
    </div>

    <div class="form__block">
        <div class="form__row">
            <x-form.inputs.default-input
                labelText="Forecast 1 year"
                inputName="forecast_year_1"
                type="number"
                min="1" />

            <x-form.inputs.default-input
                labelText="Forecast 2 year"
                inputName="forecast_year_2"
                type="number"
                min="1" />

            <x-form.inputs.default-input
                labelText="Forecast 3 year"
                inputName="forecast_year_3"
                type="number"
                min="1" />
        </div>
    </div>

    <div class="form__block">
        <x-form.misc.comment-inputs-on-model-create />
    </div>
</x-form-templates.create-template>
