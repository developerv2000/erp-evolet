<x-form-templates.edit-template class="export-batches-edit-form" :action="route('export.batches.update', $record->id)">
    @foreach ($record->assemblages as $assemblage)
        <div class="form__block">
            <h2 class="main-title main-title--marginless">{{ __('Assemblage №') }} {{ $assemblage->number }}</h2>

            <input type="hidden" name="assemblages[{{ $assemblage->id }}][id]" value="{{ $assemblage->id }}" />

            <div class="form__row">
                <x-form.inputs.record-field-input
                    labelText="Quantity for assembly"
                    field="assemblages[{{ $assemblage->id }}][quantity_for_assembly]"
                    :model="$assemblage"
                    type="number"
                    min="0"
                    :is-required="true"
                    :initial-value="$assemblage->pivot->quantity_for_assembly" />

                <x-form.inputs.record-field-input
                    labelText="Additional comment"
                    field="assemblages[{{ $assemblage->id }}][additional_comment]"
                    :model="$assemblage"
                    :initial-value="$assemblage->pivot->additional_comment" />
            </div>
        </div>
    @endforeach

    <div class="form__block">
        <x-form.misc.comment-inputs-on-model-edit :record="$record" />
    </div>
</x-form-templates.edit-template>
