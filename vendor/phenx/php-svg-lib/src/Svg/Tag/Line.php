<?php
/**
 * @package php-svg-lib
 * @link    http://github.com/PhenX/php-svg-lib
 * @author  Fabien Ménager <fabien.menager@gmail.com>
 * @license GNU LGPLv3+ http://www.gnu.org/copyleft/lesser.html
 */

namespace Svg\Tag;

class Line extends Shape
{
    protected $x1 = 0;
    protected $y1 = 0;

    protected $x2 = 0;
    protected $y2 = 0;

    public function start($attributes)
    {
        if (isset($attributes['x1'])) {
            $this->x1 = $attributes['x1'];
        }
        if (isset($attributes['y1'])) {
            $this->y1 = $attributes['y1'];
        }
        if (isset($attributes['x2'])) {
            $this->x2 = $attributes['x2'];
        }
        if (isset($attributes['y2'])) {
            $this->y2 = $attributes['y2'];
        }

        $surface = $this->document->getSurface();
        $surface->moveTo($this->x1, $this->y1);
        $surface->lineTo($this->x2, $this->y2);
    }
} 