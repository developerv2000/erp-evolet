<x-form-templates.edit-template
    action="{{ route('processes.status-history.update', ['process' => $process->id, 'record' => $record->id]) }}">

    <div class="form__block">
        <div class="form__row">
            @unless ($record->isActiveStatusHistory())
                <x-form.selects.selectize.id-based-single-select.record-field-select
                    labelText="Status"
                    field="status_id"
                    :model="$record"
                    :options="$statuses"
                    :isRequired="true" />
            @endunless

            <x-form.inputs.record-field-input
                labelText="Start date"
                field="start_date"
                :model="$record"
                type="datetime-local"
                :initialValue="$record->formatForDateTimeInput('start_date')"
                :isRequired="true" />

            @unless ($record->isActiveStatusHistory())
                <x-form.inputs.record-field-input
                    labelText="End date"
                    field="end_date"
                    :model="$record"
                    type="datetime-local"
                    :initialValue="$record->formatForDateTimeInput('end_date')"
                    :isRequired="true" />
            @endunless
        </div>
    </div>
</x-form-templates.edit-template>
