<x-form-templates.create-template class="mad-products-create-form" :action="route('mad.products.store')">
    <div class="form__block">
        <div class="form__row">
            <x-form.selects.selectize.id-based-single-select.default-select
                labelText="Manufacturer"
                inputName="manufacturer_id"
                :options="$manufacturers"
                :isRequired="true" />

            <x-form.selects.selectize.id-based-single-select.default-select
                labelText="Generic"
                inputName="inn_id"
                :options="$inns"
                :isRequired="true" />

            <x-form.selects.selectize.id-based-single-select.default-select
                labelText="Form"
                inputName="form_id"
                :options="$productForms"
                :isRequired="true" />
        </div>
    </div>

    {{-- Container used to hold similar products, on AJAX request --}}
    <div class="form__block similar-records-wrapper"></div>

    {{-- Container used to hold ATX inputs, on AJAX request --}}
    <div class="form__block atx-inputs-wrapper"></div>

    {{-- Multiple dosage & packs holder --}}
    <div class="form__block">
        <x-form.misc.dynamic-rows title="{{ __('Dosage') . ' / ' . __('Pack') }}">
            @include('MAD.products.partials.create-form-dynamic-rows-list-item', ['inputsIndex' => 0])
        </x-form.misc.dynamic-rows>
    </div>

    <div class="form__block">
        <div class="form__row">
            <x-form.selects.selectize.id-based-single-select.default-select
                labelText="Product class"
                inputName="class_id"
                :options="$productClasses"
                :initialValue="$defaultSelectedClassID"
                :isRequired="true" />

            <x-form.selects.selectize.id-based-single-select.default-select
                labelText="Shelf life"
                inputName="shelf_life_id"
                :options="$shelfLifes"
                :initialValue="$defaultSelectedShelfLifeID"
                :isRequired="true" />

            <x-form.inputs.default-input
                labelText="Brand"
                inputName="brand" />
        </div>
    </div>

    <div class="form__block">
        <div class="form__row">
            <x-form.inputs.default-input
                labelText="Dossier"
                inputName="dossier" />

            <x-form.selects.selectize.id-based-multiple-select.default-select
                labelText="Zones"
                inputName="zones[]"
                :options="$zones"
                :initialValues="$defaultSelectedZoneIDs"
                :isRequired="true" />

            <x-form.inputs.default-input
                labelText="Bioequivalence"
                inputName="bioequivalence" />
        </div>

        <div class="form__row">
            <x-form.inputs.default-input
                labelText="Down payment"
                inputName="down_payment" />

            <x-form.inputs.default-input
                labelText="Validity period"
                inputName="validity_period" />

            <x-form.misc.attach-files-input />
        </div>
    </div>

    <div class="form__block">
        <div class="form__row">
            <x-form.radio-buttons.default-radio-buttons
                class="radio-group--horizontal"
                labelText="Registered in EU"
                inputName="registered_in_eu"
                :options="$booleanOptions"
                :initialValue="false"
                :isRequired="true" />

            <x-form.radio-buttons.default-radio-buttons
                class="radio-group--horizontal"
                labelText="Sold in EU"
                inputName="sold_in_eu"
                :options="$booleanOptions"
                :initialValue="false"
                :isRequired="true" />

            <div class="form-group"></div>
        </div>
    </div>

    <div class="form__block">
        <x-form.misc.comment-inputs-on-model-create />
    </div>
</x-form-templates.create-template>
