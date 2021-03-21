<?php
/*
 * This file is part of the DebugBar package.
 *
 * (c) 2013 Maxime Bouroumeau-Fuseau
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DebugBar\Bridge;

use DebugBar\DataCollector\AssetProvider;
use DebugBar\DataCollector\DataCollector;
use DebugBar\DataCollector\Renderable;
use Monolog\Handler\TestHandler;
use Monolog\Logger;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Connection\ProfilerConnectionWrapper;
use Propel\Runtime\Propel;
use Psr\Log\LogLevel;
use Psr\Log\LoggerInterface;

/**
 * A Propel logger which acts as a data collector
 *
 * http://propelorm.org/
 *
 * Will log queries and display them using the SQLQueries widget.
 *
 * Example:
 * <code>
 * $debugbar->addCollector(new \DebugBar\Bridge\Propel2Collector(\Propel\Runtime\Propel::getServiceContainer()->getReadConnection()));
 * </code>
 */
class Propel2Collector extends DataCollector implements Renderable, AssetProvider
{
    /**
     * @var null|TestHandler
     */
    protected $handler = null;

    /**
     * @var null|Logger
     */
    protected $logger = null;

    /**
     * @var array
     */
    protected $config = array();

    /**
     * @var array
     */
    protected $errors = array();

    /**
     * @var int
     */
    protected $queryCount = 0;

    /**
     * @param ConnectionInterface $connection Propel connection
     */
    public function __construct(
        ConnectionInterface $connection,
        array $logMethods = array(
            'beginTransaction',
            'commit',
            'rollBack',
            'forceRollBack',
            'exec',
            'query',
            'execute'
        )
    ) {
        if ($connection instanceof ProfilerConnectionWrapper) {
            $connection->setLogMethods($logMethods);

            $this->config = $connection->getProfiler()->getConfiguration();

            $this->handler = new TestHandler();

            if ($connection->getLogger() instanceof Logger) {
                $this->logger = $connection->getLogger();
                $this->logger->pushHandler($this->handler);
            } else {
                $this->errors[] = 'Supported only monolog logger';
            }
        } else {
            $this->errors[] = 'You need set ProfilerConnectionWrapper';
        }
    }

    /**
     * @return TestHandler|null
     */
    public function getHandler()
    {
        return $this->handler;
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @return Logger|null
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * @return LoggerInterface
     */
    protected function getDefaultLogger()
    {
        return Propel::getServiceContainer()->getLogger();
    }

    /**
     * @return int
     */
    protected function getQueryCount()
    {
        return $this->queryCount;
    }

    /**
     * @param array $records
     * @param array $config
     * @return array
     */
    protected function getStatements($records, $config)
    {
        $statements = array();
        foreach ($records as $record) {
            $duration = null;
            $memory = null;

            $isSuccess = ( LogLevel::INFO === strtolower($record['level_name']) );

            $detailsCount = count($config['details']);
            $parameters = explode($config['outerGlue'], $record['message'], $detailsCount + 1);
            if (count($parameters) === ($detailsCount + 1)) {
                $parameters = array_map('trim', $parameters);
                $_details = array();
                foreach (array_splice($parameters, 0, $detailsCount) as $string) {
                    list($key, $value) = array_map('trim', explode($config['innerGlue'], $string, 2));
                    $_details[$key] = $value;
                }

                $details = array();
                foreach ($config['details'] as $key => $detail) {
                    if (isset($_details[$detail['name']])) {
                        $value = $_details[$detail['name']];
                        if ('time' === $key) {
                            if (substr_count($value, 'ms')) {
                                $value = (float)$value / 1000;
                            } else {
                                $value = (float)$value;
                            }
                        } else {
                            $suffixes = array('B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
                            $suffix = substr($value, -2);
                            $i = array_search($suffix, $suffixes, true);
                            $i = (false === $i) ? 0 : $i;

                            $value = ((float)$value) * pow(1024, $i);
                        }
                        $details[$key] = $value;
                    }
                }

                if (isset($details['time'])) {
                    $duration = $details['time'];
                }
                if (isset($details['memDelta'])) {
                    $memory = $details['memDelta'];
                }

                $message = end($parameters);

                if ($isSuccess) {
                    $this->queryCount++;
                }

            } else {
                $message = $record['message'];
            }

            $statement = array(
                'sql' => $message,
                'is_success' => $isSuccess,
                'duration' => $duration,
                'duration_str' => $this->getDataFormatter()->formatDuration($duration),
                'memory' => $memory,
                'memory_str' => $this->getDataFormatter()->formatBytes($memory),
            );

            if (false === $isSuccess) {
                $statement['sql'] = '';
                $statement['error_code'] = $record['level'];
                $statement['error_message'] = $message;
            }

            $statements[] = $statement;
        }
        return $statements;
    }

    /**
     * @return array
     */
    public function collect()
    {
        if (count($this->errors)) {
            return array(
                'statements' => array_map(function ($message) {
                    return array('sql' => '', 'is_success' => false, 'error_code' => 500, 'error_message' => $message);
                }, $this->errors),
                'nb_statements' => 0,
                'nb_failed_statements' => count($this->errors),
            );
        }

        if ($this->getHandler() === null) {
            return array();
        }

        $statements = $this->getStatements($this->getHandler()->getRecords(), $this->getConfig());

        $failedStatement = count(array_filter($statements, function ($statement) {
            return false === $statement['is_success'];
        }));
        $accumulatedDuration = array_reduce($statements, function ($accumulatedDuration, $statement) {
        
            $time = isset($statement['duration']) ? $statement['duration'] : 0;
            return $accumulatedDuration += $time;
        });
        $memoryUsage = array_reduce($statements, function ($memoryUsage, $statement) {
        
            $time = isset($statement['memory']) ? $statement['memory'] : 0;
            return $memoryUsage += $time;
        });

        return array(
            'nb_statements' => $this->getQueryCount(),
            'nb_failed_statements' => $failedStatement,
            'accumulated_duration' => $accumulatedDuration,
            'accumulated_duration_str' => $this->getDataFormatter()->formatDuration($accumulatedDuration),
            'memory_usage' => $memoryUsage,
            'memory_usage_str' => $this->getDataFormatter()->formatBytes($memoryUsage),
            'statements' => $statements
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        $additionalName  = '';
        if ($this->getLogger() !== $this->getDefaultLogger()) {
            $additionalName = ' ('.$this->getLogger()->getName().')';
        }

        return 'propel2'.$additionalName;
    }

    /**
     * @return array
     */
    public function getWidgets()
    {
        return array(
            $this->getName() => array(
                'icon' => 'bolt',
                'widget' => 'PhpDebugBar.Widgets.SQLQueriesWidget',
                'map' => $this->getName(),
                'default' => '[]'
            ),
            $this->getName().':badge' => array(
                'map' => $this->getName().'.nb_statements',
                'default' => 0
            ),
        );
    }

    /**
     * @return array
     */
    public function getAssets()
    {
        return array(
            'css' => 'widgets/sqlqueries/widget.css',
            'js' => 'widgets/sqlqueries/widget.js'
        );
    }
}
