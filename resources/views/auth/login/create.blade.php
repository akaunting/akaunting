<x-layouts.auth>
    <x-slot name="title">
        {{ trans('auth.login') }}
    </x-slot>

    <x-slot name="content">
        <div>
            <img src="{{ asset('public/img/akaunting-logo-green.svg') }}" class="w-16" alt="Akaunting" />

            <h1 class="text-lg my-3">
                {{ trans('auth.login_to') }}
            </h1>
        </div>

        <div :class="(form.response.success) ? 'w-full bg-green-100 text-green-600 p-3 rounded-sm font-semibold text-xs' : 'hidden'"
            v-if="form.response.success"
            v-html="form.response.message"
            v-cloak
        ></div>

        <div :class="(form.response.error) ? 'w-full bg-red-100 text-red-600 p-3 rounded-sm font-semibold text-xs' : 'hidden'"
            v-if="form.response.error"
            v-html="form.response.message"
            v-cloak
        ></div>

        <x-form id="auth" route="login">
            <div class="grid sm:grid-cols-6 gap-x-8 gap-y-6 my-3.5 lg:h-64">
                <x-form.group.email
                    name="email"
                    label="{{ trans('general.email') }}"
                    placeholder="{{ trans('general.email') }}"
                    form-group-class="sm:col-span-6"
                    input-group-class="input-group-alternative"
                />

                <x-form.group.password
                    name="password"
                    label="{{ trans('auth.password.pass') }}"
                    placeholder="{{ trans('auth.password.pass') }}"
                    form-group-class="sm:col-span-6"
                    input-group-class="input-group-alternative"
                />

                <div class="sm:col-span-6 flex flex-row justify-between items-center">
                    @stack('remember_input_start')
                    <div>
                        <x-form.input.checkbox
                            name="remember"
                            label="{{ trans('auth.remember_me') }}"
                            value="1"
                            v-model="form.remember"
                            id="checkbox-remember"
                            class="text-purple focus:outline-none focus:ring-purple focus:border-purple"
                        />
                    </div>
                    @stack('remember_input_end')

                    @stack('forgotten-password-start')
                    <a href="{{ route('forgot') }}" class="text-black-400 hover:text-black-700 text-sm">
                        {{ trans('auth.forgot_password') }}
                    </a>
                    @stack('forgotten-password-end')
                </div>

                <x-button
                    type="submit"
                    ::disabled="form.loading"
                    class="relative flex items-center justify-center bg-green hover:bg-green-700 text-white px-6 py-1.5 text-base rounded-lg disabled:bg-green-100 sm:col-span-6"
                    override="class"
                    data-loading-text="{{ trans('general.loading') }}"
                >
                    <i v-if="form.loading" class="animate-submit delay-[0.28s] absolute w-2 h-2 rounded-full left-0 right-0 -top-3.5 m-auto before:absolute before:w-2 before:h-2 before:rounded-full before:animate-submit before:delay-[0.14s] after:absolute after:w-2 after:h-2 after:rounded-full after:animate-submit before:-left-3.5 after:-right-3.5 after:delay-[0.42s]"></i> 
                    <span :class="[{'opacity-0': form.loading}]">
                        {{ trans('auth.login') }}
                    </span>
                </x-button>
            </div>
        </x-form>
    </x-slot>

    <x-script folder="auth" file="common" />
</x-layouts.auth>
