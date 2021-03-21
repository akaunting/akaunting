<?php
/**
 * @package php-svg-lib
 * @link    http://github.com/PhenX/php-svg-lib
 * @author  Fabien Ménager <fabien.menager@gmail.com>
 * @license GNU LGPLv3+ http://www.gnu.org/copyleft/lesser.html
 */

namespace Svg\Tag;

use Svg\Style;

class Shape extends AbstractTag
{
    protected function before($attributes)
    {
        $surface = $this->document->getSurface();

        $surface->save();

        $style = $this->makeStyle($attributes);

        $this->setStyle($style);
        $surface->setStyle($style);

        $this->applyTransform($attributes);
    }

    protected function after()
    {
        $surface = $this->document->getSurface();

        if ($this->hasShape) {
            $style = $surface->getStyle();

            $fill   = $style->fill   && is_array($style->fill);
            $stroke = $style->stroke && is_array($style->stroke);

            if ($fill) {
                if ($stroke) {
                    $surface->fillStroke();
                } else {
//                    if (is_string($style->fill)) {
//                        /** @var LinearGradient|RadialGradient $gradient */
//                        $gradient = $this->getDocument()->getDef($style->fill);
//
//                        var_dump($gradient->getStops());
//                    }

                    $surface->fill();
                }
            }
            elseif ($stroke) {
                $surface->stroke();
            }
            else {
                $surface->endPath();
            }
        }

        $surface->restore();
    }
} 