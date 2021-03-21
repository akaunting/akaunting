<?php
/*
 * This file is part of the DebugBar package.
 *
 * (c) 2013 Maxime Bouroumeau-Fuseau
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DebugBar\Bridge\Twig;

use DebugBar\DataCollector\AssetProvider;
use DebugBar\DataCollector\DataCollector;
use DebugBar\DataCollector\Renderable;

/**
 * Collects data about rendered templates
 *
 * http://twig.sensiolabs.org/
 *
 * Your Twig_Environment object needs to be wrapped in a
 * TraceableTwigEnvironment object
 *
 * <code>
 * $env = new TraceableTwigEnvironment(new Twig_Environment($loader));
 * $debugbar->addCollector(new TwigCollector($env));
 * </code>
 * 
 * @deprecated use DebugBar\Bridge\TwigProfileCollector instead
 */
class TwigCollector extends DataCollector implements Renderable, AssetProvider
{
    public function __construct(TraceableTwigEnvironment $twig)
    {
        $this->twig = $twig;
    }

    public function collect()
    {
        $templates = array();
        $accuRenderTime = 0;

        foreach ($this->twig->getRenderedTemplates() as $tpl) {
            $accuRenderTime += $tpl['render_time'];
            $templates[] = array(
                'name' => $tpl['name'],
                'render_time' => $tpl['render_time'],
                'render_time_str' => $this->formatDuration($tpl['render_time'])
            );
        }

        return array(
            'nb_templates' => count($templates),
            'templates' => $templates,
            'accumulated_render_time' => $accuRenderTime,
            'accumulated_render_time_str' => $this->formatDuration($accuRenderTime)
        );
    }

    public function getName()
    {
        return 'twig';
    }

    public function getWidgets()
    {
        return array(
            'twig' => array(
                'icon' => 'leaf',
                'widget' => 'PhpDebugBar.Widgets.TemplatesWidget',
                'map' => 'twig',
                'default' => json_encode(array('templates' => array())),
            ),
            'twig:badge' => array(
                'map' => 'twig.nb_templates',
                'default' => 0
            )
        );
    }

    public function getAssets()
    {
        return array(
            'css' => 'widgets/templates/widget.css',
            'js' => 'widgets/templates/widget.js'
        );
    }
}
