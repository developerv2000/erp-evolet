<x-tables.template.main-template :records="$records" :includePagination="true">
    {{-- thead titles --}}
    <x-slot:thead-titles>
        <th width="50"><x-tables.partials.th.select-all /></th>

        @can('edit-MAD-ASP')
            <th width="40"><x-tables.partials.th.edit /></th>
        @endcan

        <th width="40">
            <x-misc.material-symbol class="th__iconed-title unselectable" icon="visibility" title="{{ __('View') }}" />
        </th>

        <th width="80">{{ __('Year') }}</th>
        <th width="100">{{ __('Кк план') }}</th>
        <th width="100">{{ __('Кк факт') }}</th>
        <th width="100">{{ __('Кк %') }}</th>
        <th width="100">{{ __('НПР факт') }}</th>
        <th width="100">{{ __('НПР %') }}</th>

        <th width="120">{{ __('Countries') }}</th>

        <th width="132">{{ __('Comments') }}</th>
        <th width="240">{{ __('Last comment') }}</th>
    </x-slot:thead-titles>

    {{-- tbody rows --}}
    <x-slot:tbody-rows>
        @foreach ($records as $record)
            <tr>
                <td><x-tables.partials.td.checkbox :value="$record->id" /></td>

                @can('edit-MAD-ASP')
                    <td><x-tables.partials.td.edit :link="route('mad-asp.edit', $record->year)" /></td>
                @endcan

                <td><x-tables.partials.td.view :link="route('mad-asp.show', $record->id)" /></td>

                <td>{{ $record->year }}</td>
                <td>{{ $record->year_contract_plan }}</td>
                <td>{{ $record->year_contract_fact }}</td>
                <td>{{ $record->year_contract_fact_percentage }} %</td>
                <td>{{ $record->year_register_fact }}</td>
                <td>{{ $record->year_register_fact_percentage }} %</td>

                <td>
                    <a class="main-link" href="{{ route('mad-asp.countries.index', $record->year) }}">{{ $record->countries_count }} {{ __('countries') }}</a>
                </td>

                <td>
                    <x-tables.partials.td.model-comments-link :record="$record" />
                </td>

                <td>
                    <x-tables.partials.td.max-lines-limited-text :text="$record->lastComment?->plain_text" />
                </td>
            </tr>
        @endforeach
    </x-slot:tbody-rows>
</x-tables.template.main-template>
