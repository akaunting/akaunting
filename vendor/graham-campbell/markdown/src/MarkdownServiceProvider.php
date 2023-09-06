<?php

declare(strict_types=1);

/*
 * This file is part of Laravel Markdown.
 *
 * (c) Graham Campbell <hello@gjcampbell.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GrahamCampbell\Markdown;

use GrahamCampbell\Markdown\View\Compiler\CommonMarkCompiler;
use GrahamCampbell\Markdown\View\Directive\CommonMarkDirective;
use GrahamCampbell\Markdown\View\Directive\DirectiveInterface;
use GrahamCampbell\Markdown\View\Engine\BladeCommonMarkEngine;
use GrahamCampbell\Markdown\View\Engine\PhpCommonMarkEngine;
use Illuminate\Contracts\Container\Container;
use Illuminate\Foundation\Application as LaravelApplication;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Engines\CompilerEngine;
use Laravel\Lumen\Application as LumenApplication;
use League\CommonMark\ConverterInterface;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Environment\EnvironmentInterface;
use League\CommonMark\MarkdownConverter;

/**
 * This is the markdown service provider class.
 *
 * @author Graham Campbell <hello@gjcampbell.co.uk>
 */
class MarkdownServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->setupConfig();

        if ($this->app->config->get('markdown.views')) {
            $this->enableCompiler();
            $this->enablePhpEngine();
            $this->enableBladeEngine();
            $this->enableBladeDirective();
        }
    }

    /**
     * Setup the config.
     *
     * @return void
     */
    private function setupConfig(): void
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
    private function enableCompiler(): void
    {
        $app = $this->app;

        $app->view->getEngineResolver()->register('md', function () use ($app): CompilerEngine {
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
    private function enablePhpEngine(): void
    {
        $app = $this->app;

        $app->view->getEngineResolver()->register('phpmd', function () use ($app): PhpCommonMarkEngine {
            $files = $app['files'];
            $converter = $app['markdown.converter'];

            return new PhpCommonMarkEngine($files, $converter);
        });

        $app->view->addExtension('md.php', 'phpmd');
    }

    /**
     * Enable the blade markdown engine.
     *
     * @return void
     */
    private function enableBladeEngine(): void
    {
        $app = $this->app;

        $app->view->getEngineResolver()->register('blademd', function () use ($app): BladeCommonMarkEngine {
            $compiler = $app['blade.compiler'];
            $files = $app['files'];
            $converter = $app['markdown.converter'];

            return new BladeCommonMarkEngine($compiler, $files, $converter);
        });

        $app->view->addExtension('md.blade.php', 'blademd');
    }

    private function enableBladeDirective(): void
    {
        $app = $this->app;

        $app['blade.compiler']->directive('markdown', function (string $markdown): string {
            if ($markdown) {
                return "<?php echo app('markdown.converter')->convert((string) {$markdown})->getContent(); ?>";
            }

            return '<?php ob_start(); ?>';
        });

        $app['blade.compiler']->directive('endmarkdown', function (): string {
            return "<?php echo app('markdown.directive')->render(ob_get_clean()); ?>";
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
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
    private function registerEnvironment(): void
    {
        $this->app->singleton('markdown.environment', function (Container $app): Environment {
            $config = $app->config->get('markdown');

            $environment = new Environment(Arr::except($config, ['extensions', 'views']));

            foreach ((array) Arr::get($config, 'extensions') as $extension) {
                $environment->addExtension($app->make($extension));
            }

            return $environment;
        });

        $this->app->alias('markdown.environment', Environment::class);
        $this->app->alias('markdown.environment', EnvironmentInterface::class);
        $this->app->alias('markdown.environment', EnvironmentBuilderInterface::class);
    }

    /**
     * Register the markdowm class.
     *
     * @return void
     */
    private function registerMarkdown(): void
    {
        $this->app->singleton('markdown.converter', function (Container $app): MarkdownConverter {
            $environment = $app['markdown.environment'];

            return new MarkdownConverter($environment);
        });

        $this->app->alias('markdown.converter', MarkdownConverter::class);
        $this->app->alias('markdown.converter', ConverterInterface::class);
    }

    /**
     * Register the markdown compiler class.
     *
     * @return void
     */
    private function registerCompiler(): void
    {
        $this->app->singleton('markdown.compiler', function (Container $app): CommonMarkCompiler {
            $converter = $app['markdown.converter'];
            $files = $app['files'];
            $storagePath = $app->config->get('view.compiled');

            return new CommonMarkCompiler($converter, $files, $storagePath);
        });

        $this->app->alias('markdown.compiler', CommonMarkCompiler::class);
    }

    /**
     * Register the markdown directive class.
     *
     * @return void
     */
    private function registerDirective(): void
    {
        $this->app->singleton('markdown.directive', function (Container $app): CommonMarkDirective {
            $converter = $app['markdown.converter'];

            return new CommonMarkDirective($converter);
        });

        $this->app->alias('markdown.directive', CommonMarkDirective::class);
        $this->app->alias('markdown.directive', DirectiveInterface::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return string[]
     */
    public function provides(): array
    {
        return [
            'markdown.environment',
            'markdown.converter',
            'markdown.compiler',
            'markdown.directive',
        ];
    }
}
