# Change Log
All notable changes to this project will be documented in this file.
Updates should follow the [Keep a CHANGELOG](https://keepachangelog.com/) principles.

## [0.19.3] - 2019-06-18

### Fixed

 - Fixed bug where elements with content of `"0"` wouldn't be rendered (#376)

## [0.19.2] - 2019-05-19

### Fixed

 - Fixed bug where default values for nested configuration paths were inadvertently cast to strings

## [0.19.1] - 2019-04-10

### Added

 - Added the missing `addExtension()` method to the new `ConfigurableEnvironmentInterface`

### Fixed

 - Fixed extensions not being able to register other extensions

## [0.19.0] - 2019-04-10

### Added

 - The priority of parsers, processors, and renderers can now be set when `add()`ing them; you no longer need to rely on the order in which they are added
 - Added support for trying multiple parsers per block/inline
 - Extracted two new base interfaces from `Environment`:
   - `EnvironmentInterface`
   - `ConfigurableEnvironmentInterface`
 - Extracted a new `AbstractStringContainerBlock` base class and corresponding `StringContainerInterface` from `AbstractBlock`
 - Added `Cursor::getEncoding()` method
 - Added `.phpstorm.meta.php` file for better IDE code completion
 - Made some minor optimizations here and there

### Changed

 - Pretty much everything now has parameter and return types (#346)
 - Attributes passed to `HtmlElement` will now be escaped by default
 - `Environment` is now a `final` class
 - `Environment::getBlockRendererForClass()` was replaced with `Environment::getBlockRenderersForClass()` (note the added `s`)
 - `Environment::getInlineRendererForClass()` was replaced with `Environment::getInlineRenderersForClass()` (note the added `s`)
 - The `Environment::get____()` methods now return an iterator instead of an array
 - `Context::addBlock()` no longer returns the same block instance you passed into the method, as this served no useful purpose
 - `RegexHelper::isEscapable()` no longer accepts `null` values
 - `Node::replaceChildren()` now accepts any type of `iterable`, not just `array`s
 - Some block elements now extend `AbstractStringContainerBlock` instead of `AbstractBlock`
   - `InlineContainerInterface` now extends the new `StringContainerInterface`
   - The `handleRemainingContents()` method (formerly on `AbstractBlock`, now on `AbstractStringContainerBlock`) is now an `abstract method
   - The `InlineParserContext` constructor now requires an `AbstractStringContainerBlock` instead of an `AbstractBlock`

### Removed

 - Removed support for PHP 5.6 and 7.0 (#346)
 - Removed support for `add()`ing parsers with just the target block/inline class name - you need to include the full namespace now
 - Removed the following unused methods from `Environment`:
   - `getInlineParser($name)`
   - `getInlineParsers()`
   - `createInlineParserEngine()`
 - Removed the unused `getName()` methods:
   - `AbstractBlockParser::getName()`
   - `AbstractInlineParser::getName()`
   - `BlockParserInterface::getName()`
   - `InlinerParserInterface::getName()`
 - Removed the now-useless classes:
   - `AbstractBlockParser`
   - `AbstractInlinerParser`
   - `InlineContainer`
 - Removed the `AbstractBlock::acceptsLines()` method
 - Removed the now-useless constructor from `AbstractBlock`
 - Removed previously-deprecated functionality:
   - `InlineContainer` class
   - `RegexHelper::$instance`
   - `RegexHelper::getInstance()`
   - `RegexHelper::getPartialRegex()`
   - `RegexHelper::getHtmlTagRegex()`
   - `RegexHelper::getLinkTitleRegex()`
   - `RegexHelper::getLinkDestinationBracesRegex()`
   - `RegexHelper::getThematicBreakRegex()`
 - Removed the second `$preserveEntities` parameter from `Xml:escape()`

## [0.18.5] - 2019-03-28

### Fixed

 - Fixed the adjoining `Text` collapser not handling the full tree (thephpleague/commonmark-ext-autolink#10)

## [0.18.4] - 2019-03-23

### Changed

 - Modified how URL normalization decodes certain characters in order to align with the JS library's output
 - Disallowed unescaped `(` in parenthesized link title

### Fixed

 - Fixed two exponential backtracking issues

## [0.18.3] - 2019-03-21

This is a **security update** release.

### Changed

 - XML/HTML entities in attributes will no longer be preserved when rendering (#353)

### Fixed

 - Fix XSS vulnerability caused by improper preservation of entities when rendering (#353)

### Deprecated

 - Deprecated the `$preserveEntites` argument of `Xml::escape()` for removal in the next release (#353)

## [0.18.2] - 2019-03-16

### Fixed

 - Fixed adjoining `Text` elements not being collapsed after delimiter processing

### Deprecated

 - Deprecated the `CommonmarkConverter::VERSION` constant for removal in 1.0.0

## [0.18.1] - 2018-12-29

This is a **security update** release.

### Fixed

 - Fix XSS vulnerability caused by URL normalization not handling/encoding newlines properly (#337, CVE-2018-20583)

## [0.18.0] - 2018-09-18

### Added

 - Added `ConverterInterface` to `Converter` and `CommonMarkConverter` (#330)
 - Added `ListItem::getListData()` method (#329)

### Changed

 - Links with `target="_blank"` will also get `rel="noopener noreferrer"` by default (#331)
 - Implemented several performance optimizations (#324)

## [0.17.5] - 2018-03-29

### Fixed

 - Fixed incorrect version constant value (again)
 - Fixed release checklist to prevent the above from happening
 - Fixed incorrect dates in CHANGELOG

## [0.17.4] - 2018-03-28

### Added

 - Added `ListBlock::setTight()` method

## [0.17.3] - 2018-03-26

### Fixed

 - Fixed incorrect version constant value

## [0.17.2] - 2018-03-25

### Added

 - Added new `RegexHelper::isEscapable()` method

### Fixed

 - Fixed spec compliance bug where escaped spaces should not be allowed in link destinations

## [0.17.1] - 2018-03-18

### Added

 - Added a new constant containing the current version: `CommonMarkConverter::VERSION` (#314)

## [0.17.0] - 2017-12-30

This release contains several breaking changes and a minimum PHP version bump - see <UPGRADE.md> for more details.

### Added

 - Added new `max_nesting_level` setting (#243)
 - Added minor performance optimizations to `Cursor`

### Changed

 - Minimum PHP version is now 5.6.5.
 - All full and partial regular expressions in `RegexHelper` are now defined as constants instead of being built on-the-fly.
 - `Cursor::saveState()` now returns an `array` instead of a `CursorState` object.
 - `Cursor::restoreState()` now accepts an `array` parameter instead of a `CursorState` object.
 - Saving/restoring the Cursor state no longer tracks things that don't change (like the text content).
 - `RegexHelper` is now `final`.
 - References to `InlineContainer` changed to new `InlineContainerInterface` interface.
 - `MiscExtension::addInlineParser()` and `MiscExtension::addBlockRenderer()` now return `$this` instead of nothing.

### Fixed
 - Fixed `Reference::normalizeReference()` not properly collapsing whitespace to a single space

### Deprecated

 - `RegexHelper::getInstance()` and all instance (non-static) methods have been deprecated.
 - The `InlineContainer` interface has been deprecated. Use `InlineContainerInterface` instead.

### Removed

 - Removed support for PHP 5.4 and 5.5.
 - Removed `CursorState` class
 - Removed all previous deprecations:
   - `Cursor::getFirstNonSpacePosition()`
   - `Cursor::getFirstNonSpaceCharacter()`
   - `Cursor::advanceWhileMatches()`
   - `Cursor::advanceToFirstNonSpace()`
   - `ElementRendererInterface::escape()`
   - `HtmlRenderer::escape()`
   - `RegexHelper::REGEX_UNICODE_WHITESPACE`
   - `RegexHelper::getLinkDestinationRegex()`

## [0.16.0] - 2017-10-30

This release contains breaking changes, several performance improvements, and two deprecations:

### Added

 - Added new `Xml` utility class; moved HTML/XML escaping logic into there (see deprecations below)

### Changed

 - `Environment::getInlineParsersForCharacter()` now returns an empty array (instead of `null`) when no matching parsers are found
 - Three utility classes are now marked `final`:
   - `Html5Entities`
   - `LinkParserHelper`
   - `UrlEncoder`

### Fixed

 - Improved performance of several methods (for a 10% overall performance boost - #292)

### Deprecated

The following methods were deprecated and are scheduled for removal in 0.17.0 or 1.0.0 (whichever comes first).  See <UPGRADE.md> for more information.

 - `Cursor::advanceWhileMatches()` deprecated; use `Cursor::match()` instead.
 - `HtmlRenderer::escape()` deprecated; use `Xml::escape()` instead.

### Removed

 - Removed `DelimiterStack::findFirstMatchingOpener()` which was previously deprecated in 0.15.0

## [0.15.7] - 2017-10-26

### Fixed

 - Improved performance of `Cursor::advanceBy()` (for a 16% performance boost)

## [0.15.6] - 2017-08-08

### Fixed

 - Fixed URI normalization not properly encoding/decoding special characters in certain cases (#287)

## [0.15.5] - 2017-08-05

This release bumps spec compliance to 0.28 without breaking changes to the API.

### Added

 - Project is now tested against PHP 7.2

### Changed

 - Bumped CommonMark spec target to 0.28
 - Changed internal implementation of `LinkParserHelper::parseLinkDestination()` to allow nested parens
 - Changed precedence of strong/emph when both nestings are possible (rule 14)
 - Allow tabs before and after ATX closing header

### Fixed

 - Fixed HTML type 6 block regex matching against `<pre>` (it shouldn't) and not matching `<iframe>` (it should)
 - Fixed reference parser incorrectly handling escaped `]` characters
 - Fixed "multiple of 3" delimiter run calculations

### Deprecated

An unused constant and static method were deprecated and will be removed in a future release.  See <UPGRADE.md> for more information.

 - Deprecated `RegexHelper::REGEX_UNICODE_WHITESPACE` (no longer used)
 - Deprecated `RegexHelper::getLinkDestinationRegex()` (no longer used)

## [0.15.4] - 2017-05-09

### Added

 - Added new methods to `Cursor` (#280):
   - `advanceToNextNonSpaceOrNewline()` - Identical replacement for the (now-deprecated) `advanceToFirstNonSpace()` method
   - `advanceToNextNonSpaceOrTab()` - Similar replacement for `advanceToFirstNonSpace()` but with proper tab handling
   - `getNextNonSpaceCharacter()` - Identical replacement for the (now-deprecated) `getFirstNonSpaceCharacter()` method
   - `getNextNonSpacePosition()` - Identical replacement for the (now-deprecated) `getFirstNonSpacePosition()` method
 - Added new method to `CursorState` (#280):
   - `getNextNonSpaceCache()` - Identical replacement for the (now-deprecated) `getFirstNonSpaceCache()` method

### Fixed

 - Fixed duplicate characters in non-intended lines containing tabs (#279)

### Deprecated

**All deprecations listed here will be removed in a future 0.x release.** See [UPGRADE.md](UPGRADE.md) for instructions on preparing your code for the eventual removal of these methods.

 - Deprecated `Cursor::advanceToFirstNonSpace()` (#280)
   - Use `advanceToNextNonSpaceOrTab()` or `advanceToNextNonSpaceOrNewline()` instead, depending on your requirements
 - Deprecated `Cursor::getFirstNonSpaceCharacter()` (#280)
   - Use `Cursor::getNextNonSpaceCharacter()` instead
 - Deprecated `Cursor::getFirstNonSpacePosition()` (#280)
   - Use `Cursor::getNextNonSpacePosition()` instead
 - Deprecated `CursorState::getFirstNonSpaceCache()` (#280)
   - Use `CursorState::getNextNonSpaceCache()` instead

## [0.15.3] - 2016-12-18

### Fixed
 - Allow inline parsers matching regex delimiter to be created (#271, #272)

## [0.15.2] - 2016-11-22

### Changed
 - Bumped spec target version to 0.27 (#268)
 - H2-H6 elements are now parsed as HTML block elements instead of HTML inlines

### Fixed
 - Fixed incomplete punctuation regex
 - Fixed shortcut links not being allowed before a `(`
 - Fixed distinction between Unicode whitespace and regular whitespace

## [0.15.1] - 2016-11-08

### Fixed
 - Fixed setext heading underlines not allowing trailing tabs (#266)

## [0.15.0] - 2016-09-14

### Added
 - Added preliminary support for PHP 7.1 (#259)
 - Added more regression tests (#258, #260)

### Changed
 - Bumped spec target version to 0.26 (#260)
 - The `CursorState` constructor requires an additional parameter (#258)
 - Ordered lists cannot interupt a paragraph unless they start with `1` (#260)
 - Blank list items cannot interupt a paragraph (#260)

### Deprecated
 - Deprecated `DelimiterStack::findFirstMatchingOpener()` - use `findMatchingOpener()` instead (#260)

### Fixed
 - Fixed tabs in ATX headers and thematic breaks (#260)
 - Fixed issue where cursor state was not being restored properly (#258, #260)
   - This fixed the lists-with-tabs regression reported in #258

### Removed
 - Removed an unnecessary check in `Cursor::advanceBy()` (#260)
 - Removed the two-blanks-break-out-of-lists feature (#260)


## [0.14.0] - 2016-07-02
### Added
 - The `safe` option is deprecated and replaced by 2 new options (#253, #255):
   - `html_input` (`strip`, `allow` or `escape`): how to handle untrusted HTML input (the default is `strip` for BC reasons)
   - `allow_unsafe_links` (`true` or `false`): whether to allow risky image URLs and links (the default is `true` for BC reasons)

### Deprecated
 - The `safe` option is now deprecated and will be removed in the 1.0.0 release.

## [0.13.4] - 2016-06-14

### Fixed
 - Fixed path to `autoload.php` within bin/commonmark (#250)

## [0.13.3] - 2016-05-21

### Added
 - Added `setUrl()` method for `Link` and `Image` elements (#227, #244)
 - Added cebe/markdown to the benchmark tool (#245)

## [0.13.2] - 2016-03-27

### Added
 - Added ability to invoke `Converter` as a function (#233, #239)
 - Added new `advanceBySpaceOrTab` convenience method to `Cursor`

### Changed
 - Bumped spec target version to 0.25
 - Adjusted how tabs are handled by the `Cursor` (#234)
 - Made a couple small micro-optimizations to heavily used functions (#240)
 - Updated URLs in docblocks to use HTTPS where possible (#238)

## [0.13.1] - 2016-03-09

### Changed
 - Refactored `EmphasisParser::parse()` to simplify it (#223)
 - Updated dev dependencies (#218 & #220)

### Fixed
 - Fixed invalid regex generated when no inline parsers are defined (#224)
 - Fixed logic bug with blank line after empty list item (#230)
 - Fixed some incorrect code comments

### Removed
 - Removed unused variables (#223)

## [0.13.0] - 2016-01-13

### Added
 - Added AST document processors (#210)
 - Added optional `Environment` parameter to `CommonMarkConverter` constructor

### Changed
 - Renamed "header" things to "heading" for spec consistency
   - `Header` => `Heading`
   - `ATXHeaderParser` => `ATXHeadingParser`
   - `SetExtHeaderParser` => `SetExtHeadingParser`
   - `HeaderRenderer` => `HeadingRenderer`
 - Renamed "HorizontalRule" to "ThematicBreak" for spec consistency
   - `HorizontalRule` => `ThematicBreak`
   - `HorizontalRuleParser` => `ThematicBreakParser`
   - `HorizontalRuleRenderer` => `ThematicBreakRenderer`
   - `HorizontalRuleRendererTest` => `ThematicBreakRendererTest`
   - `RegexHelper::getHRuleRegex()` => `RegexHelper::getThematicBreakRegex()`
 - Renamed inline "Html" and "RawHtml" to "HtmlInline" for consistency
   - `Html` => `HtmlInline`
   - `RawHtmlParser` => `HtmlInlineParser`
   - `RawHtmlRenderer` => `HtmlInlineRenderer`
 - Don't allow whitespace between link text and link label of a reference link (spec change)
 - Don't allow spaces in link destinations, even in `<>`
 - Allow multiline setext header content
   - The `Heading` constructor now allows `$contents` to be a `string` (old behavior) or `string[]` (new)

### Fixed
 - Fixed several list issues and regressions (jgm/commonmark.js#59)

### Removed
 - Removed schema whitelist from autolink regex
 - Moved SmartPunct functionality into new [league/commonmark-extras](https://github.com/thephpleague/commonmark-extras) package

## [0.12.0] - 2015-11-04

### Added
 - Added ability to configure characters and disable emphasis/strong (#135)
 - Added new ConfigurationAwareInterface support for all parsers, processors, and renderers (#201)
 - Added HTML safe mode to handle untrusted input (#200, #201)
   - Safe mode is disabled by default for backwards-compatibility
   - To enable it, set the `safe` option to `true`
 - Added AppVeyor integration for automated unit/functional testing on Windows (#195)

### Changed
 - `AbstractBlock::finalize()` now requires a second parameter, `$endLineNumber`
 - `RegexHelper::REGEX_ENTITY` no longer includes the starting `/` or the ending `/i` (#194)
 - `Node::setParent()` now accepts null values (#203)

### Fixed
 - Fixed incorrect `endLine` positions (#187)
 - Fixed `DocParser::preProcessInput` dropping up to 2 ending newlines instead of just one
 - Fixed `EntityParser` not checking for ampersands at the start of the current position (#192, #194)

### Removed
 - Removed protected function Context::addChild()
   - It was a duplicate of the Context::addBlock() method
 - Disabled STDIN reading on `bin/commonmark` for Windows due to PHP issues (#189, #195)

## [0.11.3] - 2015-09-25
### Fixed
 - Reset container after closing containing lists (#183; jgm/commonmark.js#67)
   - The temporary fix from 0.11.2 was reverted

## [0.11.2] - 2015-09-23
### Fixed
 - Fixed parser checking acceptsLines on the wrong element (#183)

## [0.11.1] - 2015-09-22
### Changed
 - Tightened up some loose comparisons

### Fixed
 - Fixed missing "bin" directive in composer.json
 - Updated a docblock to match recent changes to method parameters

### Removed
 - Removed unused variable from within QuoteProcessor's closure

## [0.11.0] - 2015-09-19
### Added
 - Added new `Node` class, which both `AbstractBlock` and `AbstractInline` extend from (#169)
 - Added a `NodeWalker` and `NodeWalkerEvent` to traverse the AST without using recursion
 - Added new `InlineContainer` interface for blocks
 - Added new `getContainer()` and `getReferenceMap()` methods to `InlineParserContext`
 - Added `iframe` to whitelist of HTML block tags (as per spec)
 - Added `./bin/commonmark` for converting Markdown at the command line

### Changed
 - Bumped spec target version to 0.22
 - Revised AST to use a double-linked list (#169)
 - `AbstractBlock` and `AbstractInline` both extend from `Node`
   - Sub-classes must implement new `isContainer()` method
 - Other major changes to `AbstractBlock`:
   - `getParent()` is now `parent()`
   - `setParent()` now expects a `Node` instead of an `AbstractBlock`
   - `getChildren()` is now `children()`
   - `getLastChild()` is now `lastChild()`
   - `addChild()` is now `appendChild()`
 - `InlineParserContext` is constructed using the container `AbstractBlock` and the document's `RefereceMap`
   - The constructor will automatically create the `Cursor` using the container's string contents
 - `InlineParserEngine::parse` now requires the `Node` container and the document's `ReferenceMap` instead of a `ContextInterface` and `Cursor`
 - Changed `Delimiter` to reference the actual inline `Node` instead of the position
   - The `int $pos` protected member and constructor arg is now `Node $node`
   - Use `getInlineNode()` and `setInlineNode()` instead of `getPos()` and `setPos()`
 - Changed `DocParser::processInlines` to use a `NodeWalker` to iterate through inlines
   - Walker passed as second argument instead of `AbstractBlock`
   - Uses a `while` loop instead of recursion to traverse the AST
 - `Image` and `Link` now only accept a string as their second argument
 - Refactored how `CloseBracketParser::parse()` works internally
 - `CloseBracketParser::createInline` no longer accepts label inlines
 - Disallow list item starting with multiple blank lines (see jgm/CommonMark#332)
 - Modified `AbstractBlock::setLastLineBlank()`
   - Functionality moved to `AbstractBlock::shouldLastLineBeBlank()` and new `DocParser::setAndPropagateLastLineBlank()` method
   - `AbstractBlock::setLastLineBlank()` is now a setter method for `AbstractBlock::$lastLineBlank`
 - `AbstractBlock::handleRemainingContents()` is no longer abstract
   - A default implementation is provided
   - Removed duplicate code from sub-classes which used the default implementation - they'll just use the parent method from now on

### Fixed
 - Fixed logic error in calculation of offset (see jgm/commonmark.js@94053a8)
 - Fixed bug where `DocParser` checked the wrong method to determine remainder handling behavior
 - Fixed bug where `HorizontalRuleParser` failed to advance the cursor beyond the parsed horizontal rule characters
 - Fixed `DocParser` not ignoring the final newline of the input (like the reference parser does)

### Removed
 - Removed `Block\Element\AbstractInlineContainer`
   - Extend `AbstractBlock` and implement `InlineContainer` instead
   - Use child methods instead of `getInlines` and `setInlines`
 - Removed `AbstractBlock::replaceChild()`
   - Call `Node::replaceWith()` directly the child node instead
 - Removed the `getInlines()` method from `InlineParserContext`
   - Add parsed inlines using `$inlineContext->getContainer()->appendChild()` instead of `$inlineContext->getInlines()->add()`
 - Removed the `ContextInterface` argument from `AbstractInlineParser::parse()` and `InlineParserEngine::parseCharacter`
 - Removed the first `ArrayCollection $inlines` argument from `InlineProcessorInterface::processInlines()`
 - Removed `CloseBracketParser::nullify()`
 - Removed `pre` from rule 6 of HTML blocks (see jgm/CommonMark#355)

## [0.10.0] - 2015-07-25
### Added
 - Added parent references to inline elements (#124)
 - Added smart punctuation extension (#134)
 - Added HTML block types
 - Added indentation caching to the cursor
 - Added automated code style checks (#133)
 - Added support for tag attributes in renderers (#101, #165)

### Changed
 - Bumped spec target version to 0.21
 - Revised HTML block parsing to conform to new spec (jgm/commonmark.js@99bd473)
 - Imposed 9-digit limit on ordered list markers, per spec
 - Allow non-initial hyphens in html tag names (jgm/CommonMark#239)
 - Updated list of block tag names
 - Changed tab/indentation handling to meet the new spec behavior
 - Modified spec tests to show spaces and tabs in test results
 - Replaced `HtmlRendererInterface` with `ElementRendererInterface` (#141)
 - Removed the unnecessary `trim()` and string cast from `ListItemRenderer`

### Fixed
 - Fixed link reference definition edge case (#120)
 - Allow literal (non-escaping) backslashes in link destinations (#118)
 - Allow backslash-escaped backslashes in link labels (#119)
 - Allow link labels up to 999 characters (per the spec)
 - Properly split on whitespace when determining code block class (jgm/commonmark.js#54)
 - Fixed code style issues (#132, #133, #151, #152)
 - Fixed wording for invalid inline exception (#136)

### Removed
 - Removed the advance-by-one optimization due to added cursor complexity

## [0.9.0] - 2015-06-18
### Added
 - Added public $data array to block elements (#95)
 - Added `isIndented` helper method to `Cursor`
 - Added a new `Converter` base class which `CommonMarkConverter` extends from (#105)

### Changed
 - Bumped spec target version to 0.20 (#112)
 - Renamed ListBlock::$data and ListItem::$data to $listData
 - Require link labels to contain non-whitespace (jgm/CommonMark#322)
 - Use U+FFFD for entities resolving to 0 (jgm/CommonMark#323)
 - Moved `IndentedCodeParser::CODE_INDENT_LEVEL` to `Cursor::INDENT_LEVEL`
 - Changed arrays to short syntax (#116)
 - Improved efficiency of DelimiterStack iteration (jgm/commonmark.js#43)

### Fixed
 - Fixed open block tag followed by newline not being recognized (jgm/CommonMark#324)
 - Fixed indented lists sometimes being parsed incorrectly (jgm/commonmark.js#42)

## [0.8.0] - 2015-04-29
### Added
 - Allow swapping built-in renderers without using their fully qualified names (#84)
 - Lots of unit tests (for existing code)
 - Ability to include arbitrary functional tests in addition to spec-based tests

### Changed
 - Dropped support for PHP 5.3 (#64 and #76)
 - Bumped spec target version to 0.19
 - Made the AbstractInlineContainer be abstract
 - Moved environment config. logic into separate class

### Fixed
 - Fixed underscore emphasis to conform to spec changes (jgm/CommonMark#317)

### Removed
 - Removed PHP 5.3 workaround (see commit 5747822)
 - Removed unused AbstractWebResource::setUrl() method
 - Removed unnecessary check for hrule when parsing lists (#85)

## [0.7.2] - 2015-03-08
### Changed
 - Bumped spec target version to 0.18

### Fixed
 - Fixed broken parsing of emphasized text ending with a '0' character (#81)

## [0.7.1] - 2015-03-01
### Added
 - All references can now be obtained from the `ReferenceMap` via `listReferences()` (#73)
 - Test against PHP 7.0 (nightly) but allow failures

### Changed
 - ListData::$start now defaults to null instead of 0 (#74)
 - Replace references to HtmlRenderer with new HtmlRendererInterface

### Fixed
 - Fixed 0-based ordered lists starting at 1 instead of 0 (#74)
 - Fixed errors parsing multi-byte characters (#78 and #79)

## [0.7.0] - 2015-02-16
### Added
 - More unit tests to increase code coverage

### Changed
 - Enabled the InlineParserEngine to parse several non-special characters at once (performance boost)
 - NewlineParser no longer attempts to parse spaces; look-behind is used instead (major performance boost)
 - Moved closeUnmatchedBlocks into its own class
 - Image and link elements now extend AbstractInlineContainer; label data is stored via $inlineContents instead
 - Renamed AbstractInlineContainer::$inlineContents and its getter/setter

### Removed
 - Removed the InlineCollection class
 - Removed the unused ArrayCollection::splice() method
 - Removed impossible-to-reach code in Cursor::advanceToFirstNonSpace
 - Removed unnecessary test from the InlineParserEngine
 - Removed unnecessary/unused RegexHelper::getMainRegex() method

## [0.6.1] - 2015-01-25
### Changed
 - Bumped spec target version to 0.17
 - Updated emphasis parsing for underscores to prevent intra-word emphasis
 - Deferred closing of fenced code blocks

## [0.6.0] - 2015-01-09
### Added
 - Bulk registration of parsers/renderers via extensions (#45)
 - Proper UTF-8 support, especially in the Cursor; mbstring extension is now required (#49)
 - Environment is now configurable; options can be accessed in its parsers/renderers (#56)
 - Added some unit tests

### Changed
 - Bumped spec target version to 0.15 (#50)
 - Parsers/renderers are now lazy-initialized (#52)
 - Some private elements are now protected for easier extending, especially on Element classes (#53)
 - Renderer option keys changed from camelCase to underscore_case (#56)
 - Moved CommonMark parser/render definitions into CommonMarkCoreExtension

### Fixed
 - Improved parsing of emphasis around punctuation
 - Improved regexes for CDATA and HTML comments
 - Fixed issue with HTML content that is considered false in loose comparisons, like `'0'` (#55)
 - Fixed DocParser trying to add empty strings to closed containers (#58)
 - Fixed incorrect use of a null parameter value in the HtmlElementTest

### Removed
 - Removed unused ReferenceDefinition* classes (#51)
 - Removed UnicodeCaseFolder in favor of mb_strtoupper

## [0.5.1] - 2014-12-27
### Fixed
 - Fixed infinite loop and link-in-link-in-image parsing (#37)

### Removed
 - Removed hard dependency on mbstring extension; workaround used if not installed (#38)

## [0.5.0] - 2014-12-24
### Added
 - Support for custom directives, parsers, and renderers

### Changed
 - Major refactoring to de-couple directives from the parser, support custom directive functionality, and reduce complexity
 - Updated references to stmd.js in README and docblocks
 - Modified CHANGELOG formatting
 - Improved travis configuration
 - Put tests in autoload-dev

### Fixed
 - Fixed CommonMarkConverter re-creating object each time new text is converted (#26)

### Removed
 - Removed HtmlRenderer::render() (use the renderBlock method instead)
 - Removed dependency on symfony/options-resolver (fixes #20)

## [0.4.0] - 2014-12-15
### Added
 - Added some missing copyright info

### Changed
 - Changed namespace to League\CommonMark
 - Made compatible with spec version 0.13
 - Moved delimiter stack functionality into separate class

### Fixed
 - Fixed regex which caused HHVM tests to fail

## [0.3.0] - 2014-11-28
### Added
 - Made renderer options configurable (issue #7)

### Changed
 - Made compatible with spec version 0.12
 - Stack-based parsing now used for emphasis, links and images
 - Protected some of the internal renderer methods which shouldn't have been `public`
 - Minor code clean-up (including PSR-2 compliance)

### Removed
 - Removed unnecessary distinction between ATX and Setext headers

## [0.2.1] - 2014-11-09
### Added
 - Added simpler string replacement to a method

### Changed
 - Removed "is" prefix from boolean methods
 * Updated to latest version of PHPUnit
 * Target specific spec version

## [0.2.0] - 2014-11-09
### Changed
 - Mirrored significant changes and improvements from stmd.js
 - Made compatible with spec version 0.10
 - Updated location of JGM's repository
 - Allowed HHVM tests to fail without affecting overall build success

### Removed
 - Removed composer.lock
 - Removed fixed reference to jgm/stmd@0275f34

## [0.1.2] - 2014-09-28
### Added
 - Added performance benchmarking tool (issue #2)
 - Added more badges to the README

### Changed
 - Fix JS -> PHP null judgement (issue #4)
 - Updated phpunit dependency

## [0.1.1] - 2014-09-08
### Added
 - Add anchors to regexes

### Changed
 - Updated target spec (now compatible with jgm/stmd:spec.txt @ 2cf0750)
 - Adjust HTML output for fenced code
 - Adjust block-level tag regex (remove "br", add "iframe")
 - Fix incorrect handling of nested emphasis

## 0.1.0
### Added
 - Initial commit (compatible with jgm/stmd:spec.txt @ 0275f34)

[0.19.3]: https://github.com/thephpleague/commonmark/compare/0.19.2...0.19.3
[0.19.2]: https://github.com/thephpleague/commonmark/compare/0.19.1...0.19.2
[0.19.1]: https://github.com/thephpleague/commonmark/compare/0.19.0...0.19.1
[0.19.0]: https://github.com/thephpleague/commonmark/compare/0.18.5...0.19.0
[0.18.5]: https://github.com/thephpleague/commonmark/compare/0.18.4...0.18.5
[0.18.4]: https://github.com/thephpleague/commonmark/compare/0.18.3...0.18.4
[0.18.3]: https://github.com/thephpleague/commonmark/compare/0.18.2...0.18.3
[0.18.2]: https://github.com/thephpleague/commonmark/compare/0.18.1...0.18.2
[0.18.1]: https://github.com/thephpleague/commonmark/compare/0.18.0...0.18.1
[0.18.0]: https://github.com/thephpleague/commonmark/compare/0.17.5...0.18.0
[0.17.5]: https://github.com/thephpleague/commonmark/compare/0.17.4...0.17.5
[0.17.4]: https://github.com/thephpleague/commonmark/compare/0.17.3...0.17.4
[0.17.3]: https://github.com/thephpleague/commonmark/compare/0.17.2...0.17.3
[0.17.2]: https://github.com/thephpleague/commonmark/compare/0.17.1...0.17.2
[0.17.1]: https://github.com/thephpleague/commonmark/compare/0.17.0...0.17.1
[0.17.0]: https://github.com/thephpleague/commonmark/compare/0.16.0...0.17.0
[0.16.0]: https://github.com/thephpleague/commonmark/compare/0.15.7...0.16.0
[0.15.7]: https://github.com/thephpleague/commonmark/compare/0.15.6...0.15.7
[0.15.6]: https://github.com/thephpleague/commonmark/compare/0.15.5...0.15.6
[0.15.5]: https://github.com/thephpleague/commonmark/compare/0.15.4...0.15.5
[0.15.4]: https://github.com/thephpleague/commonmark/compare/0.15.3...0.15.4
[0.15.3]: https://github.com/thephpleague/commonmark/compare/0.15.2...0.15.3
[0.15.2]: https://github.com/thephpleague/commonmark/compare/0.15.1...0.15.2
[0.15.1]: https://github.com/thephpleague/commonmark/compare/0.15.0...0.15.1
[0.15.0]: https://github.com/thephpleague/commonmark/compare/0.14.0...0.15.0
[0.14.0]: https://github.com/thephpleague/commonmark/compare/0.13.4...0.14.0
[0.13.4]: https://github.com/thephpleague/commonmark/compare/0.13.3...0.13.4
[0.13.3]: https://github.com/thephpleague/commonmark/compare/0.13.2...0.13.3
[0.13.2]: https://github.com/thephpleague/commonmark/compare/0.13.1...0.13.2
[0.13.1]: https://github.com/thephpleague/commonmark/compare/0.13.0...0.13.1
[0.13.0]: https://github.com/thephpleague/commonmark/compare/0.12.0...0.13.0
[0.12.0]: https://github.com/thephpleague/commonmark/compare/0.11.3...0.12.0
[0.11.3]: https://github.com/thephpleague/commonmark/compare/0.11.2...0.11.3
[0.11.2]: https://github.com/thephpleague/commonmark/compare/0.11.1...0.11.2
[0.11.1]: https://github.com/thephpleague/commonmark/compare/0.11.0...0.11.1
[0.11.0]: https://github.com/thephpleague/commonmark/compare/0.10.0...0.11.0
[0.10.0]: https://github.com/thephpleague/commonmark/compare/0.9.0...0.10.0
[0.9.0]: https://github.com/thephpleague/commonmark/compare/0.8.0...0.9.0
[0.8.0]: https://github.com/thephpleague/commonmark/compare/0.7.2...0.8.0
[0.7.2]: https://github.com/thephpleague/commonmark/compare/0.7.1...0.7.2
[0.7.1]: https://github.com/thephpleague/commonmark/compare/0.7.0...0.7.1
[0.7.0]: https://github.com/thephpleague/commonmark/compare/0.6.1...0.7.0
[0.6.1]: https://github.com/thephpleague/commonmark/compare/0.6.0...0.6.1
[0.6.0]: https://github.com/thephpleague/commonmark/compare/0.5.1...0.6.0
[0.5.1]: https://github.com/thephpleague/commonmark/compare/0.5.0...0.5.1
[0.5.0]: https://github.com/thephpleague/commonmark/compare/0.4.0...0.5.0
[0.4.0]: https://github.com/thephpleague/commonmark/compare/0.3.0...0.4.0
[0.3.0]: https://github.com/thephpleague/commonmark/compare/0.2.1...0.3.0
[0.2.1]: https://github.com/thephpleague/commonmark/compare/0.2.0...0.2.1
[0.2.0]: https://github.com/thephpleague/commonmark/compare/0.1.2...0.2.0
[0.1.2]: https://github.com/thephpleague/commonmark/compare/0.1.1...0.1.2
[0.1.1]: https://github.com/thephpleague/commonmark/compare/0.1.0...0.1.1
