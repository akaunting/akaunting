<?php
/**
 * @package php-svg-lib
 * @link    http://github.com/PhenX/php-svg-lib
 * @author  Fabien Ménager <fabien.menager@gmail.com>
 * @license GNU LGPLv3+ http://www.gnu.org/copyleft/lesser.html
 */

namespace Svg\Tag;

class Image extends AbstractTag
{
    protected $x = 0;
    protected $y = 0;
    protected $width = 0;
    protected $height = 0;
    protected $href = null;

    protected function before($attributes)
    {
        parent::before($attributes);

        $surface = $this->document->getSurface();
        $surface->save();

        $this->applyTransform($attributes);
    }

    public function start($attributes)
    {
        $document = $this->document;
        $height = $this->document->getHeight();
        $this->y = $height;

        if (isset($attributes['x'])) {
            $this->x = $attributes['x'];
        }
        if (isset($attributes['y'])) {
            $this->y = $height - $attributes['y'];
        }

        if (isset($attributes['width'])) {
            $this->width = $attributes['width'];
        }
        if (isset($attributes['height'])) {
            $this->height = $attributes['height'];
        }

        if (isset($attributes['xlink:href'])) {
            $this->href = $attributes['xlink:href'];
        }

        $document->getSurface()->transform(1, 0, 0, -1, 0, $height);

        $document->getSurface()->drawImage($this->href, $this->x, $this->y, $this->width, $this->height);
    }

    protected function after()
    {
        $this->document->getSurface()->restore();
    }
} 