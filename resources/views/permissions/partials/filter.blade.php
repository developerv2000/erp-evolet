<x-filter.layout>
    <x-form.selects.selectize.id-based-multiple-select.request-based-select
        labelText="Name"
        inputName="id[]"
        :options="$permissions" />

    <x-form.selects.selectize.id-based-multiple-select.request-based-select
        labelText="Roles"
        inputName="roles[]"
        :options="$roles" />

    <x-form.selects.selectize.id-based-multiple-select.request-based-select
        labelText="Department"
        inputName="department_id[]"
        optionCaptionField="abbreviation"
        :options="$departments" />

    <x-form.selects.selectize.boolean-select.request-based-select
        labelText="Global"
        inputName="global" />

    {{-- Default filter inputs --}}
    <x-filter.partials.default-inputs :exclude="['created_at', 'updated_at', 'id']" />
</x-filter.layout>
