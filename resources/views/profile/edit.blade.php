@extends('layouts.app', [
    'pageTitle' => __('My profile'),
    'pageName' => 'profile-edit',
    'mainAutoOverflowed' => false,
])

@section('content')
    <div class="toolbar">
        {{-- blade-formatter-disable --}}
        @php
            $crumbs = [
                ['link' => null, 'text' => __('My profile')],
                ['link' => null, 'text' => __('Edit')],
            ];
        @endphp
        {{-- blade-formatter-enable --}}

        <x-layouts.breadcrumbs :crumbs="$crumbs" />
    </div>

    {{-- Personal data --}}
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

    {{-- Password --}}
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
@endsection
