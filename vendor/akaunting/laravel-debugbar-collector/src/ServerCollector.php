<?php

namespace Akaunting\DebugbarCollector;

use App\Utilities\Info;
use App\Utilities\Installer;
use DebugBar\DataCollector\DataCollector;
use DebugBar\DataCollector\DataCollectorInterface;
use DebugBar\DataCollector\Renderable;

class ServerCollector extends DataCollector implements DataCollectorInterface, Renderable
{
    /**
     * Called by the DebugBar when data needs to be collected
     *
     * @return array Collected data
     */
    public function collect()
    {
        $versions = Info::versions();
        $php_extensions = Info::phpExtensions();
        $requirements = Installer::checkServerRequirements();

        return [
            'PHP' => ! empty($info['php']) ? $info['php'] : '0.0.0',
            'MySQL' => ! empty($info['mysql']) ? $info['mysql'] : '0.0.0',
            'Memory Limit' => ini_get('memory_limit'),
            'Execution Time' => ini_get('max_execution_time'),
            'PHP OS' => PHP_OS,
            'PHP SAPI' => PHP_SAPI,
            'PHP Timezone' => ini_get('date.timezone'),
            'Requirements' => DataCollector::getDefaultVarDumper()->renderVar($requirements),
            'PHP Extensions' => DataCollector::getDefaultVarDumper()->renderVar($php_extensions),
        ];
    }

    /**
     * Returns the unique name of the collector
     *
     * @return string
     */
    public function getName()
    {
        return 'server';
    }

    public function getWidgets()
    {
        return [
            "server" => [
                "title" => "Server",
                "icon" => "archive",
                "widget" => "PhpDebugBar.Widgets.HtmlVariableListWidget",
                "map" => "server",
                "default" => "{}",
            ],
        ];
    }
}
