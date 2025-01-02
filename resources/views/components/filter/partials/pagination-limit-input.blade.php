{{-- single-select.request-based-select component is not used, because highlighting is not required for this input --}}
<x-form.groups.wrapped-label-group labelText="{{ __('Records per page') }}">
    <select class="single-selectize" name="pagination_limit">
        {{-- Empty option for placeholder --}}
        <option></option>

        {{-- Loop through the options and generate each option tag --}}
        @foreach ($paginationLimitOptions as $option)
            <option value="{{ $option }}" @selected($option == request()->input('pagination_limit'))>
                {{ $option }}
            </option>
        @endforeach
    </select>
</x-form.groups.wrapped-label-group>
