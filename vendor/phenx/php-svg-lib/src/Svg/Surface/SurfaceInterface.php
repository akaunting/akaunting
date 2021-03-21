<?php
/**
 * @package php-svg-lib
 * @link    http://github.com/PhenX/php-svg-lib
 * @author  Fabien M�nager <fabien.menager@gmail.com>
 * @license GNU LGPLv3+ http://www.gnu.org/copyleft/lesser.html
 */

namespace Svg\Surface;

use Svg\Style;

/**
 * Interface Surface, like CanvasRenderingContext2D
 *
 * @package Svg
 */
interface SurfaceInterface
{
    public function save();

    public function restore();

    // transformations (default transform is the identity matrix)
    public function scale($x, $y);

    public function rotate($angle);

    public function translate($x, $y);

    public function transform($a, $b, $c, $d, $e, $f);

    // path ends
    public function beginPath();

    public function closePath();

    public function fill();

    public function stroke();

    public function endPath();

    public function fillStroke();

    public function clip();

    // text (see also the CanvasDrawingStyles interface)
    public function fillText($text, $x, $y, $maxWidth = null);

    public function strokeText($text, $x, $y, $maxWidth = null);

    public function measureText($text);

    // drawing images
    public function drawImage($image, $sx, $sy, $sw = null, $sh = null, $dx = null, $dy = null, $dw = null, $dh = null);

    // paths
    public function lineTo($x, $y);

    public function moveTo($x, $y);

    public function quadraticCurveTo($cpx, $cpy, $x, $y);

    public function bezierCurveTo($cp1x, $cp1y, $cp2x, $cp2y, $x, $y);

    public function arcTo($x1, $y1, $x2, $y2, $radius);

    public function circle($x, $y, $radius);

    public function arc($x, $y, $radius, $startAngle, $endAngle, $anticlockwise = false);

    public function ellipse($x, $y, $radiusX, $radiusY, $rotation, $startAngle, $endAngle, $anticlockwise);

    // Rectangle
    public function rect($x, $y, $w, $h, $rx = 0, $ry = 0);

    public function fillRect($x, $y, $w, $h);

    public function strokeRect($x, $y, $w, $h);

    public function setStyle(Style $style);

    /**
     * @return Style
     */
    public function getStyle();

    public function setFont($family, $style, $weight);
}