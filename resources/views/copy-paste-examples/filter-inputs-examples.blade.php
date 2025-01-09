{{-- ID based Multiselect --}}
<x-form.selects.selectize.id-based-multiple-select.request-based-select
    labelText="Generic"
    inputName="inn_id[]"
    :options="$inns" />

{{-- Simple Multiselect --}}
<x-form.selects.selectize.multiple-select.request-based-select
    labelText="Generic"
    inputName="inn_id[]"
    :options="$inns" />

{{-- ID based Single select --}}
<x-form.selects.selectize.id-based-single-select.request-based-select
    labelText="Analyst"
    inputName="analyst_user_id"
    :options="$analystUsers" />

{{-- Simple Single select --}}
<x-form.selects.selectize.single-select.request-based-select
    labelText="Region"
    inputName="region"
    :options="$regions" />

{{-- Boolean elect --}}
<x-form.selects.selectize.boolean-select.request-based-select
    labelText="Status"
    inputName="active"
    :trueOptionLabel="__('Active')"
    :falseOptionLabel="__('Stop/pause')" />

{{-- Input --}}
<x-form.inputs.request-based-input
    labelText="Dosage"
    inputName="dosage" />
