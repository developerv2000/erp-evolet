@extends('layouts.app', [
    'pageTitle' => $mah->name . ' — ' . __('ASP'),
    'pageName' => 'mad-asp-mahs-edit',
    'mainAutoOverflowed' => false,
])

@section('content')
    <div class="main-box">
        {{-- Toolbar --}}
        <div class="toolbar">
            {{-- blade-formatter-disable --}}
            @php
                $crumbs = [
                    ...$record->generateBreadcrumbs(),
                    ['link' => route('mad.asp.countries.index', $record->year), 'text' => __('Countries')],
                    ['link' => null, 'text' => $country->code],
                    ['link' => route('mad.asp.mahs.index', ['record' => $record->year, 'country' => $country->id]), 'text' => __('MAH')],
                    ['link' => null, 'text' => $mah->name]
                ];
            @endphp
            {{-- blade-formatter-enable --}}

            <x-layouts.breadcrumbs :crumbs="$crumbs" />

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
        @include('MAD.asp.mahs.partials.edit-form')
    </div>

@endsection
