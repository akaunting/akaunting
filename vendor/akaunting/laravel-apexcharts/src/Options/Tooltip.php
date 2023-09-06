<?php

namespace Akaunting\Apexcharts\Options;

use Akaunting\Apexcharts\Chart;

trait Tooltip
{
    public bool $tooltipEnabled = true;

    public array $tooltipEnabledOnSeries = [];

    public bool $tooltipShared = true;

    public bool $tooltipFollowCursor = false;

    public bool $tooltipIntersect = false;

    public bool $tooltipInverseOrder = false;

    public mixed $tooltipCustom;

    public bool $tooltipFillSeriesColor = false;

    public bool $tooltipTheme = false;

    public array $tooltipStyle = [];

    public array $tooltipOnDatasetHover = [];

    public array $tooltipX = [];

    public array $tooltipY = [];

    public array $tooltipZ = [];

    public array $tooltipMarker = [];

    public array $tooltipItems = [];

    public array $tooltipFixed = [];

    public function setTooltipEnabled(bool $tooltipEnabled): Chart
    {
        $this->tooltipEnabled = $tooltipEnabled;

        $this->setOption([
            'tooltip' => [
                'enabled' => $tooltipEnabled,
            ],
        ]);

        return $this;
    }

    public function getTooltipEnabled(): bool
    {
        return $this->tooltipEnabled;
    }

    public function setTooltipEnabledOnSeries(array $tooltipEnabledOnSeries): Chart
    {
        $this->tooltipEnabledOnSeries = $tooltipEnabledOnSeries;

        $this->setOption([
            'tooltip' => [
                'enabledOnSeries' => $tooltipEnabledOnSeries,
            ],
        ]);

        return $this;
    }

    public function getTooltipEnabledOnSeries(): array
    {
        return $this->tooltipEnabledOnSeries;
    }

    public function setTooltipShared(bool $tooltipShared): Chart
    {
        $this->tooltipShared = $tooltipShared;

        $this->setOption([
            'tooltip' => [
                'shared' => $tooltipShared,
            ],
        ]);

        return $this;
    }

    public function getTooltipShared(): bool
    {
        return $this->tooltipShared;
    }

    public function setTooltipFollowCursor(bool $tooltipFollowCursor): Chart
    {
        $this->tooltipFollowCursor = $tooltipFollowCursor;

        $this->setOption([
            'tooltip' => [
                'followCursor' => $tooltipFollowCursor,
            ],
        ]);

        return $this;
    }

    public function getTooltipFollowCursor(): bool
    {
        return $this->tooltipFollowCursor;
    }

    public function setTooltipIntersect(bool $tooltipIntersect): Chart
    {
        $this->tooltipIntersect = $tooltipIntersect;

        $this->setOption([
            'tooltip' => [
                'intersect' => $tooltipIntersect,
            ],
        ]);

        return $this;
    }

    public function getTooltipIntersect(): bool
    {
        return $this->tooltipIntersect;
    }

    public function setTooltipInverseOrder(bool $tooltipInverseOrder): Chart
    {
        $this->tooltipInverseOrder = $tooltipInverseOrder;

        $this->setOption([
            'tooltip' => [
                'inverseOrder' => $tooltipInverseOrder,
            ],
        ]);

        return $this;
    }

    public function getTooltipInverseOrder(): bool
    {
        return $this->tooltipInverseOrder;
    }

    public function setTooltipCustom(mixed $tooltipCustom): Chart
    {
        $this->tooltipCustom = $tooltipCustom;

        $this->setOption([
            'tooltip' => [
                'custom' => $tooltipCustom,
            ],
        ]);

        return $this;
    }

    public function getTooltipCustom(): mixed
    {
        return $this->tooltipCustom;
    }

    public function setTooltipFillSeriesColor(bool $tooltipFillSeriesColor): Chart
    {
        $this->tooltipFillSeriesColor = $tooltipFillSeriesColor;

        $this->setOption([
            'tooltip' => [
                'fillSeriesColor' => $tooltipFillSeriesColor,
            ],
        ]);

        return $this;
    }

    public function getTooltipFillSeriesColor(): bool
    {
        return $this->tooltipFillSeriesColor;
    }

    public function setTooltipTheme(bool $tooltipTheme): Chart
    {
        $this->tooltipTheme = $tooltipTheme;

        $this->setOption([
            'tooltip' => [
                'theme' => $tooltipTheme,
            ],
        ]);

        return $this;
    }

    public function getTooltipTheme(): bool
    {
        return $this->tooltipTheme;
    }

    public function setTooltipStyle(array $tooltipStyle): Chart
    {
        $this->tooltipStyle = $tooltipStyle;

        $this->setOption([
            'tooltip' => [
                'style' => $tooltipStyle,
            ],
        ]);

        return $this;
    }

    public function getTooltipStyle(): array
    {
        return $this->tooltipStyle;
    }

    public function setTooltipOnDatasetHover(array $tooltipOnDatasetHover): Chart
    {
        $this->tooltipOnDatasetHover = $tooltipOnDatasetHover;

        $this->setOption([
            'tooltip' => [
                'onDatasetHover' => $tooltipOnDatasetHover,
            ],
        ]);

        return $this;
    }

    public function getTooltipOnDatasetHover(): array
    {
        return $this->tooltipOnDatasetHover;
    }

    public function setTooltipX(array $tooltipX): Chart
    {
        $this->tooltipX = $tooltipX;

        $this->setOption([
            'tooltip' => [
                'x' => $tooltipX,
            ],
        ]);

        return $this;
    }

    public function getTooltipX(): array
    {
        return $this->tooltipX;
    }

    public function setTooltipY(array $tooltipY): Chart
    {
        $this->tooltipY = $tooltipY;

        $this->setOption([
            'tooltip' => [
                'y' => $tooltipY,
            ],
        ]);

        return $this;
    }

    public function getTooltipY(): array
    {
        return $this->tooltipY;
    }

    public function setTooltipZ(array $tooltipZ): Chart
    {
        $this->tooltipZ = $tooltipZ;

        $this->setOption([
            'tooltip' => [
                'z' => $tooltipZ,
            ],
        ]);

        return $this;
    }

    public function getTooltipZ(): array
    {
        return $this->tooltipZ;
    }

    public function setTooltipMarker(array $tooltipMarker): Chart
    {
        $this->tooltipMarker = $tooltipMarker;

        $this->setOption([
            'tooltip' => [
                'marker' => $tooltipMarker,
            ],
        ]);

        return $this;
    }

    public function getTooltipMarker(): array
    {
        return $this->tooltipMarker;
    }

    /**
     * Set the items tooltip.
     *
     * @param string $tooltipItems
     *
     * @return this
     */
    public function setTooltipItems(array $tooltipItems): Chart
    {
        $this->tooltipItems = $tooltipItems;

        $this->setOption([
            'tooltip' => [
                'items' => $tooltipItems,
            ],
        ]);

        return $this;
    }

    public function getTooltipItems(): array
    {
        return $this->tooltipItems;
    }

    public function setTooltipFixed(array $tooltipFixed): Chart
    {
        $this->tooltipFixed = $tooltipFixed;

        $this->setOption([
            'tooltip' => [
                'fixed' => $tooltipFixed,
            ],
        ]);

        return $this;
    }

    public function getTooltipFixed(): array
    {
        return $this->tooltipFixed;
    }
}
