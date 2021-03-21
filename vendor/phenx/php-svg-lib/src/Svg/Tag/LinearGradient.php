<?php
/**
 * @package php-svg-lib
 * @link    http://github.com/PhenX/php-svg-lib
 * @author  Fabien Ménager <fabien.menager@gmail.com>
 * @license GNU LGPLv3+ http://www.gnu.org/copyleft/lesser.html
 */

namespace Svg\Tag;


use Svg\Gradient;
use Svg\Style;

class LinearGradient extends AbstractTag
{
    protected $x1;
    protected $y1;
    protected $x2;
    protected $y2;

    /** @var Gradient\Stop[] */
    protected $stops = array();

    public function start($attributes)
    {
        parent::start($attributes);

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
    }

    public function getStops() {
        if (empty($this->stops)) {
            foreach ($this->children as $_child) {
                if ($_child->tagName != "stop") {
                    continue;
                }

                $_stop = new Gradient\Stop();
                $_attributes = $_child->attributes;

                // Style
                if (isset($_attributes["style"])) {
                    $_style = Style::parseCssStyle($_attributes["style"]);

                    if (isset($_style["stop-color"])) {
                        $_stop->color = Style::parseColor($_style["stop-color"]);
                    }

                    if (isset($_style["stop-opacity"])) {
                        $_stop->opacity = max(0, min(1.0, $_style["stop-opacity"]));
                    }
                }

                // Attributes
                if (isset($_attributes["offset"])) {
                    $_stop->offset = $_attributes["offset"];
                }
                if (isset($_attributes["stop-color"])) {
                    $_stop->color = Style::parseColor($_attributes["stop-color"]);
                }
                if (isset($_attributes["stop-opacity"])) {
                    $_stop->opacity = max(0, min(1.0, $_attributes["stop-opacity"]));
                }

                $this->stops[] = $_stop;
            }
        }

        return $this->stops;
    }
} 