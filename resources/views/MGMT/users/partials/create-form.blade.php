<x-form-templates.create-template :action="route('users.store')">
    <div class="form__block">
        <div class="form__row">
            <x-form.inputs.default-input
                labelText="Name"
                inputName="name"
                :isRequired="true" />

            <x-form.inputs.default-input
                labelText="Email"
                inputName="email"
                :isRequired="true" />

            <x-form.inputs.default-input
                labelText="Photo"
                inputName="photo"
                type="file"
                accept=".png, .jpg, .jpeg"
                :isRequired="true" />
        </div>

        <div class="form__row">
            <x-form.selects.selectize.id-based-single-select.default-select
                labelText="Department"
                inputName="department_id"
                :options="$departments"
                optionCaptionField="abbreviation"
                :isRequired="true" />

            <x-form.selects.selectize.id-based-multiple-select.default-select
                labelText="Roles"
                inputName="roles[]"
                :options="$roles"
                :isRequired="true" />

            <x-form.selects.selectize.id-based-multiple-select.default-select
                labelText="Responsible"
                inputName="responsibleCountries[]"
                :options="$countriesOrderedByProcessesCount"
                optionCaptionField="code" />
        </div>

        <div class="form__row">
            <x-form.selects.selectize.id-based-multiple-select.default-select
                labelText="Permissions"
                inputName="permissions[]"
                :options="$permissions" />

            <x-form.inputs.default-input
                labelText="Password"
                inputName="password"
                type="password"
                minlength="4"
                autocomplete="new-password"
                :isRequired="true" />

            <div class="form-group"></div>
        </div>
    </div>
</x-form-templates.create-template>
