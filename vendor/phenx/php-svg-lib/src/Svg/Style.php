<?php
/**
 * @package php-svg-lib
 * @link    http://github.com/PhenX/php-svg-lib
 * @author  Fabien M�nager <fabien.menager@gmail.com>
 * @license GNU LGPLv3+ http://www.gnu.org/copyleft/lesser.html
 */

namespace Svg;

use Svg\Tag\AbstractTag;

class Style
{
    const TYPE_COLOR = 1;
    const TYPE_LENGTH = 2;
    const TYPE_NAME = 3;
    const TYPE_ANGLE = 4;
    const TYPE_NUMBER = 5;

    public $color;
    public $opacity;
    public $display;

    public $fill;
    public $fillOpacity;
    public $fillRule;

    public $stroke;
    public $strokeOpacity;
    public $strokeLinecap;
    public $strokeLinejoin;
    public $strokeMiterlimit;
    public $strokeWidth;
    public $strokeDasharray;
    public $strokeDashoffset;

    public $fontFamily = 'serif';
    public $fontSize = 12;
    public $fontWeight = 'normal';
    public $fontStyle = 'normal';
    public $textAnchor = 'start';

    protected function getStyleMap()
    {
        return array(
            'color'             => array('color', self::TYPE_COLOR),
            'opacity'           => array('opacity', self::TYPE_NUMBER),
            'display'           => array('display', self::TYPE_NAME),

            'fill'              => array('fill', self::TYPE_COLOR),
            'fill-opacity'      => array('fillOpacity', self::TYPE_NUMBER),
            'fill-rule'         => array('fillRule', self::TYPE_NAME),

            'stroke'            => array('stroke', self::TYPE_COLOR),
            'stroke-dasharray'  => array('strokeDasharray', self::TYPE_NAME),
            'stroke-dashoffset' => array('strokeDashoffset', self::TYPE_NUMBER),
            'stroke-linecap'    => array('strokeLinecap', self::TYPE_NAME),
            'stroke-linejoin'   => array('strokeLinejoin', self::TYPE_NAME),
            'stroke-miterlimit' => array('strokeMiterlimit', self::TYPE_NUMBER),
            'stroke-opacity'    => array('strokeOpacity', self::TYPE_NUMBER),
            'stroke-width'      => array('strokeWidth', self::TYPE_NUMBER),

            'font-family'       => array('fontFamily', self::TYPE_NAME),
            'font-size'         => array('fontSize', self::TYPE_NUMBER),
            'font-weight'       => array('fontWeight', self::TYPE_NAME),
            'font-style'        => array('fontStyle', self::TYPE_NAME),
            'text-anchor'       => array('textAnchor', self::TYPE_NAME),
        );
    }

    /**
     * @param $attributes
     *
     * @return Style
     */
    public function fromAttributes($attributes)
    {
        $this->fillStyles($attributes);

        if (isset($attributes["style"])) {
            $styles = self::parseCssStyle($attributes["style"]);
            $this->fillStyles($styles);
        }
    }

    public function inherit(AbstractTag $tag) {
        $group = $tag->getParentGroup();
        if ($group) {
            $parent_style = $group->getStyle();

            foreach ($parent_style as $_key => $_value) {
                if ($_value !== null) {
                    $this->$_key = $_value;
                }
            }
        }
    }

    public function fromStyleSheets(AbstractTag $tag, $attributes) {
        $class = isset($attributes["class"]) ? preg_split('/\s+/', trim($attributes["class"])) : null;

        $stylesheets = $tag->getDocument()->getStyleSheets();

        $styles = array();

        foreach ($stylesheets as $_sc) {

            /** @var \Sabberworm\CSS\RuleSet\DeclarationBlock $_decl */
            foreach ($_sc->getAllDeclarationBlocks() as $_decl) {

                /** @var \Sabberworm\CSS\Property\Selector $_selector */
                foreach ($_decl->getSelectors() as $_selector) {
                    $_selector = $_selector->getSelector();

                    // Match class name
                    if ($class !== null) {
                        foreach ($class as $_class) {
                            if ($_selector === ".$_class") {
                                /** @var \Sabberworm\CSS\Rule\Rule $_rule */
                                foreach ($_decl->getRules() as $_rule) {
                                    $styles[$_rule->getRule()] = $_rule->getValue() . "";
                                }

                                break 2;
                            }
                        }
                    }

                    // Match tag name
                    if ($_selector === $tag->tagName) {
                        /** @var \Sabberworm\CSS\Rule\Rule $_rule */
                        foreach ($_decl->getRules() as $_rule) {
                            $styles[$_rule->getRule()] = $_rule->getValue() . "";
                        }

                        break;
                    }
                }
            }
        }

        $this->fillStyles($styles);
    }

    protected function fillStyles($styles)
    {
        foreach ($this->getStyleMap() as $from => $spec) {
            if (isset($styles[$from])) {
                list($to, $type) = $spec;
                $value = null;
                switch ($type) {
                    case self::TYPE_COLOR:
                        $value = self::parseColor($styles[$from]);
                        break;

                    case self::TYPE_NUMBER:
                        $value = ($styles[$from] === null) ? null : (float)$styles[$from];
                        break;

                    default:
                        $value = $styles[$from];
                }

                if ($value !== null) {
                    $this->$to = $value;
                }
            }
        }
    }

    static function parseColor($color)
    {
        $color = strtolower(trim($color));

        $parts = preg_split('/[^,]\s+/', $color, 2);

        if (count($parts) == 2) {
            $color = $parts[1];
        }
        else {
            $color = $parts[0];
        }

        if ($color === "none") {
            return "none";
        }

        // SVG color name
        if (isset(self::$colorNames[$color])) {
            return self::parseHexColor(self::$colorNames[$color]);
        }

        // Hex color
        if ($color[0] === "#") {
            return self::parseHexColor($color);
        }

        // RGB color
        if (strpos($color, "rgb") !== false) {
            return self::getTriplet($color);
        }

        // RGB color
        if (strpos($color, "hsl") !== false) {
            $triplet = self::getTriplet($color, true);

            if ($triplet == null) {
                return null;
            }

            list($h, $s, $l) = $triplet;

            $r = $l;
            $g = $l;
            $b = $l;
            $v = ($l <= 0.5) ? ($l * (1.0 + $s)) : ($l + $s - $l * $s);
            if ($v > 0) {
                $m = $l + $l - $v;
                $sv = ($v - $m) / $v;
                $h *= 6.0;
                $sextant = floor($h);
                $fract = $h - $sextant;
                $vsf = $v * $sv * $fract;
                $mid1 = $m + $vsf;
                $mid2 = $v - $vsf;

                switch ($sextant) {
                    case 0:
                        $r = $v;
                        $g = $mid1;
                        $b = $m;
                        break;
                    case 1:
                        $r = $mid2;
                        $g = $v;
                        $b = $m;
                        break;
                    case 2:
                        $r = $m;
                        $g = $v;
                        $b = $mid1;
                        break;
                    case 3:
                        $r = $m;
                        $g = $mid2;
                        $b = $v;
                        break;
                    case 4:
                        $r = $mid1;
                        $g = $m;
                        $b = $v;
                        break;
                    case 5:
                        $r = $v;
                        $g = $m;
                        $b = $mid2;
                        break;
                }
            }

            return array(
                $r * 255.0,
                $g * 255.0,
                $b * 255.0,
            );
        }

        // Gradient
        if (strpos($color, "url(#") !== false) {
            $i = strpos($color, "(");
            $j = strpos($color, ")");

            // Bad url format
            if ($i === false || $j === false) {
                return null;
            }

            return trim(substr($color, $i + 1, $j - $i - 1));
        }

        return null;
    }

    static function getTriplet($color, $percent = false) {
        $i = strpos($color, "(");
        $j = strpos($color, ")");

        // Bad color value
        if ($i === false || $j === false) {
            return null;
        }

        $triplet = preg_split("/\\s*,\\s*/", trim(substr($color, $i + 1, $j - $i - 1)));

        if (count($triplet) != 3) {
            return null;
        }

        foreach (array_keys($triplet) as $c) {
            $triplet[$c] = trim($triplet[$c]);

            if ($percent) {
                if ($triplet[$c][strlen($triplet[$c]) - 1] === "%") {
                    $triplet[$c] = floatval($triplet[$c]) / 100;
                }
                else {
                    $triplet[$c] = $triplet[$c] / 255;
                }
            }
            else {
                if ($triplet[$c][strlen($triplet[$c]) - 1] === "%") {
                    $triplet[$c] = round(floatval($triplet[$c]) * 2.55);
                }
            }
        }

        return $triplet;
    }

    static function parseHexColor($hex)
    {
        $c = array(0, 0, 0);

        // #FFFFFF
        if (isset($hex[6])) {
            $c[0] = hexdec(substr($hex, 1, 2));
            $c[1] = hexdec(substr($hex, 3, 2));
            $c[2] = hexdec(substr($hex, 5, 2));
        } else {
            $c[0] = hexdec($hex[1] . $hex[1]);
            $c[1] = hexdec($hex[2] . $hex[2]);
            $c[2] = hexdec($hex[3] . $hex[3]);
        }

        return $c;
    }

    /**
     * Simple CSS parser
     *
     * @param $style
     *
     * @return array
     */
    static function parseCssStyle($style)
    {
        $matches = array();
        preg_match_all("/([a-z-]+)\\s*:\\s*([^;$]+)/si", $style, $matches, PREG_SET_ORDER);

        $styles = array();
        foreach ($matches as $match) {
            $styles[$match[1]] = $match[2];
        }

        return $styles;
    }

    /**
     * Convert a size to a float
     *
     * @param string $size          SVG size
     * @param float  $dpi           DPI
     * @param float  $referenceSize Reference size
     *
     * @return float|null
     */
    static function convertSize($size, $referenceSize = 11.0, $dpi = 96.0) {
        $size = trim(strtolower($size));

        if (is_numeric($size)) {
            return $size;
        }

        if ($pos = strpos($size, "px")) {
            return floatval(substr($size, 0, $pos));
        }

        if ($pos = strpos($size, "pt")) {
            return floatval(substr($size, 0, $pos));
        }

        if ($pos = strpos($size, "cm")) {
            return floatval(substr($size, 0, $pos)) * $dpi;
        }

        if ($pos = strpos($size, "%")) {
            return $referenceSize * substr($size, 0, $pos) / 100;
        }

        if ($pos = strpos($size, "em")) {
            return $referenceSize * substr($size, 0, $pos);
        }

        // TODO cm, mm, pc, in, etc

        return null;
    }

    static $colorNames = array(
        'antiquewhite'         => '#FAEBD7',
        'aqua'                 => '#00FFFF',
        'aquamarine'           => '#7FFFD4',
        'beige'                => '#F5F5DC',
        'black'                => '#000000',
        'blue'                 => '#0000FF',
        'brown'                => '#A52A2A',
        'cadetblue'            => '#5F9EA0',
        'chocolate'            => '#D2691E',
        'cornflowerblue'       => '#6495ED',
        'crimson'              => '#DC143C',
        'darkblue'             => '#00008B',
        'darkgoldenrod'        => '#B8860B',
        'darkgreen'            => '#006400',
        'darkmagenta'          => '#8B008B',
        'darkorange'           => '#FF8C00',
        'darkred'              => '#8B0000',
        'darkseagreen'         => '#8FBC8F',
        'darkslategray'        => '#2F4F4F',
        'darkviolet'           => '#9400D3',
        'deepskyblue'          => '#00BFFF',
        'dodgerblue'           => '#1E90FF',
        'firebrick'            => '#B22222',
        'forestgreen'          => '#228B22',
        'fuchsia'              => '#FF00FF',
        'gainsboro'            => '#DCDCDC',
        'gold'                 => '#FFD700',
        'gray'                 => '#808080',
        'green'                => '#008000',
        'greenyellow'          => '#ADFF2F',
        'hotpink'              => '#FF69B4',
        'indigo'               => '#4B0082',
        'khaki'                => '#F0E68C',
        'lavenderblush'        => '#FFF0F5',
        'lemonchiffon'         => '#FFFACD',
        'lightcoral'           => '#F08080',
        'lightgoldenrodyellow' => '#FAFAD2',
        'lightgreen'           => '#90EE90',
        'lightsalmon'          => '#FFA07A',
        'lightskyblue'         => '#87CEFA',
        'lightslategray'       => '#778899',
        'lightyellow'          => '#FFFFE0',
        'lime'                 => '#00FF00',
        'limegreen'            => '#32CD32',
        'magenta'              => '#FF00FF',
        'maroon'               => '#800000',
        'mediumaquamarine'     => '#66CDAA',
        'mediumorchid'         => '#BA55D3',
        'mediumseagreen'       => '#3CB371',
        'mediumspringgreen'    => '#00FA9A',
        'mediumvioletred'      => '#C71585',
        'midnightblue'         => '#191970',
        'mintcream'            => '#F5FFFA',
        'moccasin'             => '#FFE4B5',
        'navy'                 => '#000080',
        'olive'                => '#808000',
        'orange'               => '#FFA500',
        'orchid'               => '#DA70D6',
        'palegreen'            => '#98FB98',
        'palevioletred'        => '#D87093',
        'peachpuff'            => '#FFDAB9',
        'pink'                 => '#FFC0CB',
        'powderblue'           => '#B0E0E6',
        'purple'               => '#800080',
        'red'                  => '#FF0000',
        'royalblue'            => '#4169E1',
        'salmon'               => '#FA8072',
        'seagreen'             => '#2E8B57',
        'sienna'               => '#A0522D',
        'silver'               => '#C0C0C0',
        'skyblue'              => '#87CEEB',
        'slategray'            => '#708090',
        'springgreen'          => '#00FF7F',
        'steelblue'            => '#4682B4',
        'tan'                  => '#D2B48C',
        'teal'                 => '#008080',
        'thistle'              => '#D8BFD8',
        'turquoise'            => '#40E0D0',
        'violetred'            => '#D02090',
        'white'                => '#FFFFFF',
        'yellow'               => '#FFFF00',
        'aliceblue'            => '#f0f8ff',
        'azure'                => '#f0ffff',
        'bisque'               => '#ffe4c4',
        'blanchedalmond'       => '#ffebcd',
        'blueviolet'           => '#8a2be2',
        'burlywood'            => '#deb887',
        'chartreuse'           => '#7fff00',
        'coral'                => '#ff7f50',
        'cornsilk'             => '#fff8dc',
        'cyan'                 => '#00ffff',
        'darkcyan'             => '#008b8b',
        'darkgray'             => '#a9a9a9',
        'darkgrey'             => '#a9a9a9',
        'darkkhaki'            => '#bdb76b',
        'darkolivegreen'       => '#556b2f',
        'darkorchid'           => '#9932cc',
        'darksalmon'           => '#e9967a',
        'darkslateblue'        => '#483d8b',
        'darkslategrey'        => '#2f4f4f',
        'darkturquoise'        => '#00ced1',
        'deeppink'             => '#ff1493',
        'dimgray'              => '#696969',
        'dimgrey'              => '#696969',
        'floralwhite'          => '#fffaf0',
        'ghostwhite'           => '#f8f8ff',
        'goldenrod'            => '#daa520',
        'grey'                 => '#808080',
        'honeydew'             => '#f0fff0',
        'indianred'            => '#cd5c5c',
        'ivory'                => '#fffff0',
        'lavender'             => '#e6e6fa',
        'lawngreen'            => '#7cfc00',
        'lightblue'            => '#add8e6',
        'lightcyan'            => '#e0ffff',
        'lightgray'            => '#d3d3d3',
        'lightgrey'            => '#d3d3d3',
        'lightpink'            => '#ffb6c1',
        'lightseagreen'        => '#20b2aa',
        'lightslategrey'       => '#778899',
        'lightsteelblue'       => '#b0c4de',
        'linen'                => '#faf0e6',
        'mediumblue'           => '#0000cd',
        'mediumpurple'         => '#9370db',
        'mediumslateblue'      => '#7b68ee',
        'mediumturquoise'      => '#48d1cc',
        'mistyrose'            => '#ffe4e1',
        'navajowhite'          => '#ffdead',
        'oldlace'              => '#fdf5e6',
        'olivedrab'            => '#6b8e23',
        'orangered'            => '#ff4500',
        'palegoldenrod'        => '#eee8aa',
        'paleturquoise'        => '#afeeee',
        'papayawhip'           => '#ffefd5',
        'peru'                 => '#cd853f',
        'plum'                 => '#dda0dd',
        'rosybrown'            => '#bc8f8f',
        'saddlebrown'          => '#8b4513',
        'sandybrown'           => '#f4a460',
        'seashell'             => '#fff5ee',
        'slateblue'            => '#6a5acd',
        'slategrey'            => '#708090',
        'snow'                 => '#fffafa',
        'tomato'               => '#ff6347',
        'violet'               => '#ee82ee',
        'wheat'                => '#f5deb3',
        'whitesmoke'           => '#f5f5f5',
        'yellowgreen'          => '#9acd32',
    );
}
