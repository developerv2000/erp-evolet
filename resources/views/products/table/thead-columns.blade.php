@switch($column['name'])
    {{-- Edit --}}
    @case('Edit')
        <x-tables.partials.th.edit />
    @break

    {{-- Links --}}
    @case('ID')
        <x-tables.partials.th.id />
    @break

    @case('Manufacturer')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="manufacturer_id" />
    @break

    @case('Generic')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="inn_id" />
    @break

    @case('Form')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="form_id" />
    @break

    @case('Dosage')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="dosage" />
    @break

    @case('MOQ')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="moq" />
    @break

    @case('Shelf life')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="shelf_life_id" />
    @break

    @case('Product class')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="class_id" />
    @break

    @case('Manufacturer Brand')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="brand" />
    @break

    @case('Bioequivalence')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="bioequivalence" />
    @break

    @case('Validity period')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="validity_period" />
    @break

    @case('Down payment')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="down_payment" />
    @break

    @case('Registered in EU')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="registered_in_eu" />
    @break

    @case('Sold in EU')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="sold_in_eu" />
    @break

    @case('Date of creation')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="created_at" />
    @break

    @case('Update date')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="updated_at" />
    @break

    {{-- Static text --}}

    @default
        <div title="{{ __($column['name']) }}">{{ __($column['name']) }}</div>
    @break
@endswitch
