<x-form-templates.edit-template :action="route('dd.order-products.update', $record->id)">
    <div class="form__block">
        <div class="form__row">
            <x-form.selects.selectize.boolean-select.record-field-select
                labelText="Layout status"
                field="new_layout"
                :model="$record"
                :true-option-label="__('New')"
                :false-option-label="__('No changes')"
                :isRequired="true" />

            <x-form.inputs.record-field-input
                labelText="Layout sent date"
                field="date_of_sending_new_layout_to_manufacturer"
                :model="$record"
                type="date"
                :initial-value="$record->date_of_sending_new_layout_to_manufacturer?->format('Y-m-d')" />

            <x-form.inputs.record-field-input
                labelText="Box article"
                field="box_article"
                :model="$record" />
        </div>

        <div class="form__row">
            <x-form.inputs.record-field-input
                :form-group-class="$record->new_layout ? 'order-1' : 'order-3 form-group--hidden-visibility'"
                labelText="Print proof receive date"
                field="date_of_receiving_print_proof_from_manufacturer"
                :model="$record"
                class="transition-0"
                type="date"
                :initial-value="$record->date_of_receiving_print_proof_from_manufacturer?->format('Y-m-d')" />

            <x-form.inputs.record-field-input
                form-group-class="order-2"
                labelText="Layout approved date"
                field="layout_approved_date"
                :model="$record"
                type="date"
                :initial-value="$record->layout_approved_date?->format('Y-m-d')" />

            <div class="form-group" style="order: 4"></div>
        </div>
    </div>

    <div class="form__block">
        <x-form.misc.comment-inputs-on-model-edit :record="$record" />
    </div>
</x-form-templates.edit-template>
