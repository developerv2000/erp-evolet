@if ($errors->any())
    <div class="errors styled-box">
        <p class="errors__title main-title">{{ __('Error') }}! {{ __('Please fix the errors and try again') }}:</p>

        <ol class="errors__list">
            @foreach ($errors->all() as $error)
                <li class="errors__list-item">{{ $loop->iteration }}. {{ $error }}</li>
            @endforeach
        </ol>
    </div>
@endif
