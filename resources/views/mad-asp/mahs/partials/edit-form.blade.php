<x-form-templates.edit-template :action="route('mad-asp.mahs.update', ['record' => $record->year, 'country' => $country->id, 'mah' => $mah->id])">
    <div class="form__block">
        {{-- Important: 'marketing_authorization_holder_id' not 'id' --}}
        <x-form.selects.selectize.id-based-single-select.record-field-select
            labelText="MAH"
            field="marketing_authorization_holder_id"
            :model="$mah"
            :options="$MAHs"
            initialValue="{{ $mah->pivot->marketing_authorization_holder_id }}"
            :isRequired="true" />
    </div>

    @foreach ($months as $month)
        <div class="form__block">
            <h3 class="main-title main-title--marginless">{{ __($month['name']) }}</h3>

            <div class="form__row">
                <x-form.inputs.record-field-input
                    labelText="EU Кк"
                    field="{{ $month['name'] . '_europe_contract_plan' }}"
                    type="number"
                    min="0"
                    initialValue="{{ $mah->pivot->{$month['name'] . '_europe_contract_plan'} }}"
                    :model="$mah"
                    :isRequired="true" />

                <x-form.inputs.record-field-input
                    labelText="IN Кк"
                    field="{{ $month['name'] . '_india_contract_plan' }}"
                    type="number"
                    min="0"
                    initialValue="{{ $mah->pivot->{$month['name'] . '_india_contract_plan'} }}"
                    :model="$mah"
                    :isRequired="true" />
            </div>
        </div>
    @endforeach
</x-form-templates.edit-template>
