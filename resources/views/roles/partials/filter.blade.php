<x-filter.layout>
    <x-form.selects.selectize.id-based-multiple-select.request-based-select
        labelText="Name"
        inputName="id[]"
        :options="$roles" />

    <x-form.selects.selectize.id-based-multiple-select.request-based-select
        labelText="Permissions"
        inputName="permissions[]"
        :options="$permissions" />

    <x-form.selects.selectize.id-based-multiple-select.request-based-select
        labelText="Department"
        inputName="department_id[]"
        optionCaptionField="abbreviation"
        :options="$departments" />

    <x-form.selects.selectize.boolean-select.request-based-select
        labelText="Global"
        inputName="global" />
</x-filter.layout>
