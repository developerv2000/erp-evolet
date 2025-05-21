<x-form-templates.create-template :action="route('mad.meetings.store')">
    <div class="form__block">
        <div class="form__row">
            <x-form.selects.selectize.id-based-single-select.default-select
                labelText="Manufacturer"
                inputName="manufacturer_id"
                :options="$manufacturers"
                :isRequired="true" />

            <x-form.inputs.default-input
                labelText="Year"
                inputName="year"
                :isRequired="true"
                type="number"
                min="1" />

            <x-form.inputs.default-input
                labelText="Who met"
                inputName="who_met" />
        </div>
    </div>

    <div class="form__block">
        <div class="form__row">
            <x-form.textareas.default-textarea
                labelText="Plan"
                inputName="plan" />

            <x-form.textareas.default-textarea
                labelText="Topic"
                inputName="topic" />
        </div>

        <div class="form__row">
            <x-form.textareas.default-textarea
                labelText="Result"
                inputName="result" />

            <x-form.textareas.default-textarea
                labelText="Outside the exhibition"
                inputName="outside_the_exhibition" />
        </div>
    </div>
</x-form-templates.create-template>
