<tbody {{ ((! $attributes->has('override')) || ($attributes->has('override') && ! in_array('class', explode(',', $attributes->get('override'))))) ? $attributes->merge(['data-table-body' => 'true']) : $attributes }}>
    {{ $slot }}
</tbody>
