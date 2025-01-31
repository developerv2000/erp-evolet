<x-form-templates.edit-template :action="route('mad-asp.update', $record->id)">
    <div class="form__block">
        <div class="form__row">
            <x-form.inputs.record-field-input
                labelText="Year"
                field="year"
                type="number"
                min="1"
                :model="$record"
                :isRequired="true" />
        </div>
    </div>

    <div class="form__block">
        <x-form.misc.comment-inputs-on-model-edit :record="$record" />
    </div>
</x-form-templates.edit-template>
