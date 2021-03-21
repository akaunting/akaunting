<?php
/**
 * @package php-svg-lib
 * @link    http://github.com/PhenX/php-svg-lib
 * @author  Fabien Ménager <fabien.menager@gmail.com>
 * @license GNU LGPLv3+ http://www.gnu.org/copyleft/lesser.html
 */

namespace Svg\Tag;

use Svg\Surface\SurfaceInterface;

class Path extends Shape
{
    static $commandLengths = array(
        'm' => 2,
        'l' => 2,
        'h' => 1,
        'v' => 1,
        'c' => 6,
        's' => 4,
        'q' => 4,
        't' => 2,
        'a' => 7,
    );

    static $repeatedCommands = array(
        'm' => 'l',
        'M' => 'L',
    );

    public function start($attributes)
    {
        if (!isset($attributes['d'])) {
            $this->hasShape = false;

            return;
        }

        $commands = array();
        preg_match_all('/([MZLHVCSQTAmzlhvcsqta])([eE ,\-.\d]+)*/', $attributes['d'], $commands, PREG_SET_ORDER);

        $path = array();
        foreach ($commands as $c) {
            if (count($c) == 3) {
                $arguments = array();
                preg_match_all('/([-+]?((\d+\.\d+)|((\d+)|(\.\d+)))(?:e[-+]?\d+)?)/i', $c[2], $arguments, PREG_PATTERN_ORDER);
                $item = $arguments[0];
                $commandLower = strtolower($c[1]);

                if (
                    isset(self::$commandLengths[$commandLower]) &&
                    ($commandLength = self::$commandLengths[$commandLower]) &&
                    count($item) > $commandLength
                ) {
                    $repeatedCommand = isset(self::$repeatedCommands[$c[1]]) ? self::$repeatedCommands[$c[1]] : $c[1];
                    $command = $c[1];

                    for ($k = 0, $klen = count($item); $k < $klen; $k += $commandLength) {
                        $_item = array_slice($item, $k, $k + $commandLength);
                        array_unshift($_item, $command);
                        $path[] = $_item;

                        $command = $repeatedCommand;
                    }
                } else {
                    array_unshift($item, $c[1]);
                    $path[] = $item;
                }

            } else {
                $item = array($c[1]);

                $path[] = $item;
            }
        }

        $surface = $this->document->getSurface();

        // From https://github.com/kangax/fabric.js/blob/master/src/shapes/path.class.js
        $current = null; // current instruction
        $previous = null;
        $subpathStartX = 0;
        $subpathStartY = 0;
        $x = 0; // current x
        $y = 0; // current y
        $controlX = 0; // current control point x
        $controlY = 0; // current control point y
        $tempX = null;
        $tempY = null;
        $tempControlX = null;
        $tempControlY = null;
        $l = 0; //-((this.width / 2) + $this.pathOffset.x),
        $t = 0; //-((this.height / 2) + $this.pathOffset.y),
        $methodName = null;

        foreach ($path as $current) {
            switch ($current[0]) { // first letter
                case 'l': // lineto, relative
                    $x += $current[1];
                    $y += $current[2];
                    $surface->lineTo($x + $l, $y + $t);
                    break;

                case 'L': // lineto, absolute
                    $x = $current[1];
                    $y = $current[2];
                    $surface->lineTo($x + $l, $y + $t);
                    break;

                case 'h': // horizontal lineto, relative
                    $x += $current[1];
                    $surface->lineTo($x + $l, $y + $t);
                    break;

                case 'H': // horizontal lineto, absolute
                    $x = $current[1];
                    $surface->lineTo($x + $l, $y + $t);
                    break;

                case 'v': // vertical lineto, relative
                    $y += $current[1];
                    $surface->lineTo($x + $l, $y + $t);
                    break;

                case 'V': // verical lineto, absolute
                    $y = $current[1];
                    $surface->lineTo($x + $l, $y + $t);
                    break;

                case 'm': // moveTo, relative
                    $x += $current[1];
                    $y += $current[2];
                    $subpathStartX = $x;
                    $subpathStartY = $y;
                    $surface->moveTo($x + $l, $y + $t);
                    break;

                case 'M': // moveTo, absolute
                    $x = $current[1];
                    $y = $current[2];
                    $subpathStartX = $x;
                    $subpathStartY = $y;
                    $surface->moveTo($x + $l, $y + $t);
                    break;

                case 'c': // bezierCurveTo, relative
                    $tempX = $x + $current[5];
                    $tempY = $y + $current[6];
                    $controlX = $x + $current[3];
                    $controlY = $y + $current[4];
                    $surface->bezierCurveTo(
                        $x + $current[1] + $l, // x1
                        $y + $current[2] + $t, // y1
                        $controlX + $l, // x2
                        $controlY + $t, // y2
                        $tempX + $l,
                        $tempY + $t
                    );
                    $x = $tempX;
                    $y = $tempY;
                    break;

                case 'C': // bezierCurveTo, absolute
                    $x = $current[5];
                    $y = $current[6];
                    $controlX = $current[3];
                    $controlY = $current[4];
                    $surface->bezierCurveTo(
                        $current[1] + $l,
                        $current[2] + $t,
                        $controlX + $l,
                        $controlY + $t,
                        $x + $l,
                        $y + $t
                    );
                    break;

                case 's': // shorthand cubic bezierCurveTo, relative

                    // transform to absolute x,y
                    $tempX = $x + $current[3];
                    $tempY = $y + $current[4];

                    if (!preg_match('/[CcSs]/', $previous[0])) {
                        // If there is no previous command or if the previous command was not a C, c, S, or s,
                        // the control point is coincident with the current point
                        $controlX = $x;
                        $controlY = $y;
                    } else {
                        // calculate reflection of previous control points
                        $controlX = 2 * $x - $controlX;
                        $controlY = 2 * $y - $controlY;
                    }

                    $surface->bezierCurveTo(
                        $controlX + $l,
                        $controlY + $t,
                        $x + $current[1] + $l,
                        $y + $current[2] + $t,
                        $tempX + $l,
                        $tempY + $t
                    );
                    // set control point to 2nd one of this command
                    // "... the first control point is assumed to be
                    // the reflection of the second control point on
                    // the previous command relative to the current point."
                    $controlX = $x + $current[1];
                    $controlY = $y + $current[2];

                    $x = $tempX;
                    $y = $tempY;
                    break;

                case 'S': // shorthand cubic bezierCurveTo, absolute
                    $tempX = $current[3];
                    $tempY = $current[4];

                    if (!preg_match('/[CcSs]/', $previous[0])) {
                        // If there is no previous command or if the previous command was not a C, c, S, or s,
                        // the control point is coincident with the current point
                        $controlX = $x;
                        $controlY = $y;
                    } else {
                        // calculate reflection of previous control points
                        $controlX = 2 * $x - $controlX;
                        $controlY = 2 * $y - $controlY;
                    }

                    $surface->bezierCurveTo(
                        $controlX + $l,
                        $controlY + $t,
                        $current[1] + $l,
                        $current[2] + $t,
                        $tempX + $l,
                        $tempY + $t
                    );
                    $x = $tempX;
                    $y = $tempY;

                    // set control point to 2nd one of this command
                    // "... the first control point is assumed to be
                    // the reflection of the second control point on
                    // the previous command relative to the current point."
                    $controlX = $current[1];
                    $controlY = $current[2];

                    break;

                case 'q': // quadraticCurveTo, relative
                    // transform to absolute x,y
                    $tempX = $x + $current[3];
                    $tempY = $y + $current[4];

                    $controlX = $x + $current[1];
                    $controlY = $y + $current[2];

                    $surface->quadraticCurveTo(
                        $controlX + $l,
                        $controlY + $t,
                        $tempX + $l,
                        $tempY + $t
                    );
                    $x = $tempX;
                    $y = $tempY;
                    break;

                case 'Q': // quadraticCurveTo, absolute
                    $tempX = $current[3];
                    $tempY = $current[4];

                    $surface->quadraticCurveTo(
                        $current[1] + $l,
                        $current[2] + $t,
                        $tempX + $l,
                        $tempY + $t
                    );
                    $x = $tempX;
                    $y = $tempY;
                    $controlX = $current[1];
                    $controlY = $current[2];
                    break;

                case 't': // shorthand quadraticCurveTo, relative

                    // transform to absolute x,y
                    $tempX = $x + $current[1];
                    $tempY = $y + $current[2];

                    if (preg_match("/[QqTt]/", $previous[0])) {
                        // If there is no previous command or if the previous command was not a Q, q, T or t,
                        // assume the control point is coincident with the current point
                        $controlX = $x;
                        $controlY = $y;
                    } else {
                        if ($previous[0] === 't') {
                            // calculate reflection of previous control points for t
                            $controlX = 2 * $x - $tempControlX;
                            $controlY = 2 * $y - $tempControlY;
                        } else {
                            if ($previous[0] === 'q') {
                                // calculate reflection of previous control points for q
                                $controlX = 2 * $x - $controlX;
                                $controlY = 2 * $y - $controlY;
                            }
                        }
                    }

                    $tempControlX = $controlX;
                    $tempControlY = $controlY;

                    $surface->quadraticCurveTo(
                        $controlX + $l,
                        $controlY + $t,
                        $tempX + $l,
                        $tempY + $t
                    );
                    $x = $tempX;
                    $y = $tempY;
                    $controlX = $x + $current[1];
                    $controlY = $y + $current[2];
                    break;

                case 'T':
                    $tempX = $current[1];
                    $tempY = $current[2];

                    // calculate reflection of previous control points
                    $controlX = 2 * $x - $controlX;
                    $controlY = 2 * $y - $controlY;
                    $surface->quadraticCurveTo(
                        $controlX + $l,
                        $controlY + $t,
                        $tempX + $l,
                        $tempY + $t
                    );
                    $x = $tempX;
                    $y = $tempY;
                    break;

                case 'a':
                    // TODO: optimize this
                    $this->drawArc(
                        $surface,
                        $x + $l,
                        $y + $t,
                        array(
                            $current[1],
                            $current[2],
                            $current[3],
                            $current[4],
                            $current[5],
                            $current[6] + $x + $l,
                            $current[7] + $y + $t
                        )
                    );
                    $x += $current[6];
                    $y += $current[7];
                    break;

                case 'A':
                    // TODO: optimize this
                    $this->drawArc(
                        $surface,
                        $x + $l,
                        $y + $t,
                        array(
                            $current[1],
                            $current[2],
                            $current[3],
                            $current[4],
                            $current[5],
                            $current[6] + $l,
                            $current[7] + $t
                        )
                    );
                    $x = $current[6];
                    $y = $current[7];
                    break;

                case 'z':
                case 'Z':
                    $x = $subpathStartX;
                    $y = $subpathStartY;
                    $surface->closePath();
                    break;
            }
            $previous = $current;
        }
    }

    function drawArc(SurfaceInterface $surface, $fx, $fy, $coords)
    {
        $rx = $coords[0];
        $ry = $coords[1];
        $rot = $coords[2];
        $large = $coords[3];
        $sweep = $coords[4];
        $tx = $coords[5];
        $ty = $coords[6];
        $segs = array(
            array(),
            array(),
            array(),
            array(),
        );

        $segsNorm = $this->arcToSegments($tx - $fx, $ty - $fy, $rx, $ry, $large, $sweep, $rot);

        for ($i = 0, $len = count($segsNorm); $i < $len; $i++) {
            $segs[$i][0] = $segsNorm[$i][0] + $fx;
            $segs[$i][1] = $segsNorm[$i][1] + $fy;
            $segs[$i][2] = $segsNorm[$i][2] + $fx;
            $segs[$i][3] = $segsNorm[$i][3] + $fy;
            $segs[$i][4] = $segsNorm[$i][4] + $fx;
            $segs[$i][5] = $segsNorm[$i][5] + $fy;

            call_user_func_array(array($surface, "bezierCurveTo"), $segs[$i]);
        }
    }

    function arcToSegments($toX, $toY, $rx, $ry, $large, $sweep, $rotateX)
    {
        $th = $rotateX * M_PI / 180;
        $sinTh = sin($th);
        $cosTh = cos($th);
        $fromX = 0;
        $fromY = 0;

        $rx = abs($rx);
        $ry = abs($ry);

        $px = -$cosTh * $toX * 0.5 - $sinTh * $toY * 0.5;
        $py = -$cosTh * $toY * 0.5 + $sinTh * $toX * 0.5;
        $rx2 = $rx * $rx;
        $ry2 = $ry * $ry;
        $py2 = $py * $py;
        $px2 = $px * $px;
        $pl = $rx2 * $ry2 - $rx2 * $py2 - $ry2 * $px2;
        $root = 0;

        if ($pl < 0) {
            $s = sqrt(1 - $pl / ($rx2 * $ry2));
            $rx *= $s;
            $ry *= $s;
        } else {
            $root = ($large == $sweep ? -1.0 : 1.0) * sqrt($pl / ($rx2 * $py2 + $ry2 * $px2));
        }

        $cx = $root * $rx * $py / $ry;
        $cy = -$root * $ry * $px / $rx;
        $cx1 = $cosTh * $cx - $sinTh * $cy + $toX * 0.5;
        $cy1 = $sinTh * $cx + $cosTh * $cy + $toY * 0.5;
        $mTheta = $this->calcVectorAngle(1, 0, ($px - $cx) / $rx, ($py - $cy) / $ry);
        $dtheta = $this->calcVectorAngle(($px - $cx) / $rx, ($py - $cy) / $ry, (-$px - $cx) / $rx, (-$py - $cy) / $ry);

        if ($sweep == 0 && $dtheta > 0) {
            $dtheta -= 2 * M_PI;
        } else {
            if ($sweep == 1 && $dtheta < 0) {
                $dtheta += 2 * M_PI;
            }
        }

        // $Convert $into $cubic $bezier $segments <= 90deg
        $segments = ceil(abs($dtheta / M_PI * 2));
        $result = array();
        $mDelta = $dtheta / $segments;
        $mT = 8 / 3 * sin($mDelta / 4) * sin($mDelta / 4) / sin($mDelta / 2);
        $th3 = $mTheta + $mDelta;

        for ($i = 0; $i < $segments; $i++) {
            $result[$i] = $this->segmentToBezier(
                $mTheta,
                $th3,
                $cosTh,
                $sinTh,
                $rx,
                $ry,
                $cx1,
                $cy1,
                $mT,
                $fromX,
                $fromY
            );
            $fromX = $result[$i][4];
            $fromY = $result[$i][5];
            $mTheta = $th3;
            $th3 += $mDelta;
        }

        return $result;
    }

    function segmentToBezier($th2, $th3, $cosTh, $sinTh, $rx, $ry, $cx1, $cy1, $mT, $fromX, $fromY)
    {
        $costh2 = cos($th2);
        $sinth2 = sin($th2);
        $costh3 = cos($th3);
        $sinth3 = sin($th3);
        $toX = $cosTh * $rx * $costh3 - $sinTh * $ry * $sinth3 + $cx1;
        $toY = $sinTh * $rx * $costh3 + $cosTh * $ry * $sinth3 + $cy1;
        $cp1X = $fromX + $mT * (-$cosTh * $rx * $sinth2 - $sinTh * $ry * $costh2);
        $cp1Y = $fromY + $mT * (-$sinTh * $rx * $sinth2 + $cosTh * $ry * $costh2);
        $cp2X = $toX + $mT * ($cosTh * $rx * $sinth3 + $sinTh * $ry * $costh3);
        $cp2Y = $toY + $mT * ($sinTh * $rx * $sinth3 - $cosTh * $ry * $costh3);

        return array(
            $cp1X,
            $cp1Y,
            $cp2X,
            $cp2Y,
            $toX,
            $toY
        );
    }

    function calcVectorAngle($ux, $uy, $vx, $vy)
    {
        $ta = atan2($uy, $ux);
        $tb = atan2($vy, $vx);
        if ($tb >= $ta) {
            return $tb - $ta;
        } else {
            return 2 * M_PI - ($ta - $tb);
        }
    }
} 