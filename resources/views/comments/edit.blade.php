@extends('layouts.app', [
    'pageName' => 'comments-edit',
    'mainAutoOverflowed' => false,
])

@section('content')
    {{-- Toolbar --}}
    <div class="toolbar">
        {{-- blade-formatter-disable --}}
            @php
                $crumbs = [
                    ['link' => null, 'text' => $title],
                    ['link' => null, 'text' => __('Comments') . ' # ' . $record->id]
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

    <x-form-templates.edit-template action="{{ route('comments.update', $record->id) }}">
        <div class="form__block">
            <x-form.simditor-textareas.record-field-textarea
                labelText="Текст"
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
