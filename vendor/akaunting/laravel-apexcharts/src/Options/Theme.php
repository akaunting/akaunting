<?php

namespace Akaunting\Apexcharts\Options;

use Akaunting\Apexcharts\Chart;

trait Theme
{
    public string $themeMode = 'light';

    public string $themePalette = 'palette1';

    public array $themeMonochrome = [];

    public function setThemeMode(string $themeMode): Chart
    {
        $this->themeMode = $themeMode;

        $this->setOption([
            'theme' => [
                'mode' => $themeMode,
            ],
        ]);

        return $this;
    }

    public function getThemeMode(): string
    {
        return $this->themeMode;
    }

    public function setThemePalette(string $themePalette): Chart
    {
        $this->themePalette = $themePalette;

        $this->setOption([
            'theme' => [
                'palette' => $themePalette,
            ],
        ]);

        return $this;
    }

    public function getThemePalette(): string
    {
        return $this->themePalette;
    }

    public function setThemeMonochrome(array $themeMonochrome): Chart
    {
        $this->themeMonochrome = $themeMonochrome;

        $this->setOption([
            'theme' => [
                'monochrome' => $themeMonochrome,
            ],
        ]);

        return $this;
    }

    public function getThemeMonochrome(): array
    {
        return $this->themeMonochrome;
    }
}
