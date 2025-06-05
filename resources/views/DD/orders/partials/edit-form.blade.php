<x-form-templates.edit-template :action="route('dd.orders.update', $record->id)">
    <div class="form__block">
        <div class="form__row">

        </div>
    </div>

    <div class="form__block">
        <x-form.misc.comment-inputs-on-model-edit :record="$record" />
    </div>
</x-form-templates.edit-template>
