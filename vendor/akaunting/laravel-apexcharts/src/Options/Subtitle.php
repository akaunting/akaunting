<?php

namespace Akaunting\Apexcharts\Options;

use Akaunting\Apexcharts\Chart;

trait Subtitle
{
    public string $subtitle = '';

    public string $subtitleAlign = 'left';

    public int $subtitleMargin = 10;

    public int $subtitleOffsetX = 0;

    public int $subtitleOffsetY = 0;

    public bool $subtitleFloating = false;

    public array $subtitleStyle = [];

    public function setSubtitle(string $subtitle): Chart
    {
        $this->subtitle = $subtitle;

        $this->setOption([
            'subtitle' => [
                'text' => $subtitle,
            ],
        ]);

        return $this;
    }

    public function getSubtitle(): string
    {
        return $this->subtitle;
    }

    public function setSubtitlePosition(string $subtitlePosition): Chart
    {
        $this->setSubtitleAlign($subtitlePosition);

        return $this;
    }

    public function getSubtitlePosition(): string
    {
        return $this->getSubtitleAlign();
    }

    public function setSubtitleAlign(string $subtitleAlign): Chart
    {
        $this->subtitleAlign = $subtitleAlign;

        $this->setOption([
            'subtitle' => [
                'align' => $subtitleAlign,
            ],
        ]);

        return $this;
    }

    public function getSubtitleAlign(): string
    {
        return $this->subtitleAlign;
    }

    public function setSubtitleMargin(int $subtitleMargin): Chart
    {
        $this->subtitleMargin = $subtitleMargin;

        $this->setOption([
            'subtitle' => [
                'margin' => $subtitleMargin,
            ],
        ]);

        return $this;
    }

    public function getSubtitleMargin(): int
    {
        return $this->subtitleMargin;
    }

    public function setSubtitleOffsetX(int $subtitleOffsetX): Chart
    {
        $this->subtitleOffsetX = $subtitleOffsetX;

        $this->setOption([
            'subtitle' => [
                'offsetX' => $subtitleOffsetX,
            ],
        ]);

        return $this;
    }

    public function getSubtitleOffsetX(): int
    {
        return $this->subtitleOffsetX;
    }

    public function setSubtitleOffsetY(int $subtitleOffsetY): Chart
    {
        $this->subtitleOffsetY = $subtitleOffsetY;

        $this->setOption([
            'subtitle' => [
                'offsetY' => $subtitleOffsetY,
            ],
        ]);

        return $this;
    }

    public function getSubtitleOffsetY(): int
    {
        return $this->subtitleOffsetY;
    }

    public function setSubtitleFloating(bool $subtitleFloating): Chart
    {
        $this->fontFamily = $subtitleFloating;

        $this->setOption([
            'subtitle' => [
                'floating' => $subtitleFloating,
            ],
        ]);

        return $this;
    }

    public function getSubtitleFloating(): bool
    {
        return $this->subtitleFloating;
    }

    public function setSubtitleStyle(array $subtitleStyle): Chart
    {
        $this->subtitleStyle = $subtitleStyle;

        $this->setOption([
            'subtitle' => [
                'style' => $subtitleStyle,
            ],
        ]);

        return $this;
    }

    public function getSubtitleStyle(): array
    {
        return $this->subtitleStyle;
    }
}
