PHP CSS Parser
--------------

[![build status](https://api.travis-ci.org/sabberworm/PHP-CSS-Parser.svg)](https://travis-ci.org/sabberworm/PHP-CSS-Parser) [![HHVM Status](http://hhvm.h4cc.de/badge/sabberworm/php-css-parser.svg)](http://hhvm.h4cc.de/package/sabberworm/php-css-parser)

A Parser for CSS Files written in PHP. Allows extraction of CSS files into a data structure, manipulation of said structure and output as (optimized) CSS.

## Usage

### Installation using composer

Add php-css-parser to your composer.json

```json
{
    "require": {
        "sabberworm/php-css-parser": "*"
    }
}
```

### Extraction

To use the CSS Parser, create a new instance. The constructor takes the following form:

```php
new Sabberworm\CSS\Parser($sText);
```

To read a file, for example, you’d do the following:

```php
$oCssParser = new Sabberworm\CSS\Parser(file_get_contents('somefile.css'));
$oCssDocument = $oCssParser->parse();
```

The resulting CSS document structure can be manipulated prior to being output.

### Options

#### Charset

The charset option is used only if no @charset declaration is found in the CSS file. UTF-8 is the default, so you won’t have to create a settings object at all if you don’t intend to change that.

```php
$oSettings = Sabberworm\CSS\Settings::create()->withDefaultCharset('windows-1252');
new Sabberworm\CSS\Parser($sText, $oSettings);
```

#### Strict parsing

To have the parser choke on invalid rules, supply a thusly configured Sabberworm\CSS\Settings object:

```php
$oCssParser = new Sabberworm\CSS\Parser(file_get_contents('somefile.css'), Sabberworm\CSS\Settings::create()->beStrict());
```

#### Disable multibyte functions

To achieve faster parsing, you can choose to have PHP-CSS-Parser use regular string functions instead of `mb_*` functions. This should work fine in most cases, even for UTF-8 files, as all the multibyte characters are in string literals. Still it’s not recommended to use this with input you have no control over as it’s not thoroughly covered by test cases.

```php
$oSettings = Sabberworm\CSS\Settings::create()->withMultibyteSupport(false);
new Sabberworm\CSS\Parser($sText, $oSettings);
```

### Manipulation

The resulting data structure consists mainly of five basic types: `CSSList`, `RuleSet`, `Rule`, `Selector` and `Value`. There are two additional types used: `Import` and `Charset` which you won’t use often.

#### CSSList

`CSSList` represents a generic CSS container, most likely containing declaration blocks (rule sets with a selector) but it may also contain at-rules, charset declarations, etc. `CSSList` has the following concrete subtypes:

* `Document` – representing the root of a CSS file.
* `MediaQuery` – represents a subsection of a CSSList that only applies to a output device matching the contained media query.

To access the items stored in a `CSSList` – like the document you got back when calling `$oCssParser->parse()` –, use `getContents()`, then iterate over that collection and use instanceof to check whether you’re dealing with another `CSSList`, a `RuleSet`, a `Import` or a `Charset`.

To append a new item (selector, media query, etc.) to an existing `CSSList`, construct it using the constructor for this class and use the `append($oItem)` method.

#### RuleSet

`RuleSet` is a container for individual rules. The most common form of a rule set is one constrained by a selector. The following concrete subtypes exist:

* `AtRuleSet` – for generic at-rules which do not match the ones specifically mentioned like @import, @charset or @media. A common example for this is @font-face.
* `DeclarationBlock` – a RuleSet constrained by a `Selector`; contains an array of selector objects (comma-separated in the CSS) as well as the rules to be applied to the matching elements.

Note: A `CSSList` can contain other `CSSList`s (and `Import`s as well as a `Charset`) while a `RuleSet` can only contain `Rule`s.

If you want to manipulate a `RuleSet`, use the methods `addRule(Rule $oRule)`, `getRules()` and `removeRule($mRule)` (which accepts either a Rule instance or a rule name; optionally suffixed by a dash to remove all related rules).

#### Rule

`Rule`s just have a key (the rule) and a value. These values are all instances of a `Value`.

#### Value

`Value` is an abstract class that only defines the `render` method. The concrete subclasses for atomic value types are:

* `Size` – consists of a numeric `size` value and a unit.
* `Color` – colors can be input in the form #rrggbb, #rgb or schema(val1, val2, …) but are always stored as an array of ('s' => val1, 'c' => val2, 'h' => val3, …) and output in the second form.
* `CSSString` – this is just a wrapper for quoted strings to distinguish them from keywords; always output with double quotes.
* `URL` – URLs in CSS; always output in URL("") notation.

There is another abstract subclass of `Value`, `ValueList`. A `ValueList` represents a lists of `Value`s, separated by some separation character (mostly `,`, whitespace, or `/`). There are two types of `ValueList`s:

* `RuleValueList` – The default type, used to represent all multi-valued rules like `font: bold 12px/3 Helvetica, Verdana, sans-serif;` (where the value would be a whitespace-separated list of the primitive value `bold`, a slash-separated list and a comma-separated list).
* `CSSFunction` – A special kind of value that also contains a function name and where the values are the function’s arguments. Also handles equals-sign-separated argument lists like `filter: alpha(opacity=90);`.

#### Convenience methods

There are a few convenience methods on Document to ease finding, manipulating and deleting rules:

* `getAllDeclarationBlocks()` – does what it says; no matter how deeply nested your selectors are. Aliased as `getAllSelectors()`.
* `getAllRuleSets()` – does what it says; no matter how deeply nested your rule sets are.
* `getAllValues()` – finds all `Value` objects inside `Rule`s.

## To-Do

* More convenience methods [like `selectorsWithElement($sId/Class/TagName)`, `attributesOfType($sType)`, `removeAttributesOfType($sType)`]
* Real multibyte support. Currently only multibyte charsets whose first 255 code points take up only one byte and are identical with ASCII are supported (yes, UTF-8 fits this description).
* Named color support (using `Color` instead of an anonymous string literal)

## Use cases

### Use `Parser` to prepend an id to all selectors

```php
$sMyId = "#my_id";
$oParser = new Sabberworm\CSS\Parser($sText);
$oCss = $oParser->parse();
foreach($oCss->getAllDeclarationBlocks() as $oBlock) {
	foreach($oBlock->getSelectors() as $oSelector) {
		//Loop over all selector parts (the comma-separated strings in a selector) and prepend the id
		$oSelector->setSelector($sMyId.' '.$oSelector->getSelector());
	}
}
```

### Shrink all absolute sizes to half

```php
$oParser = new Sabberworm\CSS\Parser($sText);
$oCss = $oParser->parse();
foreach($oCss->getAllValues() as $mValue) {
	if($mValue instanceof CSSSize && !$mValue->isRelative()) {
		$mValue->setSize($mValue->getSize()/2);
	}
}
```

### Remove unwanted rules

```php
$oParser = new Sabberworm\CSS\Parser($sText);
$oCss = $oParser->parse();
foreach($oCss->getAllRuleSets() as $oRuleSet) {
	$oRuleSet->removeRule('font-'); //Note that the added dash will make this remove all rules starting with font- (like font-size, font-weight, etc.) as well as a potential font-rule
	$oRuleSet->removeRule('cursor');
}
```

### Output

To output the entire CSS document into a variable, just use `->render()`:

```php
$oCssParser = new Sabberworm\CSS\Parser(file_get_contents('somefile.css'));
$oCssDocument = $oCssParser->parse();
print $oCssDocument->render();
```

If you want to format the output, pass an instance of type `Sabberworm\CSS\OutputFormat`:

```php
$oFormat = Sabberworm\CSS\OutputFormat::create()->indentWithSpaces(4)->setSpaceBetweenRules("\n");
print $oCssDocument->render($oFormat);
```

Or use one of the predefined formats:

```php
print $oCssDocument->render(Sabberworm\CSS\OutputFormat::createPretty());
print $oCssDocument->render(Sabberworm\CSS\OutputFormat::createCompact());
```

To see what you can do with output formatting, look at the tests in `tests/Sabberworm/CSS/OutputFormatTest.php`.

## Examples

### Example 1 (At-Rules)

#### Input

```css
@charset "utf-8";

@font-face {
  font-family: "CrassRoots";
  src: url("../media/cr.ttf")
}

html, body {
    font-size: 1.6em
}

@keyframes mymove {
	from { top: 0px; }
	to { top: 200px; }
}

```

#### Structure (`var_dump()`)

```php
class Sabberworm\CSS\CSSList\Document#4 (2) {
  protected $aContents =>
  array(4) {
    [0] =>
    class Sabberworm\CSS\Property\Charset#6 (2) {
      private $sCharset =>
      class Sabberworm\CSS\Value\CSSString#5 (2) {
        private $sString =>
        string(5) "utf-8"
        protected $iLineNo =>
        int(1)
      }
      protected $iLineNo =>
      int(1)
    }
    [1] =>
    class Sabberworm\CSS\RuleSet\AtRuleSet#7 (4) {
      private $sType =>
      string(9) "font-face"
      private $sArgs =>
      string(0) ""
      private $aRules =>
      array(2) {
        'font-family' =>
        array(1) {
          [0] =>
          class Sabberworm\CSS\Rule\Rule#8 (4) {
            private $sRule =>
            string(11) "font-family"
            private $mValue =>
            class Sabberworm\CSS\Value\CSSString#9 (2) {
              private $sString =>
              string(10) "CrassRoots"
              protected $iLineNo =>
              int(4)
            }
            private $bIsImportant =>
            bool(false)
            protected $iLineNo =>
            int(4)
          }
        }
        'src' =>
        array(1) {
          [0] =>
          class Sabberworm\CSS\Rule\Rule#10 (4) {
            private $sRule =>
            string(3) "src"
            private $mValue =>
            class Sabberworm\CSS\Value\URL#11 (2) {
              private $oURL =>
              class Sabberworm\CSS\Value\CSSString#12 (2) {
                private $sString =>
                string(15) "../media/cr.ttf"
                protected $iLineNo =>
                int(5)
              }
              protected $iLineNo =>
              int(5)
            }
            private $bIsImportant =>
            bool(false)
            protected $iLineNo =>
            int(5)
          }
        }
      }
      protected $iLineNo =>
      int(3)
    }
    [2] =>
    class Sabberworm\CSS\RuleSet\DeclarationBlock#13 (3) {
      private $aSelectors =>
      array(2) {
        [0] =>
        class Sabberworm\CSS\Property\Selector#14 (2) {
          private $sSelector =>
          string(4) "html"
          private $iSpecificity =>
          NULL
        }
        [1] =>
        class Sabberworm\CSS\Property\Selector#15 (2) {
          private $sSelector =>
          string(4) "body"
          private $iSpecificity =>
          NULL
        }
      }
      private $aRules =>
      array(1) {
        'font-size' =>
        array(1) {
          [0] =>
          class Sabberworm\CSS\Rule\Rule#16 (4) {
            private $sRule =>
            string(9) "font-size"
            private $mValue =>
            class Sabberworm\CSS\Value\Size#17 (4) {
              private $fSize =>
              double(1.6)
              private $sUnit =>
              string(2) "em"
              private $bIsColorComponent =>
              bool(false)
              protected $iLineNo =>
              int(9)
            }
            private $bIsImportant =>
            bool(false)
            protected $iLineNo =>
            int(9)
          }
        }
      }
      protected $iLineNo =>
      int(8)
    }
    [3] =>
    class Sabberworm\CSS\CSSList\KeyFrame#18 (4) {
      private $vendorKeyFrame =>
      string(9) "keyframes"
      private $animationName =>
      string(6) "mymove"
      protected $aContents =>
      array(2) {
        [0] =>
        class Sabberworm\CSS\RuleSet\DeclarationBlock#19 (3) {
          private $aSelectors =>
          array(1) {
            [0] =>
            class Sabberworm\CSS\Property\Selector#20 (2) {
              private $sSelector =>
              string(4) "from"
              private $iSpecificity =>
              NULL
            }
          }
          private $aRules =>
          array(1) {
            'top' =>
            array(1) {
              [0] =>
              class Sabberworm\CSS\Rule\Rule#21 (4) {
                private $sRule =>
                string(3) "top"
                private $mValue =>
                class Sabberworm\CSS\Value\Size#22 (4) {
                  private $fSize =>
                  double(0)
                  private $sUnit =>
                  string(2) "px"
                  private $bIsColorComponent =>
                  bool(false)
                  protected $iLineNo =>
                  int(13)
                }
                private $bIsImportant =>
                bool(false)
                protected $iLineNo =>
                int(13)
              }
            }
          }
          protected $iLineNo =>
          int(13)
        }
        [1] =>
        class Sabberworm\CSS\RuleSet\DeclarationBlock#23 (3) {
          private $aSelectors =>
          array(1) {
            [0] =>
            class Sabberworm\CSS\Property\Selector#24 (2) {
              private $sSelector =>
              string(2) "to"
              private $iSpecificity =>
              NULL
            }
          }
          private $aRules =>
          array(1) {
            'top' =>
            array(1) {
              [0] =>
              class Sabberworm\CSS\Rule\Rule#25 (4) {
                private $sRule =>
                string(3) "top"
                private $mValue =>
                class Sabberworm\CSS\Value\Size#26 (4) {
                  private $fSize =>
                  double(200)
                  private $sUnit =>
                  string(2) "px"
                  private $bIsColorComponent =>
                  bool(false)
                  protected $iLineNo =>
                  int(14)
                }
                private $bIsImportant =>
                bool(false)
                protected $iLineNo =>
                int(14)
              }
            }
          }
          protected $iLineNo =>
          int(14)
        }
      }
      protected $iLineNo =>
      int(12)
    }
  }
  protected $iLineNo =>
  int(1)
}

```

#### Output (`render()`)

```css
@charset "utf-8";
@font-face {font-family: "CrassRoots";src: url("../media/cr.ttf");}
html, body {font-size: 1.6em;}
@keyframes mymove {from {top: 0px;}
	to {top: 200px;}}
```

### Example 2 (Values)

#### Input

```css
#header {
	margin: 10px 2em 1cm 2%;
	font-family: Verdana, Helvetica, "Gill Sans", sans-serif;
	color: red !important;
}

```

#### Structure (`var_dump()`)

```php
class Sabberworm\CSS\CSSList\Document#4 (2) {
  protected $aContents =>
  array(1) {
    [0] =>
    class Sabberworm\CSS\RuleSet\DeclarationBlock#5 (3) {
      private $aSelectors =>
      array(1) {
        [0] =>
        class Sabberworm\CSS\Property\Selector#6 (2) {
          private $sSelector =>
          string(7) "#header"
          private $iSpecificity =>
          NULL
        }
      }
      private $aRules =>
      array(3) {
        'margin' =>
        array(1) {
          [0] =>
          class Sabberworm\CSS\Rule\Rule#7 (4) {
            private $sRule =>
            string(6) "margin"
            private $mValue =>
            class Sabberworm\CSS\Value\RuleValueList#12 (3) {
              protected $aComponents =>
              array(4) {
                [0] =>
                class Sabberworm\CSS\Value\Size#8 (4) {
                  private $fSize =>
                  double(10)
                  private $sUnit =>
                  string(2) "px"
                  private $bIsColorComponent =>
                  bool(false)
                  protected $iLineNo =>
                  int(2)
                }
                [1] =>
                class Sabberworm\CSS\Value\Size#9 (4) {
                  private $fSize =>
                  double(2)
                  private $sUnit =>
                  string(2) "em"
                  private $bIsColorComponent =>
                  bool(false)
                  protected $iLineNo =>
                  int(2)
                }
                [2] =>
                class Sabberworm\CSS\Value\Size#10 (4) {
                  private $fSize =>
                  double(1)
                  private $sUnit =>
                  string(2) "cm"
                  private $bIsColorComponent =>
                  bool(false)
                  protected $iLineNo =>
                  int(2)
                }
                [3] =>
                class Sabberworm\CSS\Value\Size#11 (4) {
                  private $fSize =>
                  double(2)
                  private $sUnit =>
                  string(1) "%"
                  private $bIsColorComponent =>
                  bool(false)
                  protected $iLineNo =>
                  int(2)
                }
              }
              protected $sSeparator =>
              string(1) " "
              protected $iLineNo =>
              int(2)
            }
            private $bIsImportant =>
            bool(false)
            protected $iLineNo =>
            int(2)
          }
        }
        'font-family' =>
        array(1) {
          [0] =>
          class Sabberworm\CSS\Rule\Rule#13 (4) {
            private $sRule =>
            string(11) "font-family"
            private $mValue =>
            class Sabberworm\CSS\Value\RuleValueList#15 (3) {
              protected $aComponents =>
              array(4) {
                [0] =>
                string(7) "Verdana"
                [1] =>
                string(9) "Helvetica"
                [2] =>
                class Sabberworm\CSS\Value\CSSString#14 (2) {
                  private $sString =>
                  string(9) "Gill Sans"
                  protected $iLineNo =>
                  int(3)
                }
                [3] =>
                string(10) "sans-serif"
              }
              protected $sSeparator =>
              string(1) ","
              protected $iLineNo =>
              int(3)
            }
            private $bIsImportant =>
            bool(false)
            protected $iLineNo =>
            int(3)
          }
        }
        'color' =>
        array(1) {
          [0] =>
          class Sabberworm\CSS\Rule\Rule#16 (4) {
            private $sRule =>
            string(5) "color"
            private $mValue =>
            string(3) "red"
            private $bIsImportant =>
            bool(true)
            protected $iLineNo =>
            int(4)
          }
        }
      }
      protected $iLineNo =>
      int(1)
    }
  }
  protected $iLineNo =>
  int(1)
}

```

#### Output (`render()`)

```css
#header {margin: 10px 2em 1cm 2%;font-family: Verdana,Helvetica,"Gill Sans",sans-serif;color: red !important;}
```

## Contributors/Thanks to

* [raxbg](https://github.com/raxbg) for contributions to parse `calc`, grid lines, and various bugfixes.
* [westonruter](https://github.com/westonruter) for bugfixes and improvements.
* [FMCorz](https://github.com/FMCorz) for many patches and suggestions, for being able to parse comments and IE hacks (in lenient mode).
* [Lullabot](https://github.com/Lullabot) for a patch that allows to know the line number for each parsed token.
* [ju1ius](https://github.com/ju1ius) for the specificity parsing code and the ability to expand/compact shorthand properties.
* [ossinkine](https://github.com/ossinkine) for a 150 time performance boost.
* [GaryJones](https://github.com/GaryJones) for lots of input and [http://css-specificity.info/](http://css-specificity.info/).
* [docteurklein](https://github.com/docteurklein) for output formatting and `CSSList->remove()` inspiration.
* [nicolopignatelli](https://github.com/nicolopignatelli) for PSR-0 compatibility.
* [diegoembarcadero](https://github.com/diegoembarcadero) for keyframe at-rule parsing.
* [goetas](https://github.com/goetas) for @namespace at-rule support.
* [View full list](https://github.com/sabberworm/PHP-CSS-Parser/contributors)

## Misc

* Legacy Support: The latest pre-PSR-0 version of this project can be checked with the `0.9.0` tag.
* Running Tests: To run all unit tests for this project, run `composer install` to install phpunit and use `./vendor/phpunit/phpunit/phpunit`.

## License

PHP-CSS-Parser is freely distributable under the terms of an MIT-style license.

Copyright (c) 2011 Raphael Schweikert, http://sabberworm.com/

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
