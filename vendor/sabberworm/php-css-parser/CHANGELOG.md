# Revision History

## 8.0

### 8.0.0 (2016-06-30)

* Store source CSS line numbers in tokens and parsing exceptions.
* *No deprecations*

#### Backwards-incompatible changes

* Unrecoverable parser errors throw an exception of type `Sabberworm\CSS\Parsing\SourceException` instead of `\Exception`.

### 8.1.0 (2016-07-19)

* Comments are no longer silently ignored but stored with the object with which they appear (no render support, though). Thanks to @FMCorz.
* The IE hacks using `\0` and `\9` can now be parsed (and rendered) in lenient mode. Thanks (again) to @FMCorz.
* Media queries with or without spaces before the query are parsed. Still no *real* parsing support, though. Sorry…
* PHPUnit is now listed as a dev-dependency in composer.json.
* *No backwards-incompatible changes*
* *No deprecations*

### 8.2.0 (2018-07-13)

* Support parsing `calc()`, thanks to @raxbg.
* Support parsing grid-lines, again thanks to @raxbg.
* Support parsing legacy IE filters (`progid:`) in lenient mode, thanks to @FMCorz
* Performance improvements parsing large files, again thanks to @FMCorz
* *No backwards-incompatible changes*
* *No deprecations*

### 8.3.0 (2019-02-22)

* Refactor parsing logic to mostly reside in the class files whose data structure is to be parsed (this should eventually allow us to unit-test specific parts of the parsing logic individually).
* Fix error in parsing `calc` expessions when the first operand is a negative number, thanks to @raxbg.
* Support parsing CSS4 colors in hex notation with alpha values, thanks to @raxbg.
* Swallow more errors in lenient mode, thanks to @raxbg.
* Allow specifying arbitrary strings to output before and after declaration blocks, thanks to @westonruter.
* *No backwards-incompatible changes*
* *No deprecations*

## 7.0

### 7.0.0 (2015-08-24)

* Compatibility with PHP 7. Well timed, eh?
* *No deprecations*

#### Backwards-incompatible changes

* The `Sabberworm\CSS\Value\String` class has been renamed to `Sabberworm\CSS\Value\CSSString`.

### 7.0.1 (2015-12-25)

* No more suppressed `E_NOTICE`
* *No backwards-incompatible changes*
* *No deprecations*

### 7.0.2 (2016-02-11)

* 150 time performance boost thanks to @[ossinkine](https://github.com/ossinkine)
* *No backwards-incompatible changes*
* *No deprecations*

### 7.0.3 (2016-04-27)

* Fixed parsing empty CSS when multibyte is off
* *No backwards-incompatible changes*
* *No deprecations*

## 6.0

### 6.0.0 (2014-07-03)

* Format output using Sabberworm\CSS\OutputFormat
* *No backwards-incompatible changes*

#### Deprecations

* The parse() method replaces __toString with an optional argument (instance of the OutputFormat class)

### 6.0.1 (2015-08-24)

* Remove some declarations in interfaces incompatible with PHP 5.3 (< 5.3.9)
* *No deprecations*

## 5.0

### 5.0.0 (2013-03-20)

* Correctly parse all known CSS 3 units (including Hz and kHz).
* Output RGB colors in short (#aaa or #ababab) notation
* Be case-insensitive when parsing identifiers.
* *No deprecations*

#### Backwards-incompatible changes

* `Sabberworm\CSS\Value\Color`’s `__toString` method overrides `CSSList`’s to maybe return something other than `type(value, …)` (see above).

### 5.0.1 (2013-03-20)

* Internal cleanup
* *No backwards-incompatible changes*
* *No deprecations*

### 5.0.2 (2013-03-21)

* CHANGELOG.md file added to distribution
* *No backwards-incompatible changes*
* *No deprecations*

### 5.0.3 (2013-03-21)

* More size units recognized
* *No backwards-incompatible changes*
* *No deprecations*

### 5.0.4 (2013-03-21)

* Don’t output floats with locale-aware separator chars
* *No backwards-incompatible changes*
* *No deprecations*

### 5.0.5 (2013-04-17)

* Initial support for lenient parsing (setting this parser option will catch some exceptions internally and recover the parser’s state as neatly as possible).
* *No backwards-incompatible changes*
* *No deprecations*

### 5.0.6 (2013-05-31)

* Fix broken unit test
* *No backwards-incompatible changes*
* *No deprecations*

### 5.0.7 (2013-08-04)

* Fix broken decimal point output optimization
* *No backwards-incompatible changes*
* *No deprecations*

### 5.0.8 (2013-08-15)

* Make default settings’ multibyte parsing option dependent on whether or not the mbstring extension is actually installed.
* *No backwards-incompatible changes*
* *No deprecations*

### 5.1.0 (2013-10-24)

* Performance enhancements by Michael M Slusarz
* More rescue entry points for lenient parsing (unexpected tokens between declaration blocks and unclosed comments)
* *No backwards-incompatible changes*
* *No deprecations*

### 5.1.1 (2013-10-28)

* Updated CHANGELOG.md to reflect changes since 5.0.4
* *No backwards-incompatible changes*
* *No deprecations*

### 5.1.2 (2013-10-30)

* Remove the use of consumeUntil in comment parsing. This makes it possible to parse comments such as `/** Perfectly valid **/`
* Add fr relative size unit
* Fix some issues with HHVM
* *No backwards-incompatible changes*
* *No deprecations*

### 5.2.0 (2014-06-30)

* Support removing a selector from a declaration block using `$oBlock->removeSelector($mSelector)`
* Introduce a specialized exception (Sabberworm\CSS\Parsing\OuputException) for exceptions during output rendering

* *No deprecations*

#### Backwards-incompatible changes

* Outputting a declaration block that has no selectors throws an OuputException instead of outputting an invalid ` {…}` into the CSS document.

## 4.0

### 4.0.0 (2013-03-19)

* Support for more @-rules
* Generic interface `Sabberworm\CSS\Property\AtRule`, implemented by all @-rule classes
* *No deprecations*

#### Backwards-incompatible changes

* `Sabberworm\CSS\RuleSet\AtRule` renamed to `Sabberworm\CSS\RuleSet\AtRuleSet`
* `Sabberworm\CSS\CSSList\MediaQuery` renamed to `Sabberworm\CSS\RuleSet\CSSList\AtRuleBlockList` with differing semantics and API (which also works for other block-list-based @-rules like `@supports`).

## 3.0

### 3.0.0 (2013-03-06)

* Support for lenient parsing (on by default)
* *No deprecations*

#### Backwards-incompatible changes

* All properties (like whether or not to use `mb_`-functions, which default charset to use and – new – whether or not to be forgiving when parsing) are now encapsulated in an instance of `Sabberworm\CSS\Settings` which can be passed as the second argument to `Sabberworm\CSS\Parser->__construct()`.
* Specifying a charset as the second argument to `Sabberworm\CSS\Parser->__construct()` is no longer supported. Use `Sabberworm\CSS\Settings::create()->withDefaultCharset('some-charset')` instead.
* Setting `Sabberworm\CSS\Parser->bUseMbFunctions` has no effect. Use `Sabberworm\CSS\Settings::create()->withMultibyteSupport(true/false)` instead.
* `Sabberworm\CSS\Parser->parse()` may throw a `Sabberworm\CSS\Parsing\UnexpectedTokenException` when in strict parsing mode.

## 2.0

### 2.0.0 (2013-01-29)

* Allow multiple rules of the same type per rule set

#### Backwards-incompatible changes

* `Sabberworm\CSS\RuleSet->getRules()` returns an index-based array instead of an associative array. Use `Sabberworm\CSS\RuleSet->getRulesAssoc()` (which eliminates duplicate rules and lets the later rule of the same name win).
* `Sabberworm\CSS\RuleSet->removeRule()` works as it did before except when passed an instance of `Sabberworm\CSS\Rule\Rule`, in which case it would only remove the exact rule given instead of all the rules of the same type. To get the old behaviour, use `Sabberworm\CSS\RuleSet->removeRule($oRule->getRule()`;

## 1.0

Initial release of a stable public API.

## 0.9

Last version not to use PSR-0 project organization semantics.
