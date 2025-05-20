@if ($record->comments->count())
    <div class="comments-list-wrapper styled-box">
        <h2 class="comments-list-title main-title">{{ __('All comments') . ' — ' . $record->comments->count() }}</h2>

        <div class="comments-list">
            @foreach ($record->comments as $comment)
                <div class="comments-list__item">
                    <div class="comments-list__header">
                        <x-misc.ava
                            class="comments-list__ava"
                            image="{{ $comment->user->photo_asset_url }}"
                            title="{{ $comment->user->name }}"
                            description="{{ $comment->created_at->diffForHumans() }}">
                        </x-misc.ava>

                        @can('edit-comments')
                            <div class="comments-list__actions">
                                <x-misc.buttoned-link
                                    style="main"
                                    class="button--rounded"
                                    link="{{ route('comments.edit', $comment->id) }}"
                                    icon="edit" />

                                <x-misc.button
                                    style="danger"
                                    class="button--rounded"
                                    icon="delete"
                                    data-click-action="show-target-delete-modal"
                                    :data-form-action="route('comments.destroy')"
                                    :data-target-id="$comment->id" />
                            </div>
                        @endcan
                    </div>

                    <div class="comments-list__item-body simditor-text">
                        {!! $comment->body !!}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
