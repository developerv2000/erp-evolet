<x-filter.layout>
    <x-form.selects.selectize.id-based-multiple-select.request-based-select
        labelText="Name"
        inputName="id[]"
        :options="$allRecords" />

    @if (in_array('parent_id', $model['attributes']))
        <x-form.selects.selectize.id-based-multiple-select.request-based-select
            labelText="Parent"
            inputName="parent_id[]"
            :options="$parentRecords" />
    @endif
</x-filter.layout>
