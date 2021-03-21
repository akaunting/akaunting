<?php

namespace ConsoleTVs\Charts\Features\Fusioncharts;

trait Chart
{
    /**
     * Set the chart label.
     *
     * @param string $label
     *
     * @return self
     */
    public function label(string $label)
    {
        return $this->options([
            'yAxisName' => $label,
        ]);
    }

    /**
     * Set the chart title.
     *
     * @param string $title
     * @param int    $font_size
     * @param string $color
     * @param bool   $bold
     * @param string $font_family
     *
     * @return self
     */
    public function title(
        string $title,
        int $font_size = 16,
        string $color = '#3D4852',
        bool $bold = true,
        string $font_family = "'Helvetica Neue', 'Helvetica', 'Arial', sans-serif"
    ) {
        return $this->options([
            'caption'          => $title,
            'captionFontSize'  => $font_size,
            'captionFontColor' => $color,
            'captionFontBold'  => $bold,
            'captionFont'      => $font_family,
        ]);
    }

    /**
     * Set the chart subtitle.
     *
     * @param string $subtitle
     * @param int    $font_size
     * @param string $color
     * @param bool   $bold
     * @param string $font_family
     *
     * @return self
     */
    public function subtitle(
        string $subtitle,
        int $font_size = 12,
        string $color = '#3D4852',
        bool $bold = true,
        string $font_family = "'Helvetica Neue', 'Helvetica', 'Arial', sans-serif"
    ) {
        return $this->options([
            'subCaption'          => $subtitle,
            'subCaptionFontSize'  => $font_size,
            'subCaptionFontColor' => $color,
            'subCaptionFontBold'  => $bold,
            'subCaptionFont'      => $font_family,
        ]);
    }

    /**
     * Determines if the chart should show the export button.
     *
     * @param bool $export
     * @param bool $client
     *
     * @return self
     */
    public function export(bool $export, bool $client = true)
    {
        return $this->options([
            'exportenabled'  => $export,
            'exportatclient' => $client,
        ]);
    }
}
