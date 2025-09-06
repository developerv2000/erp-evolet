@extends('layouts.app', [
    'pageTitle' => $record->title . ' — ' . __('Invoices'),
    'pageName' => 'eld-invoices-edit',
    'mainAutoOverflowed' => false,
])

@section('content')
    <div class="main-box">
        {{-- Toolbar --}}
        <div class="toolbar">
            <x-layouts.breadcrumbs :crumbs="$record->generateBreadcrumbs('ELD')" />

            {{-- Toolbar buttons --}}
            <div class="toolbar__buttons-wrapper">
                <x-misc.button
                    class="toolbar__button"
                    style="shadowed"
                    type="submit"
                    form="edit-form"
                    icon="done_all">{{ __('Update') }}
                </x-misc.button>
            </div>
        </div>

        {{-- Edit form --}}
        @include('ELD.invoices.partials.edit-form')
    </div>

@endsection
