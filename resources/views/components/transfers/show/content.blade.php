<div class="flex flex-col lg:flex-row my-10 lg:space-x-24 rtl:space-x-reverse space-y-12 lg:space-y-0">
    <div class="w-full lg:w-5/12 space-y-12">
        <x-transfers.show.create :model="$transfer" />

        <x-transfers.show.transactions :model="$transfer" />

        <x-transfers.show.attachment :model="$transfer" />
    </div>

    <div class="w-full lg:w-7/12">
        <div class="p-3 sm:p-7 shadow-2xl rounded-2xl">
            <x-transfers.show.template :model="$transfer" />
        </div>
    </div>

    <x-form.input.hidden name="transfer_id" :value="$transfer->id" />
</div>
