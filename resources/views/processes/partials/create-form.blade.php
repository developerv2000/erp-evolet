<x-form-templates.create-template class="processes-create-form" :action="route('processes.store')">
    <input type="hidden" name="product_id" value="{{ $product->id }}">

    {{-- Edit product block --}}
    @include('processes.partials.edit-product-form-block')

    {{-- Main block --}}
    <div class="form__block">
        <h3 class="main-title main-title--marginless">{{ __('Main') }}</h3>

        <div class="form__row">
            <x-form.selects.selectize.id-based-single-select.default-select
                labelText="Status"
                inputName="status_id"
                :options="$restrictedStatuses"
                :initialValue="$defaultSelectedStatusIDs"
                :isRequired="true" />

            <x-form.selects.selectize.id-based-multiple-select.default-select
                labelText="Search country"
                inputName="country_ids[]"
                :options="$countriesOrderedByProcessesCount"
                optionCaptionField="code"
                :isRequired="true" />

            <x-form.selects.selectize.id-based-single-select.default-select
                labelText="Responsible"
                inputName="responsible_person_id"
                :options="$responsiblePeople"
                :isRequired="true" />
        </div>

        <div class="form__row">
            <x-form.inputs.default-input
                labelText="Historical date"
                inputName="created_at"
                type="datetime-local" />

            <div class="form-group"></div>
            <div class="form-group"></div>
        </div>
    </div>

    {{-- Forecast inputs wrapper hidden initially  --}}
    <div class="processes-create__forecast-inputs-wrapper">@include('processes.partials.create-form-forecast-inputs', ['stage' => 1, 'selectedCountryCodes' => []])</div>

    {{-- Stage inputs wrapper. Same class for create/edit/duplicate  --}}
    <div class="processes-stage-inputs-wrapper">@include('processes.partials.create-form-stage-inputs', ['stage' => 1])</div>

    <div class="form__block">
        <x-form.misc.comment-inputs-on-model-create />
    </div>
</x-form-templates.create-template>
