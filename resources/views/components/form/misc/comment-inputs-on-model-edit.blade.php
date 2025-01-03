@props(['record'])

<div class="form__row">
    <x-form.simditor-textareas.default-textarea
        class="simditor--image-uploadable"
        data-image-upload-folder="img/comments"
        labelText="Add new comment"
        inputName="comment" />

    @if ($record->lastComment)
        <div class="form-group standard-label-group">
            <label class="label">
                <p class="label__text">{{ __('Last comment') }}</p>
            </label>

            <div class="simditor-text edit-form__last-comment">{!! $record->lastComment->body !!}</div>
        </div>
    @endif
</div>
