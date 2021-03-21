<?php
/**
 * @package php-svg-lib
 * @link    http://github.com/PhenX/php-svg-lib
 * @author  Fabien Ménager <fabien.menager@gmail.com>
 * @license GNU LGPLv3+ http://www.gnu.org/copyleft/lesser.html
 */

namespace Svg\Tag;

class Polygon extends Shape
{
    public function start($attributes)
    {
        $tmp = array();
        preg_match_all('/([\-]*[0-9\.]+)/', $attributes['points'], $tmp);

        $points = $tmp[0];
        $count = count($points);

        $surface = $this->document->getSurface();
        list($x, $y) = $points;
        $surface->moveTo($x, $y);

        for ($i = 2; $i < $count; $i += 2) {
            $x = $points[$i];
            $y = $points[$i + 1];
            $surface->lineTo($x, $y);
        }

        $surface->closePath();
    }
} 