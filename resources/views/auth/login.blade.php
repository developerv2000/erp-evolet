@extends('auth.app', ['pageName' => 'login-page'])

@section('content')
    <div class="auth-box styled-box">
        <x-misc.logo class="auth-box__logo" style="light" />
        <h1 class="auth-box__title main-title">{{ __('Account login') }}</h1>

        <form class="form login-form" action="/login" method="POST">
            @csrf

            <x-form.inputs.default-input
                labelText="{{ __('Email address') }}"
                type="email"
                inputName="email"
                :isRequired="true"
                autofocus />

            <x-form.inputs.default-input
                labelText="{{ __('Password') }}"
                type="password"
                inputName="password"
                :isRequired="true"
                autocomplete="current-password"
                minlength="4" />

            <x-misc.button>{{ __('Log in') }}</x-misc.button>
        </form>
    </div>
@endsection
