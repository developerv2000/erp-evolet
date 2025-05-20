@extends('layouts.app', [
    'pageTitle' => $record->name . ' — ' . __('Users'),
    'pageName' => 'users-edit',
    'mainAutoOverflowed' => false,
])

@section('content')
    <div class="main-box">
        {{-- Toolbar --}}
        <div class="toolbar">
            {{-- blade-formatter-disable --}}
            @php
                $crumbs = [
                    ['link' => route('users.index'), 'text' => __('Users')],
                    ['link' => null, 'text' => $record->name]
                ];
            @endphp
            {{-- blade-formatter-enable --}}

            <x-layouts.breadcrumbs :crumbs="$crumbs" />
        </div>

        {{-- Edit form --}}
        @include('MGMT.users.partials.edit-form')
    </div>
@endsection
