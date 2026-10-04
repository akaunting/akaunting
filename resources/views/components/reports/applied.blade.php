{{-- Print, PDF and Excel have no filter bar, so the chips are listed with the values the report used, then any saved preference that differs from its default and any other search term. A table, so the Excel export keeps it above the report; data-type="s" keeps each cell text, never a date, number or formula. --}}
@if (! empty($class->applied_filters))
    <table class="rp-applied">
        <tbody>
            @foreach ($class->applied_filters as [$label, $value])
                <tr>
                    <th scope="row" data-type="s">{{ $label }}</th>
                    <td data-type="s" @if (! empty($colspan)) colspan="{{ $colspan }}" @endif>{{ $value }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif
