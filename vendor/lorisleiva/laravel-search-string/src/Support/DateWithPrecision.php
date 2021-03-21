<?php

namespace Lorisleiva\LaravelSearchString\Support;

use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class DateWithPrecision
{
    public $original;
    public $precision = null;
    public $carbon = null;

    const FORMATS_PER_PRECISIONS = [
        'micro' =>  ['Y-m-d H:i:s.u'],
        'second' => ['Y-m-d H:i:s'],
        'minute' => ['Y-m-d H:i'],
        'hour' =>   ['Y-m-d H'],
        'day' =>    ['Y-m-d'],
        'month' =>  [
            'Y-m', 'Y/m', 'Y m', 'm-Y', 'm/Y', 'm Y', 'm-y', 'm/y', 'm y',
            'Y-n', 'Y/n', 'Y n', 'n-Y', 'n/Y', 'n Y', 'n-y', 'n/y', 'n y',
            'Y M', 'M Y', 'y M', 'M y',
            'Y F', 'F Y', 'y F', 'F y',
        ],
        'year' =>   ['Y', 'y'],
    ];

    public function __construct($original)
    {
        $this->original = $original;
        $this->parseOriginal();
    }

    public function parseOriginal()
    {
        if ($this->parseFromFormatMapping()) {
            return;
        }

        try {
            $this->carbon = Carbon::parse($this->original);
        } catch (\Exception $e) {
            return;
        }
        
        foreach (static::FORMATS_PER_PRECISIONS as $precision => $formats) {
            if ($this->carbon->$precision !== 0) {
                return $this->precision = $precision;
            }
        }

        // Fallback precision.
        $this->precision = 'micro';
    }

    public function parseFromFormatMapping()
    {
        foreach (static::FORMATS_PER_PRECISIONS as $precision => $formats) {
            foreach ($formats as $format) {
                if (Carbon::hasFormat($this->original, $format)) {
                    $this->carbon = Carbon::createFromFormat($format, $this->original);
                    $this->precision = $precision;

                    if (! in_array($this->precision, ['micro', 'second'])) {
                        $precision = Str::title(Str::camel($this->precision));
                        $this->carbon->{"startOf$precision"}();
                    }

                    return true;
                }
            }
        }
        return false;
    }

    public function getRange()
    {
        if (in_array($this->precision, ['micro', 'second'])) {
            return $this->carbon;
        }

        $precision = Str::title(Str::camel($this->precision));

        return [
            $this->carbon->copy()->{"startOf$precision"}(),
            $this->carbon->copy()->{"endOf$precision"}(),
        ];
    }
}