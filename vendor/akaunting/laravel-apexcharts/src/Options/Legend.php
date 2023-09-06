<?php

namespace Akaunting\Apexcharts\Options;

use Akaunting\Apexcharts\Chart;

trait Legend
{
    public bool $legendShow = true;

    public bool $legendShowForSingleSeries = false;

    public bool $legendShowForNullSeries = true;

    public bool $legendShowForZeroSeries = true;

    public string $legendPosition = 'bottom';

    public string $legendHorizontalAlign = 'center';

    public bool $legendFloating = false;

    public string $legendFontSize = '14px';

    public string $legendFontFamily = 'Helvetica, Arial';

    public int|string $legendFontWeight = 400;

    public mixed $legendFormatter;

    public bool $legendInverseOrder = false;

    public int $legendWidth;

    public int $legendHeight;

    public mixed $legendTooltipHoverFormatter;

    public array $legendCustomLegendItems = [];

    public int $legendOffsetX = 0;

    public int $legendOffsetY = 0;

    public array $legendLabels = [];

    public array $legendMarkers = [];

    public array $legendItemMargin = [];

    public array $legendOnItemClick = [];

    public array $legendOnItemHover = [];

    public function setLegendShow(bool $legendShow): Chart
    {
        $this->legendShow = $legendShow;

        $this->setOption([
            'legend' => [
                'show' => $legendShow,
            ],
        ]);

        return $this;
    }

    public function getLegendShow(): bool
    {
        return $this->legendShow;
    }

    public function setLegendShowForSingleSeries(bool $legendShowForSingleSeries): Chart
    {
        $this->legendShowForSingleSeries = $legendShowForSingleSeries;

        $this->setOption([
            'legend' => [
                'showForSingleSeries' => $legendShowForSingleSeries,
            ],
        ]);

        return $this;
    }

    public function getLegendShowForSingleSeries(): bool
    {
        return $this->legendShowForSingleSeries;
    }

    public function setLegendShowForNullSeries(bool $legendShowForNullSeries): Chart
    {
        $this->legendShowForNullSeries = $legendShowForNullSeries;

        $this->setOption([
            'legend' => [
                'showForNullSeries' => $legendShowForNullSeries,
            ],
        ]);

        return $this;
    }

    public function getLegendShowForNullSeries(): bool
    {
        return $this->legendShowForNullSeries;
    }

    public function setLegendShowForZeroSeries(bool $legendShowForZeroSeries): Chart
    {
        $this->legendShowForZeroSeries = $legendShowForZeroSeries;

        $this->setOption([
            'legend' => [
                'showForZeroSeries' => $legendShowForZeroSeries,
            ],
        ]);

        return $this;
    }

    public function getLegendShowForZeroSeries(): bool
    {
        return $this->legendShowForZeroSeries;
    }

    public function setLegendPosition(string $legendPosition): Chart
    {
        $this->legendPosition = $legendPosition;

        $this->setOption([
            'legend' => [
                'position' => $legendPosition,
            ],
        ]);

        return $this;
    }

    public function getLegendPosition(): string
    {
        return $this->legendPosition;
    }

    public function setLegendHorizontalAlign(string $legendHorizontalAlign): Chart
    {
        $this->legendHorizontalAlign = $legendHorizontalAlign;

        $this->setOption([
            'legend' => [
                'horizontalAlign' => $legendHorizontalAlign,
            ],
        ]);

        return $this;
    }

    public function getLegendHorizontalAlign(): string
    {
        return $this->legendHorizontalAlign;
    }

    public function setLegendFloating(bool $legendFloating): Chart
    {
        $this->legendFloating = $legendFloating;

        $this->setOption([
            'legend' => [
                'floating' => $legendFloating,
            ],
        ]);

        return $this;
    }

    public function getLegendFloating(): bool
    {
        return $this->legendFloating;
    }

    public function setLegendFontSize(string $legendFontSize): Chart
    {
        $this->legendFontSize = $legendFontSize;

        $this->setOption([
            'legend' => [
                'fontSize' => $legendFontSize,
            ],
        ]);

        return $this;
    }

    public function getLegendFontSize(): string
    {
        return $this->legendFontSize;
    }

    public function setLegendFontFamily(string $legendFontFamily): Chart
    {
        $this->legendFontFamily = $legendFontFamily;

        $this->setOption([
            'legend' => [
                'fontFamily' => $legendFontFamily,
            ],
        ]);

        return $this;
    }

    public function getLegendFontFamily(): string
    {
        return $this->legendFontFamily;
    }

    public function setLegendFontWeight(int|string $legendFontWeight): Chart
    {
        $this->legendFontWeight = $legendFontWeight;

        $this->setOption([
            'legend' => [
                'fontWeight' => $legendFontWeight,
            ],
        ]);

        return $this;
    }

    public function getLegendFontWeight(): int|string
    {
        return $this->legendFontWeight;
    }

    public function setLegendFormatter(mixed $legendFormatter): Chart
    {
        $this->legendFormatter = $legendFormatter;

        $this->setOption([
            'legend' => [
                'formatter' => $legendFormatter,
            ],
        ]);

        return $this;
    }

    public function getLegendFormatter(): mixed
    {
        return $this->legendFormatter;
    }

    public function setLegendInverseOrder(string $legendInverseOrder): Chart
    {
        $this->legendInverseOrder = $legendInverseOrder;

        $this->setOption([
            'legend' => [
                'inverseOrder' => $legendInverseOrder,
            ],
        ]);

        return $this;
    }

    public function getLegendInverseOrder(): string
    {
        return $this->legendInverseOrder;
    }

    public function setLegendWidth(int $legendWidth): Chart
    {
        $this->legendWidth = $legendWidth;

        $this->setOption([
            'legend' => [
                'width' => $legendWidth,
            ],
        ]);

        return $this;
    }

    public function getLegendWidth(): int
    {
        return $this->legendWidth;
    }

    public function setLegendHeight(int $legendHeight): Chart
    {
        $this->legendHeight = $legendHeight;

        $this->setOption([
            'legend' => [
                'height' => $legendHeight,
            ],
        ]);

        return $this;
    }

    public function getLegendHeight(): int
    {
        return $this->legendHeight;
    }

    public function setLegendTooltipHoverFormatter(mixed $legendTooltipHoverFormatter): Chart
    {
        $this->legendTooltipHoverFormatter = $legendTooltipHoverFormatter;

        $this->setOption([
            'legend' => [
                'tooltipHoverFormatter' => $legendTooltipHoverFormatter,
            ],
        ]);

        return $this;
    }

    public function getLegendTooltipHoverFormatter(): mixed
    {
        return $this->legendTooltipHoverFormatter;
    }

    public function setLegendCustomLegendItems(array $legendCustomLegendItems): Chart
    {
        $this->legendCustomLegendItems = $legendCustomLegendItems;

        $this->setOption([
            'legend' => [
                'customLegendItems' => $legendCustomLegendItems,
            ],
        ]);

        return $this;
    }

    public function getLegendCustomLegendItems(): array
    {
        return $this->legendCustomLegendItems;
    }

    public function setLegendOffsetX(int $legendOffsetX): Chart
    {
        $this->legendOffsetX = $legendOffsetX;

        $this->setOption([
            'legend' => [
                'offsetX' => $legendOffsetX,
            ],
        ]);

        return $this;
    }

    public function getLegendOffsetX(): int
    {
        return $this->legendOffsetX;
    }

    public function setLegendOffsetY(int $legendOffsetY): Chart
    {
        $this->legendOffsetY = $legendOffsetY;

        $this->setOption([
            'legend' => [
                'offsetY' => $legendOffsetY,
            ],
        ]);

        return $this;
    }

    public function getLegendOffsetY():int
    {
        return $this->legendOffsetY;
    }

    public function setLegendLabels(array $legendLabels): Chart
    {
        $this->legendLabels = $legendLabels;

        $this->setOption([
            'legend' => [
                'labels' => $legendLabels,
            ],
        ]);

        return $this;
    }

    public function getLegendLabels(): array
    {
        return $this->legendLabels;
    }

    public function setLegendMarkers(array $legendMarkers): Chart
    {
        $this->legendMarkers = $legendMarkers;

        $this->setOption([
            'legend' => [
                'markers' => $legendMarkers,
            ],
        ]);

        return $this;
    }

    public function getLegendMarkers(): array
    {
        return $this->legendMarkers;
    }

    public function setLegendItemMargin(array $legendItemMargin): Chart
    {
        $this->legendItemMargin = $legendItemMargin;

        $this->setOption([
            'legend' => [
                'itemMargin' => $legendItemMargin,
            ],
        ]);

        return $this;
    }

    public function getLegendItemMargin(): array
    {
        return $this->legendItemMargin;
    }

    public function setLegendOnItemClick(array $legendOnItemClick): Chart
    {
        $this->legendOnItemClick = $legendOnItemClick;

        $this->setOption([
            'legend' => [
                'onItemClick' => $legendOnItemClick,
            ],
        ]);

        return $this;
    }

    public function getLegendOnItemClick(): array
    {
        return $this->legendOnItemClick;
    }

    public function setLegendOnItemHover(array $legendOnItemHover): Chart
    {
        $this->legendOnItemHover = $legendOnItemHover;

        $this->setOption([
            'legend' => [
                'onItemHover' => $legendOnItemHover,
            ],
        ]);

        return $this;
    }

    public function getLegendOnItemHover(): array
    {
        return $this->legendOnItemHover;
    }
}
