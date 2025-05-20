<x-form-templates.create-template class="create-comments-form styled-box" :action="route('comments.store')" id="create-comments-form">
    @csrf
    <input type="hidden" name="commentable_type" value="{{ get_class($record) }}">
    <input type="hidden" name="commentable_id" value="{{ $record->id }}">

    <h1 class="main-title main-title--marginless">{{ __('Add new comment') }}</h1>

    <x-form.simditor-textareas.default-textarea
        class="simditor--image-uploadable"
        data-image-upload-folder="img/comments"
        labelText="Comment"
        inputName="body"
        :isRequired="true" />
</x-form-templates.create-template>
