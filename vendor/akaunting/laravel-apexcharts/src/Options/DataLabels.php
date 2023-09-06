<?php

namespace Akaunting\Apexcharts\Options;

use Akaunting\Apexcharts\Chart;

trait DataLabels
{
    public bool $dataLabelsEnabled = true;

    public array $dataLabelsEnabledOnSeries;

    public mixed $dataLabelsFormatter;

    public string $dataLabelsTextAnchor = 'middle';

    public bool $dataLabelsDistributed = false;

    public int $dataLabelsOffsetX = 0;

    public int $dataLabelsOffsetY = 0;

    public array $dataLabelsStyle = [];

    public array $dataLabelsBackground = [];

    public array $dataLabelsDropShadow = [];

    public function setDataLabelsEnabled(bool $dataLabelsEnabled): Chart
    {
        $this->dataLabelsEnabled = $dataLabelsEnabled;

        $this->setOption([
            'dataLabels' => [
                'enabled' => $dataLabelsEnabled,
            ],
        ]);

        return $this;
    }

    public function getDataLabelsEnabled(): bool
    {
        return $this->dataLabelsEnabled;
    }

    public function setDataLabelsEnabledOnSeries(array $dataLabelsEnabledOnSeries): Chart
    {
        $this->dataLabelsEnabledOnSeries = $dataLabelsEnabledOnSeries;

        $this->setOption([
            'dataLabels' => [
                'enabledOnSeries' => $dataLabelsEnabledOnSeries,
            ],
        ]);

        return $this;
    }

    public function getDataLabelsEnabledOnSeries(): array
    {
        return $this->dataLabelsEnabledOnSeries;
    }

    public function setDataLabelsFormatter(mixed $dataLabelsFormatter): Chart
    {
        $this->dataLabelsFormatter = $dataLabelsFormatter;

        $this->setOption([
            'dataLabels' => [
                'formatter' => $dataLabelsFormatter,
            ],
        ]);

        return $this;
    }

    public function getDataLabelsFormatter(): mixed
    {
        return $this->dataLabelsFormatter;
    }

    public function setDataLabelsTextAnchor(string $dataLabelsTextAnchor): Chart
    {
        $this->dataLabelsTextAnchor = $dataLabelsTextAnchor;

        $this->setOption([
            'dataLabels' => [
                'textAnchor' => $dataLabelsTextAnchor,
            ],
        ]);

        return $this;
    }

    public function getDataLabelsTextAnchor(): string
    {
        return $this->dataLabelsTextAnchor;
    }

    public function setDataLabelsDistributed(bool $dataLabelsDistributed): Chart
    {
        $this->dataLabelsDistributed = $dataLabelsDistributed;

        $this->setOption([
            'dataLabels' => [
                'distributed' => $dataLabelsDistributed,
            ],
        ]);

        return $this;
    }

    public function getDataLabelsDistributed(): bool
    {
        return $this->dataLabelsDistributed;
    }

    public function setDataLabelsOffsetX(int $dataLabelsOffsetX): Chart
    {
        $this->dataLabelsOffsetX = $dataLabelsOffsetX;

        $this->setOption([
            'dataLabels' => [
                'offsetX' => $dataLabelsOffsetX,
            ],
        ]);

        return $this;
    }

    public function getDataLabelsOffsetX(): int
    {
        return $this->dataLabelsOffsetX;
    }

    public function setDataLabelsOffsetY(int $dataLabelsOffsetY): Chart
    {
        $this->dataLabelsOffsetY = $dataLabelsOffsetY;

        $this->setOption([
            'dataLabels' => [
                'offsetY' => $dataLabelsOffsetY,
            ],
        ]);

        return $this;
    }

    public function getDataLabelsOffsetY(): int
    {
        return $this->dataLabelsOffsetY;
    }

    public function setDataLabelsStyle(array $dataLabelsStyle): Chart
    {
        $this->dataLabelsStyle = $dataLabelsStyle;

        $this->setOption([
            'dataLabels' => [
                'style' => $dataLabelsStyle,
            ],
        ]);

        return $this;
    }

    public function getDataLabelsStyle(): array
    {
        return $this->dataLabelsStyle;
    }

    public function setDataLabelsBackground(array $dataLabelsBackground): Chart
    {
        $this->dataLabelsBackground = $dataLabelsBackground;

        $this->setOption([
            'dataLabels' => [
                'enabled' => $dataLabelsBackground,
            ],
        ]);

        return $this;
    }

    public function getDataLabelsBackground(): array
    {
        return $this->dataLabelsBackground;
    }

    public function setDataLabelsDropShadow(array $dataLabelsDropShadow): Chart
    {
        $this->dataLabelsDropShadow = $dataLabelsDropShadow;

        $this->setOption([
            'dataLabels' => [
                'dropShadow' => $dataLabelsDropShadow,
            ],
        ]);

        return $this;
    }

    public function getDataLabelsDropShadow(): array
    {
        return $this->dataLabelsDropShadow;
    }
}
