@extends('layouts.app', [
    'pageTitle' => __('Status history') . ' — ' . $process->title,
    'pageName' => 'mad-process-status-history-edit',
    'mainAutoOverflowed' => false,
])

@section('content')
    {{-- Toolbar --}}
    <div class="toolbar">
        {{-- blade-formatter-disable --}}
        @php
            $crumbs = [
                    ...$process->generateBreadcrumbs(),
                    ['link' => route('mad.processes.status-history.index', $process->id), 'text' => __('Status history')],
                    ['link' => null, 'text' => __('Edit')],
                ];
        @endphp
        {{-- blade-formatter-enable --}}

        <x-layouts.breadcrumbs :crumbs="$crumbs" />

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

    @include('MAD.process-status-history.partials.edit-form')
@endsection
