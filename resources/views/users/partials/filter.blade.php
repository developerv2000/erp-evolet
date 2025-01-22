<x-filter.layout>
    <x-form.selects.selectize.id-based-multiple-select.request-based-select
        labelText="Name"
        inputName="id[]"
        :options="$users" />

    <x-form.selects.selectize.id-based-multiple-select.request-based-select
        labelText="Department"
        inputName="department_id[]"
        optionCaptionField="abbreviation"
        :options="$departments" />

    <x-form.selects.selectize.id-based-multiple-select.request-based-select
        labelText="Roles"
        inputName="roles[]"
        :options="$roles" />

    <x-form.selects.selectize.id-based-multiple-select.request-based-select
        labelText="Permissions"
        inputName="permissions[]"
        :options="$permissions" />

    <x-form.selects.selectize.id-based-multiple-select.request-based-select
        labelText="Responsible"
        inputName="responsibleCountries[]"
        :options="$countriesOrderedByProcessesCount"
        optionCaptionField="code" />

    <x-form.inputs.request-based-input
        labelText="Email"
        inputName="email" />

    {{-- Default filter inputs --}}
    <x-filter.partials.default-inputs :exclude="['id']" />
</x-filter.layout>
