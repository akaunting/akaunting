<?php

namespace App\View\Components;

use App\Abstracts\View\Component;
use App\Traits\DateTime;
use App\Utilities\Date as UDate;
use Illuminate\Support\Str;

class Date extends Component
{
    public $rawDate;

    public $date;

    public $format;

    public $function;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $date, string $format = '', string $function = ''
    ) {
        $this->rawDate = $date;
        $this->format = $this->getFormat($format);
        $this->function = $function;

        $this->date = $this->getFormatDate($date);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.date');
    }

    protected function getFormat($format)
    {
        if (! empty($format)) {
            return $format;
        }

        $date_time = new class() {
            use DateTime;
        };

        return $date_time->getCompanyDateFormat();
    }

    protected function getFormatDate($date)
    {
        if (! empty($this->function)) {
            $date = UDate::parse($date)->{$this->function}();

            return Str::ucfirst($date);
        }

        return UDate::parse($date)->format($this->format);
    }
}
