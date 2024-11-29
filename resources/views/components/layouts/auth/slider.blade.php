@stack('slider_start')
<div class="hidden lg:block md:w-6/12 h-full flex-col h-fit">
    <div class="swiper bg-cover bg-bottom bg-no-repeat h-screen py-24">
        <div class="swiper-container h-full -mt-12">
            <div class="swiper-wrapper">
                <div class="swiper-slide flex justify-center flex-col items-center">
                    <div style="width:450px; height:450px;">
                        <img src="{{ asset('public/img/auth/folder.png') }}" />
                    </div>

                    <h1 class="text-3xl text-black-400 font-bold">
                        {{ trans('auth.information.invoice') }}
                    </h1>
                </div>

                <div class="swiper-slide flex justify-center flex-col items-center">
                    <div style="width:450px; height:450px;">
                        <img src="{{ asset('public/img/auth/chart.png') }}" />
                    </div>

                    <h1 class="text-3xl text-black-400 font-bold">
                        {{ trans('auth.information.reports') }}
                    </h1>
                </div>

                <div class="swiper-slide flex justify-center flex-col items-center">
                    <div style="width:450px; height:450px;">
                        <img src="{{ asset('public/img/auth/client.png') }}" />
                    </div>

                    <h1 class="text-3xl text-black-400 font-bold">
                        {{ trans('auth.information.expense') }}
                    </h1>
                </div>

                <div class="swiper-slide flex justify-center flex-col items-center">
                    <div style="width:450px; height:450px;">
                        <img src="{{ asset('public/img/auth/layout.png') }}" />
                    </div>

                    <h1 class="text-3xl text-black-400 font-bold">
                        {{ trans('auth.information.customize') }}
                    </h1>
                </div>
            </div>

            <div class="swiper-pagination w-full flex justify-center pb-12 gap-1"></div>
        </div>
    </div>
</div>
@stack('slider_end')
