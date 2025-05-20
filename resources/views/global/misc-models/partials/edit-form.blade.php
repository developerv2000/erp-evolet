<x-form-templates.edit-template :action="route('misc-models.update', ['model' => $model['name'], 'id' => $record->id])">
    <div class="form__block">
        <div class="form__row">
            {{-- Special formatter for Inn name input --}}
            <x-form.inputs.record-field-input
                :class="$model['name'] == 'Inn' ? 'specific-formatable-input' : null"
                labelText="Name"
                field="name"
                :model="$record"
                :isRequired="true" />

            @if (in_array('parent_id', $model['attributes']))
                <x-form.selects.selectize.id-based-single-select.record-field-select
                    labelText="Parent"
                    field="parent_id"
                    :model="$record"
                    :options="$parentRecords" />
            @endif

            {{-- For Country model --}}
            @if (in_array('code', $model['attributes']))
                <x-form.inputs.record-field-input
                    labelText="Code"
                    field="code"
                    :model="$record"
                    :isRequired="true" />
            @endif
        </div>
    </div>
</x-form-templates.edit-template>
