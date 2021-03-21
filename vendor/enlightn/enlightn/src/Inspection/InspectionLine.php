<?php

namespace Enlightn\Enlightn\Inspection;

class InspectionLine
{
    /**
     * @var int
     */
    public $lineNumber;

    /**
     * @var string|null
     */
    public $details;

    public function __construct($lineNumber, $details = null)
    {
        $this->lineNumber = $lineNumber;
        $this->details = $details;
    }
}
