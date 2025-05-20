{{-- Personal data --}}
<x-form-templates.edit-template id="personal-data-form" :action="route('users.update', $record->id)" submitIcon="face">
    <div class="form__block">
        <h1 class="main-title main-title--marginless">{{ __('Personal data') }}</h1>

        <div class="form__row">
            <x-form.inputs.record-field-input
                labelText="Name"
                field="name"
                :model="$record"
                :isRequired="true" />

            <x-form.inputs.record-field-input
                labelText="Email"
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

        <div class="form__row">
            <x-form.selects.selectize.id-based-single-select.record-field-select
                labelText="Department"
                field="department_id"
                :model="$record"
                :options="$departments"
                optionCaptionField="abbreviation"
                :isRequired="true" />

            <x-form.selects.selectize.id-based-multiple-select.record-relation-select
                labelText="Roles"
                inputName="roles[]"
                :model="$record"
                :options="$roles"
                :isRequired="true" />

            <x-form.selects.selectize.id-based-multiple-select.record-relation-select
                labelText="Responsible"
                inputName="responsibleCountries[]"
                :model="$record"
                :options="$countriesOrderedByProcessesCount"
                optionCaptionField="code" />
        </div>

        <div class="form__row">
            <x-form.selects.selectize.id-based-multiple-select.record-relation-select
                labelText="Permissions"
                inputName="permissions[]"
                :model="$record"
                :options="$permissions" />
        </div>
    </div>
</x-form-templates.edit-template>

{{-- Passowrd update --}}
<x-form-templates.edit-template id="password-update-form" :action="route('users.update-password', $record->id)" submitIcon="key">
    <div class="form__block">
        <h1 class="main-title main-title--marginless">{{ __('Update password') }}</h1>

        <x-form.inputs.default-input
            labelText="New password"
            inputName="password"
            type="password"
            autocomplete="new_password"
            minlength="4"
            :isRequired="true" />
    </div>
</x-form-templates.edit-template>
