<x-form-templates.edit-template class="processes-edit-form" :action="route('processes.update', $record->id)">
    <input type="hidden" name="process_id" value="{{ $record->id }}">
    <input type="hidden" name="product_id" value="{{ $product->id }}">

    {{-- Edit product block --}}
    @include('processes.partials.edit-product-form-block')

    {{-- Main block --}}
    <div class="form__block">
        <h3 class="main-title main-title--marginless">{{ __('Main') }}</h3>

        <div class="form__row">
            {{-- Check if user is able to edit status --}}
            @if ($record->currentStatusCanBeEditedForAuthtUser())
                <x-form.selects.selectize.id-based-single-select.record-field-select
                    labelText="Product status"
                    field="status_id"
                    :model="$record"
                    :options="$restrictedStatuses"
                    :isRequired="true" />
            @else
                <x-form.inputs.default-input
                    labelText="Product status"
                    inputName="readonly_status_id"
                    initialValue="{{ $record->status->name }}"
                    readonly />
            @endif

            {{-- Country can`t be edited after stage 1 --}}
            @if ($record->status->generalStatus->stage == 1)
                <x-form.selects.selectize.id-based-single-select.record-field-select
                    labelText="Search country"
                    field="country_id"
                    :model="$record"
                    :options="$countriesOrderedByUsageCount"
                    optionCaptionField="code"
                    :isRequired="true" />
            @else
                <x-form.inputs.default-input
                    labelText="Search country"
                    inputName="readonly_country_id"
                    initialValue="{{ $record->searchCountry->code }}"
                    readonly />
            @endif

            <x-form.selects.selectize.id-based-multiple-select.record-relation-select
                labelText="Responsible"
                inputName="responsiblePeople[]"
                :model="$record"
                :options="$responsiblePeople"
                :isRequired="true" />
        </div>
    </div>

    {{-- Stage inputs wrapper. Same class for create/edit/duplicate  --}}
    <div class="processes-stage-inputs-wrapper">@include('processes.partials.edit-form-stage-inputs', ['stage' => $record->status->generalStatus->stage])</div>

    <div class="form__block">
        <x-form.misc.comment-inputs-on-model-edit :record="$record" />
    </div>
</x-form-templates.edit-template>
