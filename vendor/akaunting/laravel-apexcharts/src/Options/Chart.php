<?php

namespace Akaunting\Apexcharts\Options;

use Akaunting\Apexcharts\Chart as MainChart;

trait Chart
{
    public array $animations = [];

    public string $background = '#fff';

    public array $brush = [];

    public string $defaultLocale = 'en';

    public array $dropShadow = [];

    public string $fontFamily = '';

    public string $foreColor = '';

    public string $group = '';

    public array $events = [];

    public int|string|null $height = 400;

    public array $locales = [];

    public int $offsetX = 0;

    public int $offsetY = 0;

    public int $parentHeightOffset = 15;

    public bool $redrawOnParentResize = true;

    public bool $redrawOnWindowResize = true;

    public array $selection = [];

    public array $sparkline = [];

    public bool $stacked = false;

    public string $stackType = 'normal';

    public array $toolbar = [];

    public int|string|null $width = null;

    public array $zoom = [];

    public function setAnimations(array $animations): MainChart
    {
        $this->animations = $animations;

        $this->setOption([
            'chart' => [
                'animations' => $animations,
            ],
        ]);

        return $this;
    }

    public function getAnimations(): array
    {
        return $this->animations;
    }

    public function setBackground(string $background): MainChart
    {
        $this->background = $background;

        $this->setOption([
            'chart' => [
                'background' => $background,
            ],
        ]);

        return $this;
    }

    public function getBackground(): string
    {
        return $this->background;
    }

    public function setBrush(array $brush): MainChart
    {
        $this->brush = $brush;

        $this->setOption([
            'chart' => [
                'brush' => $brush,
            ],
        ]);

        return $this;
    }

    public function getBrush(): array
    {
        return $this->brush;
    }

    public function setDefaultLocale(string $defaultLocale): MainChart
    {
        $this->defaultLocale = $defaultLocale;

        $this->setOption([
            'chart' => [
                'defaultLocale' => $defaultLocale,
            ],
        ]);

        return $this;
    }

    public function getDefaultLocale(): string
    {
        return $this->defaultLocale;
    }

    public function setDropShadow(array $dropShadow): MainChart
    {
        $this->dropShadow = $dropShadow;

        $this->setOption([
            'chart' => [
                'dropShadow' => $dropShadow,
            ],
        ]);

        return $this;
    }

    public function getDropShadow(): array
    {
        return $this->dropShadow;
    }

    public function setFontFamily(string $fontFamily): MainChart
    {
        $this->fontFamily = $fontFamily;

        $this->setOption([
            'chart' => [
                'fontFamily' => $fontFamily,
            ],
        ]);

        return $this;
    }

    public function getFontFamily(): string
    {
        return $this->fontFamily;
    }

    public function setForeColor(string $foreColor): MainChart
    {
        $this->foreColor = $foreColor;

        $this->setOption([
            'chart' => [
                'foreColor' => $foreColor,
            ],
        ]);

        return $this;
    }

    public function getForeColor(): string
    {
        return $this->foreColor;
    }

    public function setGroup(string $group): MainChart
    {
        $this->group = $group;

        $this->setOption([
            'chart' => [
                'group' => $group,
            ],
        ]);

        return $this;
    }

    public function getGroup(): string
    {
        return $this->group;
    }

    public function setEvents(array $events): MainChart
    {
        $this->events = $events;

        $this->setOption([
            'chart' => [
                'events' => $events,
            ],
        ]);

        return $this;
    }

    public function getEvents(): array
    {
        return $this->events;
    }

    public function setHeight(int|string|null $height): MainChart
    {
        $this->height = $height;

        $this->setOption([
            'chart' => [
                'height' => $height,
            ],
        ]);

        return $this;
    }

    public function getHeight(): int|string|null
    {
        return $this->height;
    }

    public function setLocales(array $locales): MainChart
    {
        $this->locales = $locales;

        $this->setOption([
            'chart' => [
                'locales' => $locales,
            ],
        ]);

        return $this;
    }

    public function getLocales(): array
    {
        return $this->locales;
    }

    public function setOffsetX(int $offsetX): MainChart
    {
        $this->offsetX = $offsetX;

        $this->setOption([
            'chart' => [
                'offsetX' => $offsetX,
            ],
        ]);

        return $this;
    }

    public function getOffsetX(): int
    {
        return $this->offsetX;
    }

    public function setOffsetY(int $offsetY): MainChart
    {
        $this->offsetY = $offsetY;

        $this->setOption([
            'chart' => [
                'offsetY' => $offsetY,
            ],
        ]);

        return $this;
    }

    public function getOffsetY(): int
    {
        return $this->offsetY;
    }

    public function setParentHeightOffset(int $parentHeightOffset): MainChart
    {
        $this->parentHeightOffset = $parentHeightOffset;

        $this->setOption([
            'chart' => [
                'parentHeightOffset' => $parentHeightOffset,
            ],
        ]);

        return $this;
    }

    public function getParentHeightOffset(): int
    {
        return $this->parentHeightOffset;
    }

    public function setRedrawOnParentResize(bool $redrawOnParentResize): MainChart
    {
        $this->redrawOnParentResize = $redrawOnParentResize;

        $this->setOption([
            'chart' => [
                'redrawOnParentResize' => $redrawOnParentResize,
            ],
        ]);

        return $this;
    }

    public function getRedrawOnParentResize(): bool
    {
        return $this->redrawOnParentResize;
    }

    public function setRedrawOnWindowResize(bool $redrawOnWindowResize): MainChart
    {
        $this->redrawOnWindowResize = $redrawOnWindowResize;

        $this->setOption([
            'chart' => [
                'redrawOnWindowResize' => $redrawOnWindowResize,
            ],
        ]);

        return $this;
    }

    public function getRedrawOnWindowResize(): bool
    {
        return $this->redrawOnWindowResize;
    }

    public function setSelection(array $selection): MainChart
    {
        $this->selection = $selection;

        $this->setOption([
            'chart' => [
                'selection' => $selection,
            ],
        ]);

        return $this;
    }

    public function getSelection(): array
    {
        return $this->selection;
    }

    public function setSparkline(array $sparkline): MainChart
    {
        $this->sparkline = $sparkline;

        $this->setOption([
            'chart' => [
                'sparkline' => $sparkline,
            ],
        ]);

        return $this;
    }

    public function getSparkline(): array
    {
        return $this->sparkline;
    }

    public function setStacked(bool $stacked): MainChart
    {
        $this->stacked = $stacked;

        $this->setOption([
            'chart' => [
                'stacked' => $stacked,
            ],
        ]);

        return $this;
    }

    public function getStacked(): bool
    {
        return $this->stacked;
    }

    public function setStackType(string $stackType): MainChart
    {
        $this->stackType = $stackType;

        $this->setOption([
            'chart' => [
                'stackType' => $stackType,
            ],
        ]);

        return $this;
    }

    public function getStackType(): string
    {
        return $this->stackType;
    }

    public function setToolbar(array $toolbar): MainChart
    {
        $this->toolbar = $toolbar;

        $this->setOption([
            'chart' => [
                'toolbar' => $toolbar,
            ],
        ]);

        return $this;
    }

    public function getToolbar(): array
    {
        return $this->toolbar;
    }

    public function setWidth(int|string|null $width): MainChart
    {
        $this->width = $width;

        $this->setOption([
            'chart' => [
                'width' => $width,
            ],
        ]);

        return $this;
    }

    public function getWidth(): int|string|null
    {
        return $this->width;
    }

    public function setZoom(array $zoom): MainChart
    {
        $this->zoom = $zoom;

        $this->setOption([
            'chart' => [
                'zoom' => $zoom,
            ],
        ]);

        return $this;
    }

    public function getZoom(): array
    {
        return $this->zoom;
    }
}
