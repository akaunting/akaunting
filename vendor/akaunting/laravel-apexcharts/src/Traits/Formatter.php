<?php

namespace Akaunting\Apexcharts\Traits;

trait Formatter
{
    public function toJson()
    {
        return response()->json([
            'id'        => $this->id(),
            'options'   => $this->getOptions(),
        ]);
    }

    public function toVue(): array
    {
        return [
            'height'    => $this->getHeight(),
            'width'     => $this->getWidth(),
            'type'      => $this->getType(),
            'options'   => $this->getOptions(),
            'series'    => json_decode($this->getSeries()),
        ];
    }
}
