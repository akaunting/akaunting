<x-form.section>
    <x-slot name="head">
        <x-form.section.head title="{{ trans($textSectionMainTitle) }}" description="{{ trans($textSectionMainDescription) }}" />
    </x-slot>

    <x-slot name="body">
        <x-documents.form.metadata type="{{ $type }}" />

        <x-documents.form.items type="{{ $type }}" />

        <x-documents.form.totals type="{{ $type }}" />

        <x-documents.form.note type="{{ $type }}" />
    </x-slot>
</x-form.section>