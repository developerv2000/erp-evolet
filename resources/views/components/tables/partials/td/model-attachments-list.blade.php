@foreach ($attachments as $attachment)
    <a class="td__link" href="{{ asset($attachment->file_path) }}" target="_blank">
        {{ $attachment->filename }}
    </a>

    <br>
@endforeach
