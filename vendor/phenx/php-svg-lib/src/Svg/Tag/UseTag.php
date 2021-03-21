<?php
/**
 * @package php-svg-lib
 * @link    http://github.com/PhenX/php-svg-lib
 * @author  Fabien M�nager <fabien.menager@gmail.com>
 * @license GNU LGPLv3+ http://www.gnu.org/copyleft/lesser.html
 */

namespace Svg\Tag;

class UseTag extends AbstractTag
{
    protected $x = 0;
    protected $y = 0;
    protected $width;
    protected $height;

    /** @var AbstractTag */
    protected $reference;

    protected function before($attributes)
    {
        if (isset($attributes['x'])) {
            $this->x = $attributes['x'];
        }
        if (isset($attributes['y'])) {
            $this->y = $attributes['y'];
        }

        if (isset($attributes['width'])) {
            $this->width = $attributes['width'];
        }
        if (isset($attributes['height'])) {
            $this->height = $attributes['height'];
        }

        parent::before($attributes);

        $document = $this->getDocument();

        $link = $attributes["xlink:href"];
        $this->reference = $document->getDef($link);

        if ($this->reference) {
            $this->reference->before($attributes);
        }

        $surface = $document->getSurface();
        $surface->save();

        $surface->translate($this->x, $this->y);
    }

    protected function after() {
        parent::after();

        if ($this->reference) {
            $this->reference->after();
        }

        $this->getDocument()->getSurface()->restore();
    }

    public function handle($attributes)
    {
        parent::handle($attributes);

        if (!$this->reference) {
            return;
        }

        $attributes = array_merge($this->reference->attributes, $attributes);

        $this->reference->handle($attributes);

        foreach ($this->reference->children as $_child) {
            $_attributes = array_merge($_child->attributes, $attributes);
            $_child->handle($_attributes);
        }
    }

    public function handleEnd()
    {
        parent::handleEnd();

        if (!$this->reference) {
            return;
        }

        $this->reference->handleEnd();

        foreach ($this->reference->children as $_child) {
            $_child->handleEnd();
        }
    }
} 