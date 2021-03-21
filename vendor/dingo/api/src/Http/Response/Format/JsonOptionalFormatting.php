<?php

namespace Dingo\Api\Http\Response\Format;

trait JsonOptionalFormatting
{
    /*
     * Supported JSON pretty print indent styles.
     *
     * @var array
     */
    protected $indentStyles = [
        'tab',
        'space',
    ];

    /*
     * Indent styles, that are allowed to have various indent size.
     *
     * @var array
     */
    protected $hasVariousIndentSize = [
        'space',
    ];

    /*
     * Indent chars, associated with indent styles.
     *
     * @var array
     */
    protected $indentChars = [
        'tab' => "\t",
        'space' => ' ',
    ];

    /*
     * JSON constants, that are allowed to be used as options while encoding.
     * Whitelist can be extended by other options in the future.
     *
     * @see http://php.net/manual/ru/json.constants.php
     *
     * @var array
     */
    protected $jsonEncodeOptionsWhitelist = [
        JSON_PRETTY_PRINT,
        JSON_UNESCAPED_UNICODE,
    ];

    /**
     * Determine if JSON pretty print option is set to true.
     *
     * @return bool
     */
    protected function isJsonPrettyPrintEnabled()
    {
        return isset($this->options['pretty_print']) && $this->options['pretty_print'] === true;
    }

    /**
     * Determine if JSON custom indent style is set.
     *
     * @return bool
     */
    protected function isCustomIndentStyleRequired()
    {
        return $this->isJsonPrettyPrintEnabled() &&
            isset($this->options['indent_style']) &&
            in_array($this->options['indent_style'], $this->indentStyles);
    }

    /**
     * Perform JSON encode.
     *
     * @param string $content
     * @param array  $jsonEncodeOptions
     *
     * @return string
     */
    protected function performJsonEncoding($content, array $jsonEncodeOptions = [])
    {
        $jsonEncodeOptions = $this->filterJsonEncodeOptions($jsonEncodeOptions);

        $optionsBitmask = $this->calucateJsonEncodeOptionsBitmask($jsonEncodeOptions);

        if (($encodedString = json_encode($content, $optionsBitmask)) === false) {
            throw new \ErrorException('Error encoding data in JSON format: '.json_last_error());
        }

        return $encodedString;
    }

    /**
     * Filter JSON encode options array against the whitelist array.
     *
     * @param array $jsonEncodeOptions
     *
     * @return array
     */
    protected function filterJsonEncodeOptions(array $jsonEncodeOptions)
    {
        return array_intersect($jsonEncodeOptions, $this->jsonEncodeOptionsWhitelist);
    }

    /**
     * Sweep JSON encode options together to get options' bitmask.
     *
     * @param array $jsonEncodeOptions
     *
     * @return int
     */
    protected function calucateJsonEncodeOptionsBitmask(array $jsonEncodeOptions)
    {
        return array_sum($jsonEncodeOptions);
    }

    /**
     * Indent pretty printed JSON string, using given indent style.
     *
     * @param string $jsonString
     * @param string $indentStyle
     * @param int    $defaultIndentSize
     *
     * @return string
     */
    protected function indentPrettyPrintedJson($jsonString, $indentStyle, $defaultIndentSize = 2)
    {
        $indentChar = $this->getIndentCharForIndentStyle($indentStyle);
        $indentSize = $this->getPrettyPrintIndentSize() ?: $defaultIndentSize;

        // If the given indentation style is allowed to have various indent size
        // (number of chars, that are used to indent one level in each line),
        // indent the JSON string with given (or default) indent size.
        if ($this->hasVariousIndentSize($indentStyle)) {
            return $this->peformIndentation($jsonString, $indentChar, $indentSize);
        }

        // Otherwise following the convention, that indent styles, that does not
        // allowed to have various indent size (e.g. tab) are indented using
        // one tabulation character per one indent level in each line.
        return $this->peformIndentation($jsonString, $indentChar);
    }

    /**
     * Get indent char for given indent style.
     *
     * @param string $indentStyle
     *
     * @return string
     */
    protected function getIndentCharForIndentStyle($indentStyle)
    {
        return $this->indentChars[$indentStyle];
    }

    /**
     * Get indent size for pretty printed JSON string.
     *
     * @return int|null
     */
    protected function getPrettyPrintIndentSize()
    {
        return isset($this->options['indent_size'])
            ? (int) $this->options['indent_size']
            : null;
    }

    /**
     * Determine if indent style is allowed to have various indent size.
     *
     * @param string $indentStyle
     *
     * @return bool
     */
    protected function hasVariousIndentSize($indentStyle)
    {
        return in_array($indentStyle, $this->hasVariousIndentSize);
    }

    /**
     * Perform indentation for pretty printed JSON string with a given
     * indent char, repeated N times, as determined by indent size.
     *
     * @param string $jsonString    JSON string, which must be indented
     * @param string $indentChar    Char, used for indent (default is tab)
     * @param int    $indentSize    Number of times to repeat indent char per one indent level
     * @param int    $defaultSpaces Default number of indent spaces after json_encode()
     *
     * @return string
     */
    protected function peformIndentation($jsonString, $indentChar = "\t", $indentSize = 1, $defaultSpaces = 4)
    {
        $pattern = '/(^|\G) {'.$defaultSpaces.'}/m';
        $replacement = str_repeat($indentChar, $indentSize).'$1';

        return preg_replace($pattern, $replacement, $jsonString);
    }
}
