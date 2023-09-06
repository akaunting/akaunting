<?php

namespace Akaunting\Apexcharts\Options;

use Akaunting\Apexcharts\Chart;

trait Annotations
{
    public string $annotationsPosition = '';

    public array $annotationsYaxis = [];

    public array $annotationsXaxis = [];

    public array $annotationsPoints = [];

    public array $annotationsTexts = [];

    public array $annotationsImages = [];

    public function setAnnotationsPosition(string $annotationsPosition): Chart
    {
        $this->annotationsPosition = $annotationsPosition;

        $this->setOption([
            'annotations' => [
                'position' => $annotationsPosition,
            ],
        ]);

        return $this;
    }

    public function getAnnotationsPosition(): string
    {
        return $this->annotationsPosition;
    }

    public function setAnnotationsYaxis(array $annotationsYaxis): Chart
    {
        $this->annotationsYaxis = $annotationsYaxis;

        $this->setOption([
            'annotations' => [
                'yaxis' => $annotationsYaxis,
            ],
        ]);

        return $this;
    }

    public function getAnnotationsYaxis(): array
    {
        return $this->annotationsYaxis;
    }

    public function setAnnotationsXaxis(array $annotationsXaxis): Chart
    {
        $this->annotationsXaxis = $annotationsXaxis;

        $this->setOption([
            'annotations' => [
                'xaxis' => $annotationsXaxis,
            ],
        ]);

        return $this;
    }

    public function getAnnotationsXaxis(): array
    {
        return $this->annotationsXaxis;
    }

    public function setAnnotationsPoints(array $annotationsPoints): Chart
    {
        $this->annotationsPoints = $annotationsPoints;

        $this->setOption([
            'annotations' => [
                'points' => $annotationsPoints,
            ],
        ]);

        return $this;
    }

    public function getAnnotationsPoints(): array
    {
        return $this->annotationsPoints;
    }

    public function setAnnotationsTexts(array $annotationsTexts): Chart
    {
        $this->annotationsTexts = $annotationsTexts;

        $this->setOption([
            'annotations' => [
                'texts' => $annotationsTexts,
            ],
        ]);

        return $this;
    }

    public function getAnnotationsTexts(): array
    {
        return $this->annotationsTexts;
    }

    public function setAnnotationsImages(array $annotationsImages): Chart
    {
        $this->annotationsImages = $annotationsImages;

        $this->setOption([
            'annotations' => [
                'images' => $annotationsImages,
            ],
        ]);

        return $this;
    }

    public function getAnnotationsImages(): array
    {
        return $this->annotationsImages;
    }
}
