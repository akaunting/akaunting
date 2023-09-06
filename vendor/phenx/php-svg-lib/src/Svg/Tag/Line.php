<?php
/**
 * @package php-svg-lib
 * @link    http://github.com/PhenX/php-svg-lib
 * @author  Fabien Ménager <fabien.menager@gmail.com>
 * @license GNU LGPLv3+ http://www.gnu.org/copyleft/lesser.html
 */

namespace Svg\Tag;

use Svg\Style;

class Line extends Shape
{
    protected $x1 = 0;
    protected $y1 = 0;

    protected $x2 = 0;
    protected $y2 = 0;

    public function start($attributes)
    {
        $height = $this->document->getHeight();
        $width = $this->document->getWidth();

        if (isset($attributes['x1'])) {
            $this->x1 = $this->convertSize($attributes['x1'], $width);
        }
        if (isset($attributes['y1'])) {
            $this->y1 = $this->convertSize($attributes['y1'], $height);
        }
        if (isset($attributes['x2'])) {
            $this->x2 = $this->convertSize($attributes['x2'], $width);
        }
        if (isset($attributes['y2'])) {
            $this->y2 = $this->convertSize($attributes['y2'], $height);
        }

        $surface = $this->document->getSurface();
        $surface->moveTo($this->x1, $this->y1);
        $surface->lineTo($this->x2, $this->y2);
    }
} 
