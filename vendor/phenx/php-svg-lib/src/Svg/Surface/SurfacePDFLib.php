<?php
/**
 * @package php-svg-lib
 * @link    http://github.com/PhenX/php-svg-lib
 * @author  Fabien Ménager <fabien.menager@gmail.com>
 * @license GNU LGPLv3+ http://www.gnu.org/copyleft/lesser.html
 */

namespace Svg\Surface;

use Svg\Style;
use Svg\Document;

class SurfacePDFLib implements SurfaceInterface
{
    const DEBUG = false;

    private $canvas;

    private $width;
    private $height;

    /** @var Style */
    private $style;

    public function __construct(Document $doc, $canvas = null)
    {
        if (self::DEBUG) echo __FUNCTION__ . "\n";

        $dimensions = $doc->getDimensions();
        $w = $dimensions["width"];
        $h = $dimensions["height"];

        if (!$canvas) {
            $canvas = new \PDFlib();

            /* all strings are expected as utf8 */
            $canvas->set_option("stringformat=utf8");
            $canvas->set_option("errorpolicy=return");

            /*  open new PDF file; insert a file name to create the PDF on disk */
            if ($canvas->begin_document("", "") == 0) {
                die("Error: " . $canvas->get_errmsg());
            }
            $canvas->set_info("Creator", "PDFlib starter sample");
            $canvas->set_info("Title", "starter_graphics");

            $canvas->begin_page_ext($w, $h, "");
        }

        // Flip PDF coordinate system so that the origin is in
        // the top left rather than the bottom left
        $canvas->setmatrix(
            1, 0,
            0, -1,
            0, $h
        );

        $this->width  = $w;
        $this->height = $h;

        $this->canvas = $canvas;
    }

    function out()
    {
        if (self::DEBUG) echo __FUNCTION__ . "\n";

        $this->canvas->end_page_ext("");
        $this->canvas->end_document("");

        return $this->canvas->get_buffer();
    }

    public function save()
    {
        if (self::DEBUG) echo __FUNCTION__ . "\n";
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

    public function fillStroke(bool $close = false)
    {
        if (self::DEBUG) echo __FUNCTION__ . "\n";
        if ($close) {
            $this->canvas->closepath_fill_stroke();
        } else {
            $this->canvas->fill_stroke();
        }
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
        }
        else {
            $data = file_get_contents($image);
        }

        $image = tempnam(sys_get_temp_dir(), "svg");
        file_put_contents($image, $data);

        $img = $this->canvas->load_image("auto", $image, "");

        $sy = $sy - $sh;
        $this->canvas->fit_image($img, $sx, $sy, 'boxsize={' . "$sw $sh" . '} fitmethod=entire');

        unlink($image);
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

        // FIXME not accurate
        $this->canvas->curveTo($cpx, $cpy, $cpx, $cpy, $x, $y);
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

        $canvas = $this->canvas;

        if ($rx <= 0.000001/* && $ry <= 0.000001*/) {
            $canvas->rect($x, $y, $w, $h);

            return;
        }

        /* Define a path for a rectangle with corners rounded by a given radius.
         * Start from the lower left corner and proceed counterclockwise.
         */
        $canvas->moveto($x + $rx, $y);

        /* Start of the arc segment in the lower right corner */
        $canvas->lineto($x + $w - $rx, $y);

        /* Arc segment in the lower right corner */
        $canvas->arc($x + $w - $rx, $y + $rx, $rx, 270, 360);

        /* Start of the arc segment in the upper right corner */
        $canvas->lineto($x + $w, $y + $h - $rx );

        /* Arc segment in the upper right corner */
        $canvas->arc($x + $w - $rx, $y + $h - $rx, $rx, 0, 90);

        /* Start of the arc segment in the upper left corner */
        $canvas->lineto($x + $rx, $y + $h);

        /* Arc segment in the upper left corner */
        $canvas->arc($x + $rx, $y + $h - $rx, $rx, 90, 180);

        /* Start of the arc segment in the lower left corner */
        $canvas->lineto($x , $y + $rx);

        /* Arc segment in the lower left corner */
        $canvas->arc($x + $rx, $y + $rx, $rx, 180, 270);
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

    public function stroke(bool $close = false)
    {
        if (self::DEBUG) echo __FUNCTION__ . "\n";
        if ($close) {
            $this->canvas->closepath_stroke();
        } else {
            $this->canvas->stroke();
        }
    }

    public function endPath()
    {
        if (self::DEBUG) echo __FUNCTION__ . "\n";
        $this->canvas->endPath();
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
            $canvas->setcolor(
                "stroke",
                "rgb",
                $stroke[0] / 255,
                $stroke[1] / 255,
                $stroke[2] / 255,
                null
            );
        }

        if (is_array($style->fill) && $fill = $style->fill) {
            $canvas->setcolor(
                "fill",
                "rgb",
                $fill[0] / 255,
                $fill[1] / 255,
                $fill[2] / 255,
                null
            );
        }

        if ($fillRule = strtolower($style->fillRule)) {
            $map = array(
                "nonzero" => "winding",
                "evenodd" => "evenodd",
            );

            if (isset($map[$fillRule])) {
                $fillRule = $map[$fillRule];

                $canvas->set_parameter("fillrule", $fillRule);
            }
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

        $opts = array();
        $opacity = $style->opacity;
        if ($opacity !== null && $opacity < 1.0) {
            $opts[] = "opacityfill=$opacity";
            $opts[] = "opacitystroke=$opacity";
        }
        else {
            $fillOpacity = $style->fillOpacity;
            if ($fillOpacity !== null && $fillOpacity < 1.0) {
                $opts[] = "opacityfill=$fillOpacity";
            }

            $strokeOpacity = $style->strokeOpacity;
            if ($strokeOpacity !== null && $strokeOpacity < 1.0) {
                $opts[] = "opacitystroke=$strokeOpacity";
            }
        }

        if (count($opts)) {
            $gs = $canvas->create_gstate(implode(" ", $opts));
            $canvas->set_gstate($gs);
        }

        $font = $this->getFont($style->fontFamily, $style->fontStyle);
        if ($font) {
            $canvas->setfont($font, $style->fontSize);
        }
    }

    private function getFont($family, $style)
    {
        $map = array(
            "serif"      => "Times",
            "sans-serif" => "Helvetica",
            "fantasy"    => "Symbol",
            "cursive"    => "Times",
            "monospace"  => "Courier",

            "arial"      => "Helvetica",
            "verdana"    => "Helvetica",
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
