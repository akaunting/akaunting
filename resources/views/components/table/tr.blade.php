<tr class="{{ $class }}" {{ $attributes }} {{ $attributes->has('href') ? 'data-table-list' : '' }}>
    {{ $slot }}
</tr>
