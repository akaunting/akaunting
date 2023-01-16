<x-layouts.admin>
    <x-slot name="title">{{ trans('settings.email.email_service') }}</x-slot>

    <x-slot name="content">
        <x-form.container>
            <x-form id="setting" method="PATCH" route="settings.email.update">
                <x-form.section>
                    <x-slot name="head">
                        <x-form.section.head title="{{ trans('general.general') }}" description="{{ trans('settings.email.form_description.general') }}" />
                    </x-slot>

                    <x-slot name="body">
                        <x-form.group.select name="protocol" label="{{ trans('settings.email.protocol') }}" :options="$email_protocols" :selected="setting('email.protocol')" not-required change="onChangeProtocol" />

                        <x-form.group.text name="sendmail_path" label="{{ trans('settings.email.sendmail_path') }}" value="{{ setting('email.sendmail_path') }}" ::disabled="email.sendmailPath" not-required />

                        <x-form.group.text name="smtp_host" label="{{ trans('settings.email.smtp.host') }}" value="{{ setting('email.smtp_host') }}" ::disabled="email.smtpHost" not-required />

                        <x-form.group.text name="smtp_port" label="{{ trans('settings.email.smtp.port') }}" value="{{ setting('email.smtp_port') }}" ::disabled="email.smtpPort" not-required />

                        <x-form.group.text name="smtp_username" label="{{ trans('settings.email.smtp.username') }}" value="{{ setting('email.smtp_username') }}" ::disabled="email.smtpUsername" not-required />

                        <x-form.group.password name="smtp_password" label="{{ trans('settings.email.smtp.password') }}" value="{{ setting('email.smtp_password') }}" ::disabled="email.smtpPassword" not-required />

                        <x-form.group.select name="smtp_encryption" label="{{ trans('settings.email.smtp.encryption') }}" :options="['' => trans('settings.email.smtp.none'), 'ssl' => 'SSL', 'tls' => 'TLS']" :selected="setting('email.smtp_encryption', null)" v-disabled="email.smtpEncryption" not-required />
                    </x-slot>
                </x-form.section>

                @can('update-settings-email')
                <x-form.section>
                    <x-slot name="foot">
                        <x-form.buttons :cancel="url()->previous()" />
                    </x-slot>
                </x-form.section>
                @endcan

                <x-form.input.hidden name="_prefix" value="email" />
            </x-form>
        </x-form.container>
    </x-slot>

    <x-script folder="settings" file="settings" />
</x-layouts.admin>
