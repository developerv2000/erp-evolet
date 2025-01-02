@extends('layouts.app', [
    'pageName' => 'comments-index',
    'mainAutoOverflowed' => false,
])

@section('content')
    <div class="main-box">
        {{-- Toolbar --}}
        <div class="toolbar">
            <x-misc.breadcrumbs :crumbs="$breadcrumbs" />
        </div>

        @include('comments.partials.create-form')
        @include('comments.partials.list')

        {{-- Modals --}}
        <x-modals.target-delete :forceDelete="false" />
    </div>
@endsection
