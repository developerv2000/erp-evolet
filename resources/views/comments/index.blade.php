@extends('layouts.app', [
    'pageName' => 'comments-index',
    'mainAutoOverflowed' => false,
])

@section('content')
    <div class="main-box">
        {{-- Toolbar --}}
        <div class="toolbar">
            {{-- blade-formatter-disable --}}
            @php
                $crumbs = [
                    ['link' => null, 'text' => $record->getCommentsPageTitle()],
                    ['link' => null, 'text' => __('Comments') . ' — ' . $record->comments->count()]
                ];
            @endphp
            {{-- blade-formatter-enable --}}

            <x-layouts.breadcrumbs :crumbs="$crumbs" />
        </div>

        @include('comments.partials.create-form')
        @include('comments.partials.list')

        {{-- Modals --}}
        <x-modals.target-delete :forceDelete="false" />
    </div>
@endsection
