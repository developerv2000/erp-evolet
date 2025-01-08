@extends('layouts.app', [
    'pageTitle' => __('Comment') . ' #' . $record->id,
    'pageName' => 'comments-edit',
    'mainAutoOverflowed' => false,
])

@section('content')
    {{-- Toolbar --}}
    <div class="toolbar">
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

    <x-form-templates.edit-template action="{{ route('comments.update', $record->id) }}">
        <div class="form__block">
            <x-form.simditor-textareas.record-field-textarea
                labelText="Text"
                field="body"
                :model="$record"
                :isRequired="true" />

            <x-form.inputs.record-field-input
                labelText="Date of creation"
                field="created_at"
                :model="$record"
                :isRequired="true" />
        </div>
    </x-form-templates.edit-template>
@endsection
