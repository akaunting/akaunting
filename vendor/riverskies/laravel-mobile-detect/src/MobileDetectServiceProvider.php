<?php

namespace Riverskies\Laravel\MobileDetect;

use Detection\MobileDetect;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Riverskies\Laravel\MobileDetect\Contracts\BladeDirectiveInterface;
use Riverskies\Laravel\MobileDetect\Directives\AndroidBladeDirective;
use Riverskies\Laravel\MobileDetect\Directives\DesktopBladeDirective;
use Riverskies\Laravel\MobileDetect\Directives\HandheldBladeDirective;
use Riverskies\Laravel\MobileDetect\Directives\iOSBladeDirective;
use Riverskies\Laravel\MobileDetect\Directives\MobileBladeDirective;
use Riverskies\Laravel\MobileDetect\Directives\NotMobileBladeDirective;
use Riverskies\Laravel\MobileDetect\Directives\NotTabletBladeDirective;
use Riverskies\Laravel\MobileDetect\Directives\TabletBladeDirective;

class MobileDetectServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @param MobileDetect $mobileDetect
     */
    public function boot(MobileDetect $mobileDetect)
    {
        $this->registerBladeDirectives($mobileDetect);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('mobile-detect', function ($app) {
            return new MobileDetect;
        });
    }

    /**
     * @param MobileDetect $mobileDetect
     */
    private function registerBladeDirectives(MobileDetect $mobileDetect)
    {
        $this->registerDirective(new DesktopBladeDirective($mobileDetect));
        $this->registerDirective(new HandheldBladeDirective($mobileDetect));
        $this->registerDirective(new TabletBladeDirective($mobileDetect));
        $this->registerDirective(new NotTabletBladeDirective($mobileDetect));
        $this->registerDirective(new MobileBladeDirective($mobileDetect));
        $this->registerDirective(new NotMobileBladeDirective($mobileDetect));
        $this->registerDirective(new AndroidBladeDirective($mobileDetect));
        $this->registerDirective(new iOSBladeDirective($mobileDetect));
    }

    /**
     * @param BladeDirectiveInterface $directive
     */
    private function registerDirective(BladeDirectiveInterface $directive)
    {
        Blade::directive($directive->openingTag(), [$directive, 'openingHandler']);
        Blade::directive($directive->closingTag(), [$directive, 'closingHandler']);
        Blade::directive($directive->alternatingTag(), [$directive, 'alternatingHandler']);
    }
}
