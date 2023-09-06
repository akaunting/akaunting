<?php

namespace Svg;

class CssLength
{
    /**
     * Array of valid css length units.
     * Should be pre-sorted by unit text length so no earlier length can be
     * contained within a latter (eg. 'in' within 'vmin').
     *
     * @var string[]
     */
    protected static $units = [
        'vmax',
        'vmin',
        'rem',
        'px',
        'pt',
        'cm',
        'mm',
        'in',
        'pc',
        'em',
        'ex',
        'ch',
        'vw',
        'vh',
        '%',
        'q',
    ];

    /**
     * A list of units that are inch-relative, and their unit division within an inch.
     *
     * @var array<string, float>
     */
    protected static $inchDivisions = [
        'in' => 1,
        'cm' => 2.54,
        'mm' => 25.4,
        'q' => 101.6,
        'pc' => 6,
        'pt' => 72,
    ];

    /**
     * The CSS length unit indicator.
     * Will be lower-case and one of the units listed in the '$units' array or empty.
     *
     * @var string
     */
    protected $unit = '';

    /**
     * The numeric value of the given length.
     *
     * @var float
     */
    protected $value = 0;

    /**
     * The original unparsed length provided.
     *
     * @var string
     */
    protected $unparsed;

    public function __construct(string $length)
    {
        $this->unparsed = $length;
        $this->parseLengthComponents($length);
    }

    /**
     * Parse out the unit and value components from the given string length.
     */
    protected function parseLengthComponents(string $length): void
    {
        $length = strtolower($length);

        foreach (self::$units as $unit) {
            $pos = strpos($length, $unit);
            if ($pos) {
                $this->value = floatval(substr($length, 0, $pos));
                $this->unit = $unit;
                return;
            }
        }

        $this->unit = '';
        $this->value = floatval($length);
    }

    /**
     * Get the unit type of this css length.
     * Units are standardised to be lower-cased.
     *
     * @return string
     */
    public function getUnit(): string
    {
        return $this->unit;
    }

    /**
     * Get this CSS length in the equivalent pixel count size.
     *
     * @param float $referenceSize
     * @param float $dpi
     *
     * @return float
     */
    public function toPixels(float $referenceSize = 11.0, float $dpi = 96.0): float
    {
        // Standard relative units
        if (in_array($this->unit, ['em', 'rem', 'ex', 'ch'])) {
            return $this->value * $referenceSize;
        }

        // Percentage relative units
        if (in_array($this->unit, ['%', 'vw', 'vh', 'vmin', 'vmax'])) {
            return $this->value * ($referenceSize / 100);
        }

        // Inch relative units
        if (in_array($this->unit, array_keys(static::$inchDivisions))) {
            $inchValue = $this->value * $dpi;
            $division = static::$inchDivisions[$this->unit];
            return $inchValue / $division;
        }

        return $this->value;
    }
}