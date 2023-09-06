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

use DebugBar\DataFormatter\DataFormatter;
use DebugBar\DataFormatter\DataFormatterInterface;
use DebugBar\DataFormatter\DebugBarVarDumper;

/**
 * Abstract class for data collectors
 */
abstract class DataCollector implements DataCollectorInterface
{
    private static $defaultDataFormatter;
    private static $defaultVarDumper;

    protected $dataFormater;
    protected $varDumper;
    protected $xdebugLinkTemplate = '';
    protected $xdebugShouldUseAjax = false;
    protected $xdebugReplacements = array();

    /**
     * Sets the default data formater instance used by all collectors subclassing this class
     *
     * @param DataFormatterInterface $formater
     */
    public static function setDefaultDataFormatter(DataFormatterInterface $formater)
    {
        self::$defaultDataFormatter = $formater;
    }

    /**
     * Returns the default data formater
     *
     * @return DataFormatterInterface
     */
    public static function getDefaultDataFormatter()
    {
        if (self::$defaultDataFormatter === null) {
            self::$defaultDataFormatter = new DataFormatter();
        }
        return self::$defaultDataFormatter;
    }

    /**
     * Sets the data formater instance used by this collector
     *
     * @param DataFormatterInterface $formater
     * @return $this
     */
    public function setDataFormatter(DataFormatterInterface $formater)
    {
        $this->dataFormater = $formater;
        return $this;
    }

    /**
     * @return DataFormatterInterface
     */
    public function getDataFormatter()
    {
        if ($this->dataFormater === null) {
            $this->dataFormater = self::getDefaultDataFormatter();
        }
        return $this->dataFormater;
    }

    /**
     * Get an Xdebug Link to a file
     *
     * @param string $file
     * @param int    $line
     *
     * @return array {
     * @var string   $url
     * @var bool     $ajax should be used to open the url instead of a normal links
     * }
     */
    public function getXdebugLink($file, $line = 1)
    {
        if (count($this->xdebugReplacements)) {
            $file = strtr($file, $this->xdebugReplacements);
        }

        $url = strtr($this->getXdebugLinkTemplate(), ['%f' => $file, '%l' => $line]);
        if ($url) {
            return ['url' => $url, 'ajax' => $this->getXdebugShouldUseAjax()];
        }
    }
  
    /**  
     * Sets the default variable dumper used by all collectors subclassing this class
     *
     * @param DebugBarVarDumper $varDumper
     */
    public static function setDefaultVarDumper(DebugBarVarDumper $varDumper)
    {
        self::$defaultVarDumper = $varDumper;
    }

    /**
     * Returns the default variable dumper
     *
     * @return DebugBarVarDumper
     */
    public static function getDefaultVarDumper()
    {
        if (self::$defaultVarDumper === null) {
            self::$defaultVarDumper = new DebugBarVarDumper();
        }
        return self::$defaultVarDumper;
    }

    /**
     * Sets the variable dumper instance used by this collector
     *
     * @param DebugBarVarDumper $varDumper
     * @return $this
     */
    public function setVarDumper(DebugBarVarDumper $varDumper)
    {
        $this->varDumper = $varDumper;
        return $this;
    }

    /**
     * Gets the variable dumper instance used by this collector; note that collectors using this
     * instance need to be sure to return the static assets provided by the variable dumper.
     *
     * @return DebugBarVarDumper
     */
    public function getVarDumper()
    {
        if ($this->varDumper === null) {
            $this->varDumper = self::getDefaultVarDumper();
        }
        return $this->varDumper;
    }

    /**
     * @deprecated
     */
    public function formatVar($var)
    {
        return $this->getDataFormatter()->formatVar($var);
    }

    /**
     * @deprecated
     */
    public function formatDuration($seconds)
    {
        return $this->getDataFormatter()->formatDuration($seconds);
    }

    /**
     * @deprecated
     */
    public function formatBytes($size, $precision = 2)
    {
        return $this->getDataFormatter()->formatBytes($size, $precision);
    }

    /**
     * @return string
     */
    public function getXdebugLinkTemplate()
    {
        if (empty($this->xdebugLinkTemplate) && !empty(ini_get('xdebug.file_link_format'))) {
            $this->xdebugLinkTemplate = ini_get('xdebug.file_link_format');
        }

        return $this->xdebugLinkTemplate;
    }

    /**
     * @param string $xdebugLinkTemplate
     * @param bool $shouldUseAjax
     */
    public function setXdebugLinkTemplate($xdebugLinkTemplate, $shouldUseAjax = false)
    {
        if ($xdebugLinkTemplate === 'idea') {
            $this->xdebugLinkTemplate  = 'http://localhost:63342/api/file/?file=%f&line=%l';
            $this->xdebugShouldUseAjax = true;
        } else {
            $this->xdebugLinkTemplate  = $xdebugLinkTemplate;
            $this->xdebugShouldUseAjax = $shouldUseAjax;
        }
    }

    /**
     * @return bool
     */
    public function getXdebugShouldUseAjax()
    {
        return $this->xdebugShouldUseAjax;
    }

    /**
     * returns an array of filename-replacements
     *
     * this is useful f.e. when using vagrant or remote servers,
     * where the path of the file is different between server and
     * development environment
     *
     * @return array key-value-pairs of replacements, key = path on server, value = replacement
     */
    public function getXdebugReplacements()
    {
        return $this->xdebugReplacements;
    }

    /**
     * @param array $xdebugReplacements
     */
    public function setXdebugReplacements($xdebugReplacements)
    {
        $this->xdebugReplacements = $xdebugReplacements;
    }

    public function setXdebugReplacement($serverPath, $replacement)
    {
        $this->xdebugReplacements[$serverPath] = $replacement;
    }
}
