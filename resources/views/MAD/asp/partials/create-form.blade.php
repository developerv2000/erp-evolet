<x-form-templates.create-template :action="route('mad-asp.store')">
    <div class="form__block">
        <div class="form__row">
            <x-form.inputs.default-input
                labelText="Year"
                inputName="year"
                type="number"
                min="1"
                :isRequired="true" />

            <x-form.selects.selectize.id-based-multiple-select.default-select
                labelText="Countries"
                inputName="country_ids[]"
                :options="$countriesOrderedByProcessesCount"
                optionCaptionField="code" />
        </div>
    </div>

    <div class="form__block">
        <x-form.misc.comment-inputs-on-model-create />
    </div>
</x-form-templates.create-template>
