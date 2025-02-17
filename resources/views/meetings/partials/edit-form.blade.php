<x-form-templates.edit-template :action="route('meetings.update', $record->id)">
    <div class="form__block">
        <div class="form__row">
            <x-form.selects.selectize.id-based-single-select.record-field-select
                labelText="Manufacturer"
                field="manufacturer_id"
                :model="$record"
                :options="$manufacturers"
                :isRequired="true" />

            <x-form.inputs.record-field-input
                labelText="Year"
                field="year"
                :model="$record"
                :isRequired="true"
                type="number"
                min="1" />

            <x-form.inputs.record-field-input
                labelText="Who met"
                field="who_met"
                :model="$record" />
        </div>
    </div>

    <div class="form__block">
        <div class="form__row">
            <x-form.textareas.record-field-textarea
                labelText="Plan"
                field="plan"
                :model="$record" />

            <x-form.textareas.record-field-textarea
                labelText="Topic"
                field="topic"
                :model="$record" />
        </div>

        <div class="form__row">
            <x-form.textareas.record-field-textarea
                labelText="Result"
                field="result"
                :model="$record" />

            <x-form.textareas.record-field-textarea
                labelText="Outside the exhibition"
                field="outside_the_exhibition"
                :model="$record" />
        </div>
    </div>
</x-form-templates.edit-template>
