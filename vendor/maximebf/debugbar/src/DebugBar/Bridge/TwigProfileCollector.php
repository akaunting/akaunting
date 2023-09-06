<?php
/*
 * This file is part of the DebugBar package.
 *
 * (c) 2017 Tim Riemenschneider
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DebugBar\Bridge;

use DebugBar\DataCollector\AssetProvider;
use DebugBar\DataCollector\DataCollector;
use DebugBar\DataCollector\Renderable;

/**
 * Collects data about rendered templates
 *
 * http://twig.sensiolabs.org/
 *
 * A Twig_Extension_Profiler should be added to your Twig_Environment
 * The root-Twig_Profiler_Profile-object should then be injected into this collector
 *
 * you can optionally provide the Twig_Environment or the Twig_Loader to also create
 * debug-links.
 *
 * @see \Twig_Extension_Profiler, \Twig_Profiler_Profile
 *
 * <code>
 * $env = new Twig_Environment($loader); // Or from a PSR11-container
 * $profile = new Twig_Profiler_Profile();
 * $env->addExtension(new Twig_Extension_Profile($profile));
 * $debugbar->addCollector(new TwigProfileCollector($profile, $env));
 * // or: $debugbar->addCollector(new TwigProfileCollector($profile, $loader));
 * </code>
 *
 * @deprecated Use `\Debugbar\Bridge\NamespacedTwigProfileCollector` instead for Twig 2.x and 3.x
 */
class TwigProfileCollector extends DataCollector implements Renderable, AssetProvider
{
    /**
     * @var \Twig_Profiler_Profile
     */
    private $profile;
    /**
     * @var \Twig_LoaderInterface
     */
    private $loader;
    /** @var int */
    private $templateCount;
    /** @var int */
    private $blockCount;
    /** @var int */
    private $macroCount;
    /**
     * @var array[] {
     * @var string $name
     * @var int    $render_time
     * @var string $render_time_str
     * @var string $memory_str
     * @var string $xdebug_link
     * }
     */
    private $templates;

    /**
     * TwigProfileCollector constructor.
     *
     * @param \Twig_Profiler_Profile $profile
     * @param \Twig_LoaderInterface|\Twig_Environment $loaderOrEnv
     */
    public function __construct(\Twig_Profiler_Profile $profile, $loaderOrEnv = null)
    {
        $this->profile     = $profile;
        if ($loaderOrEnv instanceof \Twig_Environment) {
            $loaderOrEnv = $loaderOrEnv->getLoader();
        }
        $this->loader = $loaderOrEnv;
    }

    /**
     * Returns a hash where keys are control names and their values
     * an array of options as defined in {@see DebugBar\JavascriptRenderer::addControl()}
     *
     * @return array
     */
    public function getWidgets()
    {
        return array(
            'twig'       => array(
                'icon'    => 'leaf',
                'widget'  => 'PhpDebugBar.Widgets.TemplatesWidget',
                'map'     => 'twig',
                'default' => json_encode(array('templates' => array())),
            ),
            'twig:badge' => array(
                'map'     => 'twig.badge',
                'default' => 0,
            ),
        );
    }

    /**
     * @return array
     */
    public function getAssets()
    {
        return array(
            'css' => 'widgets/templates/widget.css',
            'js'  => 'widgets/templates/widget.js',
        );
    }

    /**
     * Called by the DebugBar when data needs to be collected
     *
     * @return array Collected data
     */
    public function collect()
    {
        $this->templateCount = $this->blockCount = $this->macroCount = 0;
        $this->templates     = array();
        $this->computeData($this->profile);

        return array(
            'nb_templates'                => $this->templateCount,
            'nb_blocks'                   => $this->blockCount,
            'nb_macros'                   => $this->macroCount,
            'templates'                   => $this->templates,
            'accumulated_render_time'     => $this->profile->getDuration(),
            'accumulated_render_time_str' => $this->getDataFormatter()->formatDuration($this->profile->getDuration()),
            'memory_usage_str'            => $this->getDataFormatter()->formatBytes($this->profile->getMemoryUsage()),
            'callgraph'                   => $this->getHtmlCallGraph(),
            'badge'                       => implode(
                '/',
                array(
                    $this->templateCount,
                    $this->blockCount,
                    $this->macroCount,
                )
            ),
        );
    }

    /**
     * Returns the unique name of the collector
     *
     * @return string
     */
    public function getName()
    {
        return 'twig';
    }

    public function getHtmlCallGraph()
    {
        $dumper = new \Twig_Profiler_Dumper_Html();

        return $dumper->dump($this->profile);
    }

    /**
     * Get an Xdebug Link to a file
     *
     * @return array {
     *  @var string url
     *  @var bool ajax
     * }
     */
    public function getXdebugLink($template, $line = 1)
    {
        if (is_null($this->loader)) {
            return null;
        }
        $file = $this->loader->getSourceContext($template)->getPath();

        return parent::getXdebugLink($file, $line);
    }

    private function computeData(\Twig_Profiler_Profile $profile)
    {
        $this->templateCount += ($profile->isTemplate() ? 1 : 0);
        $this->blockCount    += ($profile->isBlock() ? 1 : 0);
        $this->macroCount    += ($profile->isMacro() ? 1 : 0);
        if ($profile->isTemplate()) {
            $this->templates[] = array(
                'name'            => $profile->getName(),
                'render_time'     => $profile->getDuration(),
                'render_time_str' => $this->getDataFormatter()->formatDuration($profile->getDuration()),
                'memory_str'      => $this->getDataFormatter()->formatBytes($profile->getMemoryUsage()),
                'xdebug_link'     => $this->getXdebugLink($profile->getTemplate()),
            );
        }
        foreach ($profile as $p) {
            $this->computeData($p);
        }
    }
}
