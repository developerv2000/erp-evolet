{{-- ID based Multiselect --}}
<x-form.selects.selectize.id-based-multiple-select.default-select
    labelText="Zones"
    inputName="zones[]"
    :options="$zones"
    :initialValues="$defaultSelectedZoneIDs"
    :isRequired="true" />

{{-- Simple Multiselect --}}
<x-form.selects.selectize.multiple-select.default-select
    labelText="Presence"
    inputName="presences[]"
    :taggable="true"
    :options="[]" />

{{-- ID based Single select --}}
<x-form.selects.selectize.id-based-single-select.default-select
    labelText="Category"
    inputName="category_id"
    :options="$categories"
    :isRequired="true" />

{{-- Simple Single select --}}
<x-form.selects.selectize.single-select.default-select
    labelText="Region"
    inputName="region"
    :options="$regions"
    :isRequired="true" />

{{-- Input --}}
<x-form.inputs.default-input
    labelText="Manufacturer"
    inputName="name"
    :isRequired="true" />

{{-- Radiobutton --}}
<x-form.radio-buttons.default-radio-buttons
    class="radio-group--horizontal"
    labelText="Status"
    inputName="active"
    :options="$statusOptions"
    :initialValue="true"
    :isRequired="true" />

{{-- Textarea --}}
<x-form.textareas.default-textarea
    labelText="About"
    inputName="about" />
