<x-form-templates.create-template :action="route('misc-models.store', $model['name'])">
    <div class="form__block">
        <div class="form__row">
            {{-- Special formatter for Inn name input --}}
            <x-form.inputs.default-input
                :class="$model['name'] == 'Inn' ? 'specific-formatable-input' : null"
                labelText="Name"
                inputName="name"
                :isRequired="true" />

            @if (in_array('parent_id', $model['attributes']))
                <x-form.selects.selectize.id-based-single-select.default-select
                    labelText="Parent"
                    inputName="parent_id"
                    :options="$parentRecords" />
            @endif

            {{-- For Country model --}}
            @if (in_array('code', $model['attributes']))
                <x-form.inputs.default-input
                    labelText="Code"
                    inputName="code"
                    :isRequired="true" />
            @endif
        </div>
    </div>
</x-form-templates.create-template>
