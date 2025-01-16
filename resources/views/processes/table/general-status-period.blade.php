<div class="process-status-periods">
    <div class="process-status-periods__duration">
        {{ $record->general_statuses_with_periods[$arrayKey]->duration_days ?? '0' }} {{ __('days') }}
    </div>

    <hr
        class="process-status-periods__hr process-general-status-{{ $statusStage }}"
        style="width: {{ $record->general_statuses_with_periods[$arrayKey]->duration_days_ratio }}%">

    <div class="process-status-periods__interval">
        @if ($record->general_statuses_with_periods[$arrayKey]->start_date && $record->general_statuses_with_periods[$arrayKey]->end_date)
            {{ $record->general_statuses_with_periods[$arrayKey]->start_date->isoFormat('DD/MM/YYYY') }}
            - {{ $record->general_statuses_with_periods[$arrayKey]->end_date->isoFormat('DD/MM/YYYY') }}
        @endif
    </div>
</div>
