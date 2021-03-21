<?php
/**
 * @package php-svg-lib
 * @link    http://github.com/PhenX/php-svg-lib
 * @author  Fabien M�nager <fabien.menager@gmail.com>
 * @license GNU LGPLv3+ http://www.gnu.org/copyleft/lesser.html
 */

namespace Svg\Surface;

use Svg\Document;
use Svg\Style;

class SurfaceCpdf implements SurfaceInterface
{
    const DEBUG = false;

    /** @var \CPdf\CPdf */
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
            $canvas = new \CPdf\CPdf(array(0, 0, $w, $h));
            $refl = new \ReflectionClass($canvas);
            $canvas->fontcache = realpath(dirname($refl->getFileName()) . "/../../fonts/")."/";
        }

        // Flip PDF coordinate system so that the origin is in
        // the top left rather than the bottom left
        $canvas->transform(array(
            1,  0,
            0, -1,
            0, $h
        ));

        $this->width  = $w;
        $this->height = $h;

        $this->canvas = $canvas;
    }

    function out()
    {
        if (self::DEBUG) echo __FUNCTION__ . "\n";
        return $this->canvas->output();
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

        $this->transform($x, 0, 0, $y, 0, 0);
    }

    public function rotate($angle)
    {
        if (self::DEBUG) echo __FUNCTION__ . "\n";

        $a = deg2rad($angle);
        $cos_a = cos($a);
        $sin_a = sin($a);

        $this->transform(
            $cos_a,                         $sin_a,
            -$sin_a,                         $cos_a,
            0, 0
        );
    }

    public function translate($x, $y)
    {
        if (self::DEBUG) echo __FUNCTION__ . "\n";

        $this->transform(
            1,  0,
            0,  1,
            $x, $y
        );
    }

    public function transform($a, $b, $c, $d, $e, $f)
    {
        if (self::DEBUG) echo __FUNCTION__ . "\n";

        $this->canvas->transform(array($a, $b, $c, $d, $e, $f));
    }

    public function beginPath()
    {
        if (self::DEBUG) echo __FUNCTION__ . "\n";
        // TODO: Implement beginPath() method.
    }

    public function closePath()
    {
        if (self::DEBUG) echo __FUNCTION__ . "\n";
        $this->canvas->closePath();
    }

    public function fillStroke()
    {
        if (self::DEBUG) echo __FUNCTION__ . "\n";
        $this->canvas->fillStroke();
    }

    public function clip()
    {
        if (self::DEBUG) echo __FUNCTION__ . "\n";
        $this->canvas->clip();
    }

    public function fillText($text, $x, $y, $maxWidth = null)
    {
        if (self::DEBUG) echo __FUNCTION__ . "\n";
        $this->canvas->addText($x, $y, $this->style->fontSize, $text);
    }

    public function strokeText($text, $x, $y, $maxWidth = null)
    {
        if (self::DEBUG) echo __FUNCTION__ . "\n";
        $this->canvas->addText($x, $y, $this->style->fontSize, $text);
    }

    public function drawImage($image, $sx, $sy, $sw = null, $sh = null, $dx = null, $dy = null, $dw = null, $dh = null)
    {
        if (self::DEBUG) echo __FUNCTION__ . "\n";

        if (strpos($image, "data:") === 0) {
            $parts = explode(',', $image, 2);

            $data = $parts[1];
            $base64 = false;

            $token = strtok($parts[0], ';');
            while ($token !== false) {
                if ($token == 'base64') {
                    $base64 = true;
                }

                $token = strtok(';');
            }

            if ($base64) {
                $data = base64_decode($data);
            }
        }
        else {
            $data = file_get_contents($image);
        }

        $image = tempnam("", "svg");
        file_put_contents($image, $data);

        $img = $this->image($image, $sx, $sy, $sw, $sh, "normal");


        unlink($image);
    }

    public static function getimagesize($filename)
    {
        static $cache = array();

        if (isset($cache[$filename])) {
            return $cache[$filename];
        }

        list($width, $height, $type) = getimagesize($filename);

        if ($width == null || $height == null) {
            $data = file_get_contents($filename, null, null, 0, 26);

            if (substr($data, 0, 2) === "BM") {
                $meta = unpack('vtype/Vfilesize/Vreserved/Voffset/Vheadersize/Vwidth/Vheight', $data);
                $width = (int)$meta['width'];
                $height = (int)$meta['height'];
                $type = IMAGETYPE_BMP;
            }
        }

        return $cache[$filename] = array($width, $height, $type);
    }

    function image($img, $x, $y, $w, $h, $resolution = "normal")
    {
        list($width, $height, $type) = $this->getimagesize($img);

        switch ($type) {
            case IMAGETYPE_JPEG:
                $this->canvas->addJpegFromFile($img, $x, $y - $h, $w, $h);
                break;

            case IMAGETYPE_GIF:
            case IMAGETYPE_BMP:
                // @todo use cache for BMP and GIF
                $img = $this->_convert_gif_bmp_to_png($img, $type);

            case IMAGETYPE_PNG:
                $this->canvas->addPngFromFile($img, $x, $y - $h, $w, $h);
                break;

            default:
        }
    }

    public function lineTo($x, $y)
    {
        if (self::DEBUG) echo __FUNCTION__ . "\n";
        $this->canvas->lineTo($x, $y);
    }

    public function moveTo($x, $y)
    {
        if (self::DEBUG) echo __FUNCTION__ . "\n";
        $this->canvas->moveTo($x, $y);
    }

    public function quadraticCurveTo($cpx, $cpy, $x, $y)
    {
        if (self::DEBUG) echo __FUNCTION__ . "\n";

        // FIXME not accurate
        $this->canvas->quadTo($cpx, $cpy, $x, $y);
    }

    public function bezierCurveTo($cp1x, $cp1y, $cp2x, $cp2y, $x, $y)
    {
        if (self::DEBUG) echo __FUNCTION__ . "\n";
        $this->canvas->curveTo($cp1x, $cp1y, $cp2x, $cp2y, $x, $y);
    }

    public function arcTo($x1, $y1, $x2, $y2, $radius)
    {
        if (self::DEBUG) echo __FUNCTION__ . "\n";
    }

    public function arc($x, $y, $radius, $startAngle, $endAngle, $anticlockwise = false)
    {
        if (self::DEBUG) echo __FUNCTION__ . "\n";
        $this->canvas->ellipse($x, $y, $radius, $radius, 0, 8, $startAngle, $endAngle, false, false, false, true);
    }

    public function circle($x, $y, $radius)
    {
        if (self::DEBUG) echo __FUNCTION__ . "\n";
        $this->canvas->ellipse($x, $y, $radius, $radius, 0, 8, 0, 360, true, false, false, false);
    }

    public function ellipse($x, $y, $radiusX, $radiusY, $rotation, $startAngle, $endAngle, $anticlockwise)
    {
        if (self::DEBUG) echo __FUNCTION__ . "\n";
        $this->canvas->ellipse($x, $y, $radiusX, $radiusY, 0, 8, 0, 360, false, false, false, false);
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

        $rx = min($rx, $w / 2);
        $rx = min($rx, $h / 2);

        /* Define a path for a rectangle with corners rounded by a given radius.
         * Start from the lower left corner and proceed counterclockwise.
         */
        $this->moveTo($x + $rx, $y);

        /* Start of the arc segment in the lower right corner */
        $this->lineTo($x + $w - $rx, $y);

        /* Arc segment in the lower right corner */
        $this->arc($x + $w - $rx, $y + $rx, $rx, 270, 360);

        /* Start of the arc segment in the upper right corner */
        $this->lineTo($x + $w, $y + $h - $rx );

        /* Arc segment in the upper right corner */
        $this->arc($x + $w - $rx, $y + $h - $rx, $rx, 0, 90);

        /* Start of the arc segment in the upper left corner */
        $this->lineTo($x + $rx, $y + $h);

        /* Arc segment in the upper left corner */
        $this->arc($x + $rx, $y + $h - $rx, $rx, 90, 180);

        /* Start of the arc segment in the lower left corner */
        $this->lineTo($x , $y + $rx);

        /* Arc segment in the lower left corner */
        $this->arc($x + $rx, $y + $rx, $rx, 180, 270);
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
        $this->canvas->endPath();
    }

    public function measureText($text)
    {
        if (self::DEBUG) echo __FUNCTION__ . "\n";
        $style = $this->getStyle();
        $this->setFont($style->fontFamily, $style->fontStyle, $style->fontWeight);

        return $this->canvas->getTextWidth($this->getStyle()->fontSize, $text);
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
            $canvas->setStrokeColor(array((float)$stroke[0]/255, (float)$stroke[1]/255, (float)$stroke[2]/255), true);
        }

        if (is_array($style->fill) && $fill = $style->fill) {
            $canvas->setColor(array((float)$fill[0]/255, (float)$fill[1]/255, (float)$fill[2]/255), true);
        }

        if ($fillRule = strtolower($style->fillRule)) {
            $canvas->setFillRule($fillRule);
        }

        $opacity = $style->opacity;
        if ($opacity !== null && $opacity < 1.0) {
            $canvas->setLineTransparency("Normal", $opacity);
            $canvas->currentLineTransparency = null;

            $canvas->setFillTransparency("Normal", $opacity);
            $canvas->currentFillTransparency = null;
        }
        else {
            $fillOpacity = $style->fillOpacity;
            if ($fillOpacity !== null && $fillOpacity < 1.0) {
                $canvas->setFillTransparency("Normal", $fillOpacity);
                $canvas->currentFillTransparency = null;
            }

            $strokeOpacity = $style->strokeOpacity;
            if ($strokeOpacity !== null && $strokeOpacity < 1.0) {
                $canvas->setLineTransparency("Normal", $strokeOpacity);
                $canvas->currentLineTransparency = null;
            }
        }

        $dashArray = null;
        if ($style->strokeDasharray) {
            $dashArray = preg_split('/\s*,\s*/', $style->strokeDasharray);
        }

        $canvas->setLineStyle(
            $style->strokeWidth,
            $style->strokeLinecap,
            $style->strokeLinejoin,
            $dashArray
        );

        $this->setFont($style->fontFamily, $style->fontStyle, $style->fontWeight);
    }

    public function setFont($family, $style, $weight)
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

        $styleMap = array(
            'Helvetica' => array(
                'b'  => 'Helvetica-Bold',
                'i'  => 'Helvetica-Oblique',
                'bi' => 'Helvetica-BoldOblique',
            ),
            'Courier' => array(
                'b'  => 'Courier-Bold',
                'i'  => 'Courier-Oblique',
                'bi' => 'Courier-BoldOblique',
            ),
            'Times' => array(
                ''   => 'Times-Roman',
                'b'  => 'Times-Bold',
                'i'  => 'Times-Italic',
                'bi' => 'Times-BoldItalic',
            ),
        );

        $family = strtolower($family);
        $style  = strtolower($style);
        $weight = strtolower($weight);

        if (isset($map[$family])) {
            $family = $map[$family];
        }

        if (isset($styleMap[$family])) {
            $key = "";

            if ($weight === "bold" || $weight === "bolder" || (is_numeric($weight) && $weight >= 600)) {
                $key .= "b";
            }

            if ($style === "italic" || $style === "oblique") {
                $key .= "i";
            }

            if (isset($styleMap[$family][$key])) {
                $family = $styleMap[$family][$key];
            }
        }

        $this->canvas->selectFont("$family.afm");
    }
}
