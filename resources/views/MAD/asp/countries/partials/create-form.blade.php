<x-form-templates.create-template :action="route('mad.asp.countries.store', $record->year)">
    <div class="form__block">
        <div class="form__row">
            <x-form.selects.selectize.id-based-single-select.default-select
                labelText="Country"
                inputName="country_id"
                :options="$countriesOrderedByProcessesCount"
                optionCaptionField="code"
                :isRequired="true" />

            <x-form.selects.selectize.id-based-multiple-select.default-select
                labelText="MAH"
                inputName="marketing_authorization_holder_ids[]"
                :options="$MAHs"
                :isRequired="true" />
        </div>
    </div>
</x-form-templates.create-template>
