<?php

declare(strict_types=1);

/*
 * This file is part of Laravel Markdown.
 *
 * (c) Graham Campbell <graham@alt-three.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GrahamCampbell\Markdown;

use GrahamCampbell\Markdown\View\Compiler\MarkdownCompiler;
use GrahamCampbell\Markdown\View\Directive\MarkdownDirective;
use GrahamCampbell\Markdown\View\Engine\BladeMarkdownEngine;
use GrahamCampbell\Markdown\View\Engine\PhpMarkdownEngine;
use Illuminate\Contracts\Container\Container;
use Illuminate\Foundation\Application as LaravelApplication;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Engines\CompilerEngine;
use Laravel\Lumen\Application as LumenApplication;
use League\CommonMark\CommonMarkConverter;
use League\CommonMark\ConfigurableEnvironmentInterface;
use League\CommonMark\Environment;
use League\CommonMark\EnvironmentInterface;
use League\CommonMark\MarkdownConverterInterface;

/**
 * This is the markdown service provider class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class MarkdownServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->setupConfig();

        if ($this->app->config->get('markdown.views')) {
            $this->enableMarkdownCompiler();
            $this->enablePhpMarkdownEngine();
            $this->enableBladeMarkdownEngine();
            $this->enableBladeDirective();
        }
    }

    /**
     * Setup the config.
     *
     * @return void
     */
    protected function setupConfig()
    {
        $source = realpath($raw = __DIR__.'/../config/markdown.php') ?: $raw;

        if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
            $this->publishes([$source => config_path('markdown.php')]);
        } elseif ($this->app instanceof LumenApplication) {
            $this->app->configure('markdown');
        }

        $this->mergeConfigFrom($source, 'markdown');
    }

    /**
     * Enable the markdown compiler.
     *
     * @return void
     */
    protected function enableMarkdownCompiler()
    {
        $app = $this->app;

        $app->view->getEngineResolver()->register('md', function () use ($app) {
            $compiler = $app['markdown.compiler'];

            return new CompilerEngine($compiler);
        });

        $app->view->addExtension('md', 'md');
    }

    /**
     * Enable the php markdown engine.
     *
     * @return void
     */
    protected function enablePhpMarkdownEngine()
    {
        $app = $this->app;

        $app->view->getEngineResolver()->register('phpmd', function () use ($app) {
            $markdown = $app['markdown'];

            return new PhpMarkdownEngine($markdown);
        });

        $app->view->addExtension('md.php', 'phpmd');
    }

    /**
     * Enable the blade markdown engine.
     *
     * @return void
     */
    protected function enableBladeMarkdownEngine()
    {
        $app = $this->app;

        $app->view->getEngineResolver()->register('blademd', function () use ($app) {
            $compiler = $app['blade.compiler'];
            $markdown = $app['markdown'];

            return new BladeMarkdownEngine($compiler, $markdown);
        });

        $app->view->addExtension('md.blade.php', 'blademd');
    }

    protected function enableBladeDirective()
    {
        $app = $this->app;

        $app['blade.compiler']->directive('markdown', function ($markdown) {
            if ($markdown) {
                return "<?php echo app('markdown')->convertToHtml((string) {$markdown}); ?>";
            }

            return '<?php ob_start(); ?>';
        });

        $app['blade.compiler']->directive('endmarkdown', function () {
            return "<?php echo app('markdown.directive')->render(ob_get_clean()); ?>";
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerEnvironment();
        $this->registerMarkdown();
        $this->registerCompiler();
        $this->registerDirective();
    }

    /**
     * Register the environment class.
     *
     * @return void
     */
    protected function registerEnvironment()
    {
        $this->app->singleton('markdown.environment', function (Container $app) {
            $environment = Environment::createCommonMarkEnvironment();

            $config = $app->config->get('markdown');

            $environment->mergeConfig(Arr::except($config, ['extensions', 'views']));

            foreach ((array) Arr::get($config, 'extensions') as $extension) {
                $environment->addExtension($app->make($extension));
            }

            return $environment;
        });

        $this->app->alias('markdown.environment', Environment::class);
        $this->app->alias('markdown.environment', EnvironmentInterface::class);
        $this->app->alias('markdown.environment', ConfigurableEnvironmentInterface::class);
    }

    /**
     * Register the markdowm class.
     *
     * @return void
     */
    protected function registerMarkdown()
    {
        $this->app->singleton('markdown', function (Container $app) {
            $environment = $app['markdown.environment'];

            return new CommonMarkConverter([], $environment);
        });

        $this->app->alias('markdown', CommonMarkConverter::class);
        $this->app->alias('markdown', MarkdownConverterInterface::class);
    }

    /**
     * Register the markdown compiler class.
     *
     * @return void
     */
    protected function registerCompiler()
    {
        $this->app->singleton('markdown.compiler', function (Container $app) {
            $markdown = $app['markdown'];
            $files = $app['files'];
            $storagePath = $app->config->get('view.compiled');

            return new MarkdownCompiler($markdown, $files, $storagePath);
        });

        $this->app->alias('markdown.compiler', MarkdownCompiler::class);
    }

    /**
     * Register the markdown directive class.
     *
     * @return void
     */
    protected function registerDirective()
    {
        $this->app->singleton('markdown.directive', function (Container $app) {
            $markdown = $app['markdown'];

            return new MarkdownDirective($markdown);
        });

        $this->app->alias('markdown.directive', MarkdownDirective::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return string[]
     */
    public function provides()
    {
        return [
            'markdown.environment',
            'markdown',
            'markdown.compiler',
            'markdown.directive',
        ];
    }
}
