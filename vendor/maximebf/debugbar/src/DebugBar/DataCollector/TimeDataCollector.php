<?php
/*
 * This file is part of the DebugBar package.
 *
 * (c) 2013 Maxime Bouroumeau-Fuseau
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DebugBar\DataCollector;

use DebugBar\DebugBarException;

/**
 * Collects info about the request duration as well as providing
 * a way to log duration of any operations
 */
class TimeDataCollector extends DataCollector implements Renderable
{
    /**
     * @var float
     */
    protected $requestStartTime;

    /**
     * @var float
     */
    protected $requestEndTime;

    /**
     * @var array
     */
    protected $startedMeasures = array();

    /**
     * @var array
     */
    protected $measures = array();

    /**
     * @param float $requestStartTime
     */
    public function __construct($requestStartTime = null)
    {
        if ($requestStartTime === null) {
            if (isset($_SERVER['REQUEST_TIME_FLOAT'])) {
                $requestStartTime = $_SERVER['REQUEST_TIME_FLOAT'];
            } else {
                $requestStartTime = microtime(true);
            }
        }
        $this->requestStartTime = (float)$requestStartTime;
    }

    /**
     * Starts a measure
     *
     * @param string $name Internal name, used to stop the measure
     * @param string|null $label Public name
     * @param string|null $collector The source of the collector
     */
    public function startMeasure($name, $label = null, $collector = null)
    {
        $start = microtime(true);
        $this->startedMeasures[$name] = array(
            'label' => $label ?: $name,
            'start' => $start,
            'collector' => $collector
        );
    }

    /**
     * Check a measure exists
     *
     * @param string $name
     * @return bool
     */
    public function hasStartedMeasure($name)
    {
        return isset($this->startedMeasures[$name]);
    }

    /**
     * Stops a measure
     *
     * @param string $name
     * @param array $params
     * @throws DebugBarException
     */
    public function stopMeasure($name, $params = array())
    {
        $end = microtime(true);
        if (!$this->hasStartedMeasure($name)) {
            throw new DebugBarException("Failed stopping measure '$name' because it hasn't been started");
        }
        $this->addMeasure(
            $this->startedMeasures[$name]['label'],
            $this->startedMeasures[$name]['start'],
            $end,
            $params,
            $this->startedMeasures[$name]['collector']
        );
        unset($this->startedMeasures[$name]);
    }

    /**
     * Adds a measure
     *
     * @param string $label
     * @param float $start
     * @param float $end
     * @param array $params
     * @param string|null $collector
     */
    public function addMeasure($label, $start, $end, $params = array(), $collector = null)
    {
        $this->measures[] = array(
            'label' => $label,
            'start' => $start,
            'relative_start' => $start - $this->requestStartTime,
            'end' => $end,
            'relative_end' => $end - $this->requestEndTime,
            'duration' => $end - $start,
            'duration_str' => $this->getDataFormatter()->formatDuration($end - $start),
            'params' => $params,
            'collector' => $collector
        );
    }

    /**
     * Utility function to measure the execution of a Closure
     *
     * @param string $label
     * @param \Closure $closure
     * @param string|null $collector
     * @return mixed
     */
    public function measure($label, \Closure $closure, $collector = null)
    {
        $name = spl_object_hash($closure);
        $this->startMeasure($name, $label, $collector);
        $result = $closure();
        $params = is_array($result) ? $result : array();
        $this->stopMeasure($name, $params);
        return $result;
    }

    /**
     * Returns an array of all measures
     *
     * @return array
     */
    public function getMeasures()
    {
        return $this->measures;
    }

    /**
     * Returns the request start time
     *
     * @return float
     */
    public function getRequestStartTime()
    {
        return $this->requestStartTime;
    }

    /**
     * Returns the request end time
     *
     * @return float
     */
    public function getRequestEndTime()
    {
        return $this->requestEndTime;
    }

    /**
     * Returns the duration of a request
     *
     * @return float
     */
    public function getRequestDuration()
    {
        if ($this->requestEndTime !== null) {
            return $this->requestEndTime - $this->requestStartTime;
        }
        return microtime(true) - $this->requestStartTime;
    }

    /**
     * @return array
     * @throws DebugBarException
     */
    public function collect()
    {
        $this->requestEndTime = microtime(true);
        foreach (array_keys($this->startedMeasures) as $name) {
            $this->stopMeasure($name);
        }

        usort($this->measures, function($a, $b) {
            if ($a['start'] == $b['start']) {
                return 0;
            }
            return $a['start'] < $b['start'] ? -1 : 1;
        });

        return array(
            'start' => $this->requestStartTime,
            'end' => $this->requestEndTime,
            'duration' => $this->getRequestDuration(),
            'duration_str' => $this->getDataFormatter()->formatDuration($this->getRequestDuration()),
            'measures' => array_values($this->measures)
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'time';
    }

    /**
     * @return array
     */
    public function getWidgets()
    {
        return array(
            "time" => array(
                "icon" => "clock-o",
                "tooltip" => "Request Duration",
                "map" => "time.duration_str",
                "default" => "'0ms'"
            ),
            "timeline" => array(
                "icon" => "tasks",
                "widget" => "PhpDebugBar.Widgets.TimelineWidget",
                "map" => "time",
                "default" => "{}"
            )
        );
    }
}
