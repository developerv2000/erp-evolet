<x-form-templates.edit-template
    :action="route('profile.update')"
    id="profile-update-form"
    submitIcon="face">

    <div class="form__block">
        <h1 class="main-title main-title--marginless">{{ __('Personal data') }}</h1>

        <div class="form__row">
            <x-form.inputs.record-field-input
                labelText="Name"
                field="name"
                :model="$record"
                :isRequired="true" />

            <x-form.inputs.record-field-input
                labelText="Email address"
                field="email"
                type="email"
                :model="$record"
                :isRequired="true" />

            <x-form.inputs.default-input
                labelText="Photo"
                inputName="photo"
                type="file"
                accept=".png, .jpg, .jpeg" />
        </div>
    </div>
</x-form-templates.edit-template>
