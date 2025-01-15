<x-filter.layout>
    <x-form.selects.selectize.id-based-multiple-select.request-based-select
        labelText="Generic"
        inputName="inn_id[]"
        :options="$inns" />

    <x-form.selects.selectize.id-based-multiple-select.request-based-select
        labelText="Form"
        inputName="form_id[]"
        :options="$productForms" />

    <x-form.inputs.request-based-input
        labelText="Dosage"
        inputName="dosage" />

    <x-form.inputs.request-based-input
        labelText="Pack"
        inputName="pack" />

    <x-form.selects.selectize.id-based-multiple-select.request-based-select
        labelText="Country"
        inputName="country_id[]"
        :options="$countriesOrderedByName" />

    <x-form.selects.selectize.id-based-multiple-select.request-based-select
        labelText="Manufacturer"
        inputName="manufacturer_id[]"
        :options="$manufacturers" />

    <x-form.selects.selectize.id-based-single-select.request-based-select
        labelText="Category"
        inputName="manufacturer_category_id"
        :options="$manufacturerCategories" />

    <x-form.selects.selectize.id-based-multiple-select.request-based-select
        labelText="Product class"
        inputName="class_id[]"
        :options="$productClasses" />

    <x-form.selects.selectize.id-based-single-select.request-based-select
        labelText="Analyst"
        inputName="analyst_user_id"
        :options="$analystUsers" />

    <x-form.selects.selectize.id-based-single-select.request-based-select
        labelText="BDM"
        inputName="bdm_user_id"
        :options="$bdmUsers" />

    <x-form.selects.selectize.multiple-select.request-based-select
        labelText="Brand"
        inputName="brand[]"
        :options="$brands" />

    <x-form.selects.selectize.id-based-multiple-select.request-based-select
        labelText="Shelf life"
        inputName="shelf_life_id[]"
        :options="$shelfLifes" />

    <x-form.selects.selectize.id-based-multiple-select.request-based-select
        labelText="Zones"
        inputName="zones[]"
        :options="$zones" />

    {{-- Default filter inputs --}}
    <x-filter.partials.default-inputs />
</x-filter.layout>
