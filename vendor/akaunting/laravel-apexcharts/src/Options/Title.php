<?php

namespace Akaunting\Apexcharts\Options;

use Akaunting\Apexcharts\Chart;

trait Title
{
    public string $title = '';

    public string $titleAlign = 'left';

    public int $titleMargin = 10;

    public int $titleOffsetX = 0;

    public int $titleOffsetY = 0;

    public bool $titleFloating = false;

    public array $titleStyle = [];

    public function setTitle(string $title): Chart
    {
        $this->title = $title;

        $this->setOption([
            'title' => [
                'text' => $title,
            ],
        ]);

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitlePosition(string $titlePosition): Chart
    {
        $this->setTitleAlign($titlePosition);

        return $this;
    }

    public function getTitlePosition(): string
    {
        return $this->getTitleAlign();
    }

    public function setTitleAlign(string $titleAlign): Chart
    {
        $this->titleAlign = $titleAlign;

        $this->setOption([
            'title' => [
                'align' => $titleAlign,
            ],
        ]);

        return $this;
    }

    public function getTitleAlign(): string
    {
        return $this->titleAlign;
    }

    public function setTitleMargin(int $titleMargin): Chart
    {
        $this->titleMargin = $titleMargin;

        $this->setOption([
            'title' => [
                'margin' => $titleMargin,
            ],
        ]);

        return $this;
    }

    public function getTitleMargin(): int
    {
        return $this->titleMargin;
    }

    public function setTitleOffsetX(int $titleOffsetX): Chart
    {
        $this->titleOffsetX = $titleOffsetX;

        $this->setOption([
            'title' => [
                'offsetX' => $titleOffsetX,
            ],
        ]);

        return $this;
    }

    public function getTitleOffsetX(): int
    {
        return $this->titleOffsetX;
    }

    public function setTitleOffsetY(int $titleOffsetY): Chart
    {
        $this->titleOffsetY = $titleOffsetY;

        $this->setOption([
            'title' => [
                'offsetY' => $titleOffsetY,
            ],
        ]);

        return $this;
    }

    public function getTitleOffsetY(): int
    {
        return $this->titleOffsetY;
    }

    public function setTitleFloating(bool $titleFloating): Chart
    {
        $this->titleFloating = $titleFloating;

        $this->setOption([
            'title' => [
                'floating' => $titleFloating,
            ],
        ]);

        return $this;
    }

    public function getTitleFloating(): bool
    {
        return $this->titleFloating;
    }

    public function setTitleStyle(array $titleStyle): Chart
    {
        $this->titleStyle = $titleStyle;

        $this->setOption([
            'title' => [
                'style' => $titleStyle,
            ],
        ]);

        return $this;
    }

    public function getTitleStyle(): array
    {
        return $this->titleStyle;
    }
}
