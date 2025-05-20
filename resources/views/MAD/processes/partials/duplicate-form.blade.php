<x-form-templates.edit-template class="processes-duplicate-form" :action="route('processes.duplicate')" method="POST" submitText="Diplicate">
    <input type="hidden" name="process_id" value="{{ $record->id }}">
    <input type="hidden" name="product_id" value="{{ $product->id }}">

    {{-- Edit product block --}}
    @include('processes.partials.edit-product-form-block')

    {{-- Main block --}}
    <div class="form__block">
        <h3 class="main-title main-title--marginless">{{ __('Main') }}</h3>

        <div class="form__row">
            <x-form.selects.selectize.id-based-single-select.record-field-select
                labelText="Status"
                field="status_id"
                :model="$record"
                :options="$restrictedStatuses"
                :isRequired="true" />

            <x-form.selects.selectize.id-based-single-select.record-field-select
                labelText="Search country"
                field="country_id"
                :model="$record"
                :options="$countriesOrderedByProcessesCount"
                optionCaptionField="code"
                :isRequired="true" />

            <x-form.selects.selectize.id-based-single-select.record-field-select
                labelText="Responsible"
                field="responsible_person_id"
                :model="$record"
                :options="$responsiblePeople"
                :isRequired="true" />
        </div>
    </div>

    {{-- Stage inputs wrapper. Same class for create/edit/duplicate  --}}
    <div class="processes-stage-inputs-wrapper">@include('processes.partials.duplicate-form-stage-inputs', ['stage' => $record->status->generalStatus->stage])</div>

    <div class="form__block">
        <x-form.misc.comment-inputs-on-model-create />
    </div>
</x-form-templates.edit-template>
