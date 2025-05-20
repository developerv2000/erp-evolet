<x-form-templates.create-template :action="route('mad-asp.mahs.store', ['record' => $record->year, 'country' => $country->id])">
    <input type="hidden" name="country_id" value="{{ $country->id }}">

    <div class="form__block">
        <x-form.selects.selectize.id-based-single-select.default-select
            labelText="MAH"
            inputName="marketing_authorization_holder_id"
            :options="$MAHs"
            :isRequired="true" />
    </div>

    @foreach ($months as $month)
        <div class="form__block">
            <h3 class="main-title main-title--marginless">{{ __($month['name']) }}</h3>

            <div class="form__row">
                <x-form.inputs.default-input
                    labelText="EU Кк"
                    inputName="{{ $month['name'] . '_europe_contract_plan' }}"
                    type="number"
                    min="0"
                    initialValue="0"
                    :isRequired="true" />

                <x-form.inputs.default-input
                    labelText="IN Кк"
                    inputName="{{ $month['name'] . '_india_contract_plan' }}"
                    type="number"
                    min="0"
                    initialValue="0"
                    :isRequired="true" />
            </div>
        </div>
    @endforeach
</x-form-templates.create-template>
