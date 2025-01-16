{{-- Stage 2 (ПО) inputs --}}
@if ($stage >= 2)
    <div class="form__block">
        <div class="form__row">
            <x-form.inputs.default-input
                labelText="Dossier status"
                inputName="dossier_status" />
        </div>
    </div>
@endif

{{-- Stage 3 (АЦ) inputs --}}
@if ($stage >= 3)
@endif

{{-- Stage 4 (СЦ) inputs --}}
@if ($stage >= 4)
@endif
