<div>
    <x-tooltip id="favorite" placement="right" message="{{ ($favorited) ? trans('header.favorite.added_favorite') : trans('header.favorite.add_favorite') }}">
        <span
            id="{{ $favorited ? 'remove-from-favorite' : 'add-to-favorite' }}"
            @class([
                'flex items-center text-purple text-2xl ltr:ml-2 rtl:mr-2 cursor-pointer',
                'material-icons-outlined transform transition-all hover:scale-125' => ($favorited) ? false : true,
                'material-icons' => (! $favorited) ? false : true,
            ])
            wire:click="changeStatus()"
        >grade</span>
    </x-tooltip>
</div>
