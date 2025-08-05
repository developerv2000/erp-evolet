@props(['records', 'includePagination' => true, 'thTextNowrap' => true])

<div @class([
    'main-table-wrapper',
    'thin-scrollbar',
    'main-table-wrapper--without-pagination' =>
        !$includePagination || ($records && !$records->hasPages()),
])>
    <table {{ $attributes->merge(['class' => 'main-table' . ($thTextNowrap ? ' main-table--th-text-nowrap' : '')]) }}>
        <thead>
            {{ $theadRows }}
        </thead>

        <tbody>
            {{ $tbodyRows }}
        </tbody>
    </table>
</div>

@if ($includePagination && $records->hasPages())
    <div class="pagination-wrapper">
        <x-layouts.navigate-to-page-number :current-page="$records->currentPage()" :last-page="$records->lastPage()" />
        {{ $records->links('layouts.pagination') }}
    </div>
@endif
