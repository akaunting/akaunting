# PHP Font Lib

[![Build Status](https://travis-ci.org/PhenX/php-font-lib.svg?branch=master)](https://travis-ci.org/PhenX/php-font-lib)


This library can be used to:
 * Read TrueType, OpenType (with TrueType glyphs), WOFF font files
 * Extract basic info (name, style, etc)
 * Extract advanced info (horizontal metrics, glyph names, glyph shapes, etc)
 * Make an Adobe Font Metrics (AFM) file from a font

You can find a demo GUI [here](http://pxd.me/php-font-lib/www/font_explorer.html).

This project was initiated by the need to read font files in the [DOMPDF project](https://github.com/dompdf/dompdf).

Usage Example
-------------

```
$font = \FontLib\Font::load('../../fontfile.ttf');
$font->parse();  // for getFontWeight() to work this call must be done first!
echo $font->getFontName() .'<br>';
echo $font->getFontSubfamily() .'<br>';
echo $font->getFontSubfamilyID() .'<br>';
echo $font->getFontFullName() .'<br>';
echo $font->getFontVersion() .'<br>';
echo $font->getFontWeight() .'<br>';
echo $font->getFontPostscriptName() .'<br>';
```
