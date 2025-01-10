{{-- ID based Multiple select --}}
<x-form.selects.selectize.id-based-multiple-select.record-relation-select
    labelText="Zones"
    inputName="zones[]"
    :model="$record"
    :options="$zones"
    :isRequired="true" />

{{-- Multiple select --}}
<x-form.selects.selectize.multiple-select.record-field-multi-select
    labelText="Presence"
    field="presence_names_array"
    inputName="presences[]"
    :model="$record"
    :taggable="true"
    :options="$record->presence_names_array" />

{{-- ID based Single select --}}
<x-form.selects.selectize.id-based-single-select.record-field-select
    labelText="Category"
    field="category_id"
    :model="$record"
    :options="$categories"
    :isRequired="true" />

{{-- Readibutton --}}
<x-form.radio-buttons.record-field-radio-buttons
    class="radio-group--horizontal"
    labelText="Status"
    field="active"
    :model="$record"
    :options="$statusOptions"
    :initialValue="true"
    :isRequired="true" />

{{-- Textarea --}}
<x-form.textareas.record-field-textarea
    labelText="About"
    field="about"
    :model="$record" />

{{-- Input --}}
<x-form.inputs.record-field-input
    labelText="Manufacturer"
    field="name"
    :model="$record"
    :isRequired="true" />
