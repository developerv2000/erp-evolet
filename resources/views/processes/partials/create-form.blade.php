<x-form-templates.create-template class="processes-create-form" :action="route('processes.store')">
    <input type="hidden" name="product_id" value="{{ $product->id }}">

    {{-- Product edit block --}}
    <div class="form__block">
        <h3 class="main-title main-title--marginless">{{ __('Product') }}</h3>

        <div class="form__row">
            <x-form.selects.selectize.id-based-single-select.record-field-select
                labelText="Form"
                field="form_id"
                :model="$product"
                :options="$productForms"
                :isRequired="true" />

            <x-form.inputs.record-field-input
                class="specific-formatable-input"
                labelText="Dosage"
                field="dosage"
                :model="$product"
                isRequired="{{ $product->dosage ? true : false }}" />

            <x-form.inputs.record-field-input
                class="specific-formatable-input"
                labelText="Pack"
                field="pack"
                :model="$product"
                isRequired="{{ $product->pack ? true : false }}" />
        </div>

        <div class="form__row">
            <x-form.selects.selectize.id-based-single-select.record-field-select
                labelText="Shelf life"
                field="shelf_life_id"
                :model="$product"
                :options="$shelfLifes"
                :isRequired="true" />

            <x-form.selects.selectize.id-based-single-select.record-field-select
                labelText="Product class"
                field="class_id"
                :model="$product"
                :options="$productClasses"
                :isRequired="true" />

            <div class="form-group"></div>
        </div>
    </div>

    {{-- Main block --}}
    <div class="form__block">
        <h3 class="main-title main-title--marginless">{{ __('Main') }}</h3>

        <div class="form__row">
            <x-form.selects.selectize.id-based-single-select.default-select
                labelText="Product status"
                inputName="status_id"
                :options="$statuses"
                :isRequired="true" />

            <x-form.selects.selectize.id-based-multiple-select.default-select
                labelText="Search country"
                inputName="country_ids[]"
                :options="$countriesOrderedByUsageCount"
                optionCaptionField="code"
                :isRequired="true" />

            <x-form.selects.selectize.id-based-multiple-select.default-select
                labelText="Responsible"
                inputName="responsiblePeople[]"
                :options="$responsiblePeople"
                :isRequired="true" />
        </div>
    </div>

    {{-- Forecast inputs wrapper hidden initially  --}}
    <div class="processes-create__forecast-inputs-wrapper">@include('processes.partials.create-form-forecast-inputs', ['stage' => 1, 'selectedCountryCodes' => []])</div>

    {{-- Stage inputs wrapper hidden initially  --}}
    <div class="processes-create__stage-inputs-wrapper">@include('processes.partials.create-form-stage-inputs', ['stage' => 1])</div>

    <div class="form__block">
        <x-form.misc.comment-inputs-on-model-create />
    </div>
</x-form-templates.create-template>
