<x-form id="form-email" :route="[$store_route, $transaction->id]">
    <x-form.section>
        <x-slot name="body">
            <x-form.group.text name="to" label="{{ trans('general.to') }}" value="{{ $transaction->contact->email }}" form-group-class="sm:col-span-6" />

            <x-form.group.text name="subject" label="{{ trans('settings.email.templates.subject') }}" value="{{ $notification->getSubject() }}" form-group-class="sm:col-span-6" />

            <x-form.group.editor name="body" label="{{ trans('settings.email.templates.body') }}" :value="$notification->getBody()" rows="5" data-toggle="quill" form-group-class="sm:col-span-6 mb-0" />

            <x-form.group.checkbox name="user_email" :options="['1' => trans('general.email_send_me', ['email' => user()->email])]" checkbox-class="col-span-6" />

            <x-form.input.hidden name="transaction_id" :value="$transaction->id" />
        </x-slot>
    </x-form.section>
</x-form>
