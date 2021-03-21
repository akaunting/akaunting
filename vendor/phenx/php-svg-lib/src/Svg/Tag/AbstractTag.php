<?php
/**
 * @package php-svg-lib
 * @link    http://github.com/PhenX/php-svg-lib
 * @author  Fabien Ménager <fabien.menager@gmail.com>
 * @license GNU LGPLv3+ http://www.gnu.org/copyleft/lesser.html
 */

namespace Svg\Tag;

use Svg\Document;
use Svg\Style;

abstract class AbstractTag
{
    /** @var Document */
    protected $document;

    public $tagName;

    /** @var Style */
    protected $style;

    protected $attributes = array();

    protected $hasShape = true;

    /** @var self[] */
    protected $children = array();

    public function __construct(Document $document, $tagName)
    {
        $this->document = $document;
        $this->tagName = $tagName;
    }

    public function getDocument(){
        return $this->document;
    }

    /**
     * @return Group|null
     */
    public function getParentGroup() {
        $stack = $this->getDocument()->getStack();
        for ($i = count($stack)-2; $i >= 0; $i--) {
            $tag = $stack[$i];

            if ($tag instanceof Group || $tag instanceof Document) {
                return $tag;
            }
        }

        return null;
    }

    public function handle($attributes)
    {
        $this->attributes = $attributes;

        if (!$this->getDocument()->inDefs) {
            $this->before($attributes);
            $this->start($attributes);
        }
    }

    public function handleEnd()
    {
        if (!$this->getDocument()->inDefs) {
            $this->end();
            $this->after();
        }
    }

    protected function before($attributes)
    {
    }

    protected function start($attributes)
    {
    }

    protected function end()
    {
    }

    protected function after()
    {
    }

    public function getAttributes()
    {
        return $this->attributes;
    }

    protected function setStyle(Style $style)
    {
        $this->style = $style;

        if ($style->display === "none") {
            $this->hasShape = false;
        }
    }

    /**
     * @return Style
     */
    public function getStyle()
    {
        return $this->style;
    }

    /**
     * Make a style object from the tag and its attributes
     *
     * @param array $attributes
     *
     * @return Style
     */
    protected function makeStyle($attributes) {
        $style = new Style();
        $style->inherit($this);
        $style->fromStyleSheets($this, $attributes);
        $style->fromAttributes($attributes);

        return $style;
    }

    protected function applyTransform($attributes)
    {

        if (isset($attributes["transform"])) {
            $surface = $this->document->getSurface();

            $transform = $attributes["transform"];

            $match = array();
            preg_match_all(
                '/(matrix|translate|scale|rotate|skewX|skewY)\((.*?)\)/is',
                $transform,
                $match,
                PREG_SET_ORDER
            );

            $transformations = array();
            if (count($match[0])) {
                foreach ($match as $_match) {
                    $arguments = preg_split('/[ ,]+/', $_match[2]);
                    array_unshift($arguments, $_match[1]);
                    $transformations[] = $arguments;
                }
            }

            foreach ($transformations as $t) {
                switch ($t[0]) {
                    case "matrix":
                        $surface->transform($t[1], $t[2], $t[3], $t[4], $t[5], $t[6]);
                        break;

                    case "translate":
                        $surface->translate($t[1], isset($t[2]) ? $t[2] : 0);
                        break;

                    case "scale":
                        $surface->scale($t[1], isset($t[2]) ? $t[2] : $t[1]);
                        break;

                    case "rotate":
                        if (isset($t[2])) {
                            $t[3] = isset($t[3]) ? $t[3] : 0;
                            $surface->translate($t[2], $t[3]);
                            $surface->rotate($t[1]);
                            $surface->translate(-$t[2], -$t[3]);
                        } else {
                            $surface->rotate($t[1]);
                        }
                        break;

                    case "skewX":
                        $surface->skewX($t[1]);
                        break;

                    case "skewY":
                        $surface->skewY($t[1]);
                        break;
                }
            }
        }
    }
} 
