<?php
/**
 * @package php-svg-lib
 * @link    http://github.com/PhenX/php-svg-lib
 * @author  Fabien M�nager <fabien.menager@gmail.com>
 * @license GNU LGPLv3+ http://www.gnu.org/copyleft/lesser.html
 */

namespace Svg\Surface;

use Svg\Style;

class SurfaceGmagick implements SurfaceInterface
{
    const DEBUG = false;

    /** @var \GmagickDraw */
    private $canvas;

    private $width;
    private $height;

    /** @var Style */
    private $style;

    public function __construct($w, $h)
    {
        if (self::DEBUG) {
            echo __FUNCTION__ . "\n";
        }
        $this->width = $w;
        $this->height = $h;

        $canvas = new \GmagickDraw();

        $this->canvas = $canvas;
    }

    function out()
    {
        if (self::DEBUG) {
            echo __FUNCTION__ . "\n";
        }

        $image = new \Gmagick();
        $image->newimage($this->width, $this->height);
        $image->drawimage($this->canvas);

        $tmp = tempnam("", "gm");

        $image->write($tmp);

        return file_get_contents($tmp);
    }

    public function save()
    {
        if (self::DEBUG) {
            echo __FUNCTION__ . "\n";
        }
        $this->canvas->save();
    }

    public function restore()
    {
        if (self::DEBUG) echo __FUNCTION__ . "\n";
        $this->canvas->restore();
    }

    public function scale($x, $y)
    {
        if (self::DEBUG) echo __FUNCTION__ . "\n";
        $this->canvas->scale($x, $y);
    }

    public function rotate($angle)
    {
        if (self::DEBUG) echo __FUNCTION__ . "\n";
        $this->canvas->rotate($angle);
    }

    public function translate($x, $y)
    {
        if (self::DEBUG) echo __FUNCTION__ . "\n";
        $this->canvas->translate($x, $y);
    }

    public function transform($a, $b, $c, $d, $e, $f)
    {
        if (self::DEBUG) echo __FUNCTION__ . "\n";
        $this->canvas->concat($a, $b, $c, $d, $e, $f);
    }

    public function beginPath()
    {
        if (self::DEBUG) echo __FUNCTION__ . "\n";
        // TODO: Implement beginPath() method.
    }

    public function closePath()
    {
        if (self::DEBUG) echo __FUNCTION__ . "\n";
        $this->canvas->closepath();
    }

    public function fillStroke()
    {
        if (self::DEBUG) echo __FUNCTION__ . "\n";
        $this->canvas->fill_stroke();
    }

    public function clip()
    {
        if (self::DEBUG) echo __FUNCTION__ . "\n";
        $this->canvas->clip();
    }

    public function fillText($text, $x, $y, $maxWidth = null)
    {
        if (self::DEBUG) echo __FUNCTION__ . "\n";
        $this->canvas->set_text_pos($x, $y);
        $this->canvas->show($text);
    }

    public function strokeText($text, $x, $y, $maxWidth = null)
    {
        if (self::DEBUG) echo __FUNCTION__ . "\n";
        // TODO: Implement drawImage() method.
    }

    public function drawImage($image, $sx, $sy, $sw = null, $sh = null, $dx = null, $dy = null, $dw = null, $dh = null)
    {
        if (self::DEBUG) echo __FUNCTION__ . "\n";

        if (strpos($image, "data:") === 0) {
            $data = substr($image, strpos($image, ";") + 1);
            if (strpos($data, "base64") === 0) {
                $data = base64_decode(substr($data, 7));
            }

            $image = tempnam("", "svg");
            file_put_contents($image, $data);
        }

        $img = $this->canvas->load_image("auto", $image, "");

        $sy = $sy - $sh;
        $this->canvas->fit_image($img, $sx, $sy, 'boxsize={' . "$sw $sh" . '} fitmethod=entire');
    }

    public function lineTo($x, $y)
    {
        if (self::DEBUG) echo __FUNCTION__ . "\n";
        $this->canvas->lineto($x, $y);
    }

    public function moveTo($x, $y)
    {
        if (self::DEBUG) echo __FUNCTION__ . "\n";
        $this->canvas->moveto($x, $y);
    }

    public function quadraticCurveTo($cpx, $cpy, $x, $y)
    {
        if (self::DEBUG) echo __FUNCTION__ . "\n";
        // TODO: Implement quadraticCurveTo() method.
    }

    public function bezierCurveTo($cp1x, $cp1y, $cp2x, $cp2y, $x, $y)
    {
        if (self::DEBUG) echo __FUNCTION__ . "\n";
        $this->canvas->curveto($cp1x, $cp1y, $cp2x, $cp2y, $x, $y);
    }

    public function arcTo($x1, $y1, $x2, $y2, $radius)
    {
        if (self::DEBUG) echo __FUNCTION__ . "\n";
    }

    public function arc($x, $y, $radius, $startAngle, $endAngle, $anticlockwise = false)
    {
        if (self::DEBUG) echo __FUNCTION__ . "\n";
        $this->canvas->arc($x, $y, $radius, $startAngle, $endAngle);
    }

    public function circle($x, $y, $radius)
    {
        if (self::DEBUG) echo __FUNCTION__ . "\n";
        $this->canvas->circle($x, $y, $radius);
    }

    public function ellipse($x, $y, $radiusX, $radiusY, $rotation, $startAngle, $endAngle, $anticlockwise)
    {
        if (self::DEBUG) echo __FUNCTION__ . "\n";
        $this->canvas->ellipse($x, $y, $radiusX, $radiusY);
    }

    public function fillRect($x, $y, $w, $h)
    {
        if (self::DEBUG) echo __FUNCTION__ . "\n";
        $this->rect($x, $y, $w, $h);
        $this->fill();
    }

    public function rect($x, $y, $w, $h, $rx = 0, $ry = 0)
    {
        if (self::DEBUG) echo __FUNCTION__ . "\n";
        $this->canvas->rect($x, $y, $w, $h);
    }

    public function fill()
    {
        if (self::DEBUG) echo __FUNCTION__ . "\n";
        $this->canvas->fill();
    }

    public function strokeRect($x, $y, $w, $h)
    {
        if (self::DEBUG) echo __FUNCTION__ . "\n";
        $this->rect($x, $y, $w, $h);
        $this->stroke();
    }

    public function stroke()
    {
        if (self::DEBUG) echo __FUNCTION__ . "\n";
        $this->canvas->stroke();
    }

    public function endPath()
    {
        if (self::DEBUG) echo __FUNCTION__ . "\n";
        //$this->canvas->endPath();
    }

    public function measureText($text)
    {
        if (self::DEBUG) echo __FUNCTION__ . "\n";
        $style = $this->getStyle();
        $font = $this->getFont($style->fontFamily, $style->fontStyle);

        return $this->canvas->stringwidth($text, $font, $this->getStyle()->fontSize);
    }

    public function getStyle()
    {
        if (self::DEBUG) echo __FUNCTION__ . "\n";

        return $this->style;
    }

    public function setStyle(Style $style)
    {
        if (self::DEBUG) echo __FUNCTION__ . "\n";

        $this->style = $style;
        $canvas = $this->canvas;

        if (is_array($style->stroke) && $stroke = $style->stroke) {
            $canvas->setcolor("stroke", "rgb", $stroke[0] / 255, $stroke[1] / 255, $stroke[2] / 255, null);
        }

        if (is_array($style->fill) && $fill = $style->fill) {
           // $canvas->setcolor("fill", "rgb", $fill[0] / 255, $fill[1] / 255, $fill[2] / 255, null);
        }

        $opts = array();
        if ($style->strokeWidth > 0.000001) {
            $opts[] = "linewidth=$style->strokeWidth";
        }

        if (in_array($style->strokeLinecap, array("butt", "round", "projecting"))) {
            $opts[] = "linecap=$style->strokeLinecap";
        }

        if (in_array($style->strokeLinejoin, array("miter", "round", "bevel"))) {
            $opts[] = "linejoin=$style->strokeLinejoin";
        }

        $canvas->set_graphics_option(implode(" ", $opts));

        $font = $this->getFont($style->fontFamily, $style->fontStyle);
        $canvas->setfont($font, $style->fontSize);
    }

    private function getFont($family, $style)
    {
        $map = array(
            "serif"      => "Times",
            "sans-serif" => "Helvetica",
            "fantasy"    => "Symbol",
            "cursive"    => "serif",
            "monospance" => "Courier",
        );

        $family = strtolower($family);
        if (isset($map[$family])) {
            $family = $map[$family];
        }

        return $this->canvas->load_font($family, "unicode", "fontstyle=$style");
    }

    public function setFont($family, $style, $weight)
    {
        // TODO: Implement setFont() method.
    }
}