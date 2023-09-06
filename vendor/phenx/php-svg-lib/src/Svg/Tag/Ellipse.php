<?php
/**
 * @package php-svg-lib
 * @link    http://github.com/PhenX/php-svg-lib
 * @author  Fabien Ménager <fabien.menager@gmail.com>
 * @license GNU LGPLv3+ http://www.gnu.org/copyleft/lesser.html
 */

namespace Svg\Tag;

use Svg\Style;

class Ellipse extends Shape
{
    protected $cx = 0;
    protected $cy = 0;
    protected $rx = 0;
    protected $ry = 0;

    public function start($attributes)
    {
        parent::start($attributes);

        $width = $this->document->getWidth();
        $height = $this->document->getHeight();

        if (isset($attributes['cx'])) {
            $this->cx = $this->convertSize($attributes['cx'], $width);
        }
        if (isset($attributes['cy'])) {
            $this->cy = $this->convertSize($attributes['cy'], $height);
        }
        if (isset($attributes['rx'])) {
            $this->rx = $this->convertSize($attributes['rx'], $width);
        }
        if (isset($attributes['ry'])) {
            $this->ry = $this->convertSize($attributes['ry'], $height);
        }

        $this->document->getSurface()->ellipse($this->cx, $this->cy, $this->rx, $this->ry, 0, 0, 360, false);
    }
} 
