<?php
/**
 * @package php-svg-lib
 * @link    http://github.com/PhenX/php-svg-lib
 * @author  Fabien Ménager <fabien.menager@gmail.com>
 * @license GNU LGPLv3+ http://www.gnu.org/copyleft/lesser.html
 */

namespace Svg\Tag;

use Svg\Style;

class Rect extends Shape
{
    protected $x = 0;
    protected $y = 0;
    protected $width = 0;
    protected $height = 0;
    protected $rx = 0;
    protected $ry = 0;

    public function start($attributes)
    {
        $width = $this->document->getWidth();
        $height = $this->document->getHeight();

        if (isset($attributes['x'])) {
            $this->x = $this->convertSize($attributes['x'], $width);
        }
        if (isset($attributes['y'])) {
            $this->y = $this->convertSize($attributes['y'], $height);
        }

        if (isset($attributes['width'])) {
            $this->width = $this->convertSize($attributes['width'], $width);
        }
        if (isset($attributes['height'])) {
            $this->height = $this->convertSize($attributes['height'], $height);
        }

        if (isset($attributes['rx'])) {
            $this->rx = $attributes['rx'];
        }
        if (isset($attributes['ry'])) {
            $this->ry = $attributes['ry'];
        }

        $this->document->getSurface()->rect($this->x, $this->y, $this->width, $this->height, $this->rx, $this->ry);
    }
} 
