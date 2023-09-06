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
        preg_match_all('/([\-]*[0-9\.]+)/', $attributes['points'], $tmp, PREG_PATTERN_ORDER);

        $points = $tmp[0];
        $count = count($points);

        if ($count < 4) {
            // nothing to draw
            return;
        }

        $surface = $this->document->getSurface();
        list($x, $y) = $points;
        $surface->moveTo($x, $y);

        for ($i = 2; $i < $count; $i += 2) {
            if ($i + 1 === $count) {
                // invalid trailing point
                continue;
            }
            $x = $points[$i];
            $y = $points[$i + 1];
            $surface->lineTo($x, $y);
        }

        $surface->closePath();
    }
} 
