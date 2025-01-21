<x-form-templates.edit-template :action="route('product-searches.update', $record->id)">
    <div class="form__block">
        <div class="form__row">
            <x-form.selects.selectize.id-based-single-select.record-field-select
                labelText="Country"
                field="country_id"
                :model="$record"
                :options="$countriesOrderedByUsageCount"
                optionCaptionField="code"
                :isRequired="true" />

            <x-form.selects.selectize.id-based-single-select.record-field-select
                labelText="Generic"
                field="inn_id"
                :model="$record"
                :options="$inns"
                :isRequired="true" />

            <x-form.selects.selectize.id-based-single-select.record-field-select
                labelText="Form"
                field="form_id"
                :model="$record"
                :options="$productForms"
                :isRequired="true" />
        </div>

        <div class="form__row">
            <x-form.inputs.record-field-input
                class="specific-formatable-input"
                labelText="Dosage"
                field="dosage"
                :model="$record" />

            <x-form.inputs.record-field-input
                class="specific-formatable-input"
                labelText="Pack"
                field="pack"
                :model="$record" />

            <div class="form-group"></div>
        </div>
    </div>

    <div class="form__block">
        <div class="form__row">
            <x-form.selects.selectize.id-based-single-select.record-field-select
                labelText="MAH"
                field="marketing_authorization_holder_id"
                :model="$record"
                :options="$MAHs"
                :isRequired="true" />

            <x-form.selects.selectize.id-based-single-select.record-field-select
                labelText="Status"
                field="status_id"
                :model="$record"
                :options="$statuses"
                :isRequired="true" />

            <x-form.selects.selectize.id-based-single-select.record-field-select
                labelText="Priority"
                field="priority_id"
                :model="$record"
                :options="$priorities"
                :isRequired="true" />
        </div>

        <div class="form__row">
            <x-form.selects.selectize.id-based-single-select.record-field-select
                labelText="Portfolio manager"
                field="portfolio_manager_id"
                :model="$record"
                :options="$portfolioManagers" />

            <x-form.selects.selectize.id-based-single-select.record-field-select
                labelText="Analyst"
                field="analyst_user_id"
                :model="$record"
                :options="$analystUsers" />

            <x-form.selects.selectize.id-based-multiple-select.record-relation-select
                labelText="Additional search countries"
                inputName="additionalSearchCountries[]"
                :model="$record"
                :options="$countriesOrderedByUsageCount"
                optionCaptionField="code" />
        </div>

        <div class="form__row">
            <x-form.inputs.record-field-input
                labelText="Additional search info"
                field="additional_search_information"
                :model="$record" />

            <div class="form-group"></div>
            <div class="form-group"></div>
        </div>
    </div>

    <div class="form__block">
        <div class="form__row">
            <x-form.radio-buttons.record-field-radio-buttons
                class="radio-group--horizontal"
                labelText="Source EU"
                field="source_eu"
                :model="$record"
                :options="$booleanOptions"
                :isRequired="true" />

            <x-form.radio-buttons.record-field-radio-buttons
                class="radio-group--horizontal"
                labelText="Source IN"
                field="source_in"
                :model="$record"
                :options="$booleanOptions"
                :isRequired="true" />

            <div class="form-group"></div>
        </div>
    </div>

    <div class="form__block">
        <div class="form__row">
            <x-form.inputs.record-field-input
                labelText="Forecast 1 year"
                field="forecast_year_1"
                :model="$record"
                type="number"
                min="1" />

            <x-form.inputs.record-field-input
                labelText="Forecast 2 year"
                field="forecast_year_2"
                :model="$record"
                type="number"
                min="1" />

            <x-form.inputs.record-field-input
                labelText="Forecast 3 year"
                field="forecast_year_3"
                :model="$record"
                type="number"
                min="1" />
        </div>
    </div>

    <div class="form__block">
        <x-form.misc.comment-inputs-on-model-edit :record="$record" />
    </div>
</x-form-templates.edit-template>
