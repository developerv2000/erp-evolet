<x-form-templates.edit-template
    :action="route('profile.update-password')"
    id="password-update-form"
    submitIcon="key">

    <div class="form__block">
        <h1 class="main-title main-title--marginless">{{ __('Update password') }}</h1>

        <div class="form__row">
            <x-form.inputs.default-input
                labelText="Current password"
                inputName="current_password"
                type="password"
                autocomplete="current-password"
                minlength="4"
                :isRequired="true" />

            <x-form.inputs.default-input
                labelText="New password"
                inputName="new_password"
                type="password"
                autocomplete="new_password"
                minlength="4"
                :isRequired="true" />
        </div>
    </div>
</x-form-templates.edit-template>
