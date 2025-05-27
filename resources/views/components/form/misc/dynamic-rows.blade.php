@props(['title' => null, 'buttonIcon' => 'add', 'buttonText' => __('Add')])

<div class="form__dynamic-rows">
    @if ($title)
        <h3 class="main-title">{{ $title }}</h3>
    @endif

    <div class="form__dynamic-rows-list">{{ $slot }}</div>

    <x-misc.button
        class="form__dynamic-rows-list-add-item-button"
        type="button"
        style="success"
        icon="{{ $buttonIcon }}">{{ $buttonText }}
    </x-misc.button>
</div>
