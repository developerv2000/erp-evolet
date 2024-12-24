<form class="create-comments-form styled-box" action="{{ route('comments.store') }}" method="POST">
    @csrf
    <input type="hidden" name="commentable_type" value="{{ get_class($record) }}">
    <input type="hidden" name="commentable_id" value="{{ $record->id }}">

    <h1 class="main-title">{{ __('Add new comment') }}</h1>

    <div class="form-group">
        <x-form.textareas.default-textarea
            class="simditor simditor--imaged"
            labelText="Comment"
            inputName="body"
            :isRequired="true" />
    </div>

    <x-misc.button type="submit" class="create-comments-form__submit" icon="done_all">{{ __('Store') }}</x-misc.button>
</form>
