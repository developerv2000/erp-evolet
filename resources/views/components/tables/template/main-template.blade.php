@props(['records', 'includePagination' => true])

<div class="main-table-wrapper thin-scrollbar @if (!$includePagination || ($records && !$records->hasPages())) main-table-wrapper--without-pagination @endif">
    <table {{ $attributes->merge(['class' => 'main-table']) }}>
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
