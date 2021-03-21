# Change Log
All notable changes to this project will be documented in this file.
Updates should follow the [Keep a CHANGELOG](https://keepachangelog.com/) principles.

## [Unreleased][unreleased]

## [1.5.7] - 2020-10-31

### Fixed

 - Fixed mentions not being parsed when appearing after non-word characters (#582)

## [1.5.6] - 2020-10-17

### Changed

 - Blocks added outside of the parsing context now have their start/end line numbers defaulted to 0 to avoid type errors (#579)

### Fixed

 - Fixed replacement blocks not inheriting the start line number of the container they're replacing (#579)
 - Fixed Table of Contents blocks not having correct start/end line numbers (#579)

## [1.5.5] - 2020-09-13

### Changed

 - Bumped CommonMark spec compliance to 0.28.2

### Fixed

 - Fixed `textarea` elements not being treated as a type 1 HTML block (like `script`, `style`, or `pre`)
 - Fixed autolink processor not handling other unmatched trailing parentheses

## [1.5.4] - 2020-08-17

### Fixed

 - Fixed footnote ID configuration not taking effect (#524, #530)
 - Fixed heading permalink slugs not being unique (#531, #534)

## [1.5.3] - 2020-07-19

### Fixed

 - Fixed regression of multi-byte inline parser characters not being matched

## [1.5.2] - 2020-07-19

### Changed

 - Significantly improved performance of the inline parser regex

### Fixed

 - Fixed parent class lookups for non-existent classes on PHP 8 (#517)

## [1.5.1] - 2020-06-27

### Fixed

 - Fixed UTF-8 encoding not being checked in the `UrlEncoder` utility (#509) or the `Cursor`

## [1.5.0] - 2020-06-21

### Added

 - Added new `AttributesExtension` based on <https://github.com/webuni/commonmark-attributes-extension> (#474)
 - Added new `FootnoteExtension` based on <https://github.com/rezozero/commonmark-ext-footnotes> (#474)
 - Added new `MentionExtension` to replace `InlineMentionParser` with more flexibility and customization
 - Added the ability to render `TableOfContents` nodes anywhere in a document (given by a placeholder)
 - Added the ability to properly clone `Node` objects
 - Added options to customize the value of `rel` attributes set via the `ExternalLink` extension (#476)
 - Added a new `heading_permalink/slug_normalizer` configuration option to allow custom slug generation (#460)
 - Added a new `heading_permalink/symbol` configuration option to replace the now deprecated `heading_permalink/inner_contents` configuration option (#505)
 - Added `SlugNormalizer` and `TextNormalizer` classes to make normalization reusable by extensions (#485)
 - Added new classes:
   - `TableOfContentsGenerator`
   - `TableOfContentsGeneratorInterface`
   - `TableOfContentsPlaceholder`
   - `TableOfContentsPlaceholderParser`
   - `TableOfContentsPlaceholderRenderer`

### Changed

 - "Moved" the `TableOfContents` class into a new `Node` sub-namespace (with backward-compatibility)
 - Reference labels are now generated and stored in lower-case instead of upper-case
 - Reference labels are no longer normalized inside the `Reference`, only the `ReferenceMap`

### Fixed

 - Fixed reference label case folding polyfill not being consistent between different PHP versions

### Deprecated

 - Deprecated the `CommonMarkConverter::VERSION` constant (#496)
 - Deprecated `League\CommonMark\Extension\Autolink\InlineMentionParser` (use `League\CommonMark\Extension\Mention\MentionParser` instead)
 - Deprecated everything under `League\CommonMark\Extension\HeadingPermalink\Slug` (use the classes under `League\CommonMark\Normalizer` instead)
 - Deprecated `League\CommonMark\Extension\TableOfContents\TableOfContents` (use the one in the new `Node` sub-namespace instead)
 - Deprecated the `STYLE_` and `NORMALIZE_` constants in `TableOfContentsBuilder` (use the ones in `TableOfContentsGenerator` instead)
 - Deprecated the `\League\CommonMark\Extension\HeadingPermalink\HeadingPermalinkRenderer::DEFAULT_INNER_CONTENTS` constant (#505)
 - Deprecated the `heading_permalink/inner_contents` configuration option in the `HeadingPermalink` extension (use the new `heading_permalink/symbol` configuration option instead) (#505)

## [1.4.3] - 2020-05-04

### Fixed

 - Fixed certain Unicode letters, numbers, and marks not being preserved when generating URL slugs (#467)

## [1.4.2] - 2020-04-24

### Fixed

 - Fixed inline code blocks not be included within heading permalinks (#457)

## [1.4.1] - 2020-04-20

### Fixed

 - Fixed BC break caused by ConverterInterface alias not being used by some DI containers (#454)

## [1.4.0] - 2020-04-18

### Added

 - Added new [Heading Permalink extension](https://commonmark.thephpleague.com/extensions/heading-permalinks/) (#420)
 - Added new [Table of Contents extension](https://commonmark.thephpleague.com/extensions/table-of-contents/) (#441)
 - Added new `MarkdownConverterInterface` as a long-term replacement for `ConverterInterface` (#439)
 - Added new `DocumentPreParsedEvent` event (#427, #359, #399)
 - Added new `ListBlock::TYPE_BULLET` constant as a replacement for `ListBlock::TYPE_UNORDERED`
 - Added new `MarkdownInput` class and `MarkdownInputInterface` to handle pre-parsing and allow listeners to replace Markdown contents

### Changed

 - Block & inline renderers will now render child classes automatically (#222, #209)
 - The `ListBlock` constants now use fully-lowercased values instead of titlecased values
 - Significantly improved typing

### Fixed

 - Fixed loose comparison when checking for table alignment
 - Fixed `StaggeredDelimiterProcessor` returning from a `void` function

### Deprecated

 - The `Converter` class has been deprecated; use `CommonMarkConverter` instead (#438, #439)
 - The `ConverterInterface` has been deprecated; use `MarkdownConverterInterface` instead (#438, #439)
 - The `bin/commonmark` script has been deprecated
 - The following methods of `ArrayCollection` have been deprecated:
   - `add()`
   - `set()`
   - `get()`
   - `remove()`
   - `isEmpty()`
   - `contains()`
   - `indexOf()`
   - `containsKey()`
   - `replaceWith()`
   - `removeGaps()`
 - The `ListBlock::TYPE_UNORDERED` constant has been deprecated, use `ListBlock::TYPE_BULLET` instead

## [1.3.4] - 2020-04-13

### Fixed

 - Fixed configuration/environment not being injected into event listeners when adding them via `[$instance, 'method']` callable syntax (#440)

## [1.3.3] - 2020-04-05

### Fixed

 - Fixed event listeners not having the environment or configuration injected if they implemented the `EnvironmentAwareInterface` or `ConfigurationAwareInterface` (#423)

## [1.3.2] - 2020-03-25

### Fixed

 - Optimized URL normalization in cases where URLs don't contain special characters (#417, #418)

## [1.3.1] - 2020-02-28

### Fixed

 - Fixed return types of `Environment::createCommonMarkEnvironment()` and `Environment::createGFMEnvironment()`

## [1.3.0] - 2020-02-08

### Added

 - Added (optional) **full GFM support!** 🎉🎉🎉 (#409)
 - Added check to ensure Markdown input is valid UTF-8 (#401, #405)
 - Added new `unordered_list_markers` configuration option (#408, #411)

### Changed

 - Introduced several micro-optimizations for a 5-10% performance boost

## [1.2.2] - 2020-01-15

This release contains the same changes as 1.1.3:

### Fixed

 - Fixed link parsing edge case (#403)

## [1.1.3] - 2020-01-15

### Fixed

 - Fixed link parsing edge case (#403)

## [1.2.1] - 2020-01-14

### Changed

 - Introduced several micro-optimizations, reducing the parse time by 8%

## [1.2.0] - 2020-01-09

### Changed

 - Removed URL decoding step before encoding (more performant and better matches the JS library)
 - Removed redundant token from HTML tag regex

## [1.1.2] - 2019-12-09

### Fixed

 - Fixed URL normalization not handling non-UTF-8 sequences properly (#395, #396)

## [1.1.1] - 2019-11-11

### Fixed

 - Fixed handling of link destinations with unbalanced unescaped parens
 - Fixed adding delimiters to stack which can neither open nor close a run

## [1.1.0] - 2019-10-31

### Added

 - Added a new `Html5EntityDecoder` class (#387)

### Changed

 - Improved performance by 10% (#389)
 - Made entity decoding less memory-intensive (#386, #387)

### Fixed

 - Fixed PHP 7.4 compatibility issues

### Deprecated

 - Deprecated the `Html5Entities` class - use `Html5EntityDecoder` instead (#387)

## [1.0.0] - 2019-06-29

No changes were made since 1.0.0-rc1.

## [1.0.0-rc1] - 2019-06-19

### Added

 - Extracted a `ReferenceMapInterface` from the `ReferenceMap` class
 - Added optional `ReferenceMapInterface` parameter to the `Document` constructor

### Changed

 - Replaced all references to `ReferenceMap` with `ReferenceMapInterface`
 - `ReferenceMap::addReference()` no longer returns `$this`

### Fixed

 - Fixed bug where elements with content of `"0"` wouldn't be rendered (#376)

## [1.0.0-beta4] - 2019-06-05

### Added

 - Added event dispatcher functionality (#359, #372)

### Removed

 - Removed `DocumentProcessorInterface` functionality in favor of event dispatching (#373)

## [1.0.0-beta3] - 2019-05-27

### Changed

 - Made the `Delimiter` class final and extracted a new `DelimiterInterface`
   - Modified most external usages to use this new interface
 - Renamed three `Delimiter` methods:
   - `getOrigDelims()` renamed to `getOriginalLength()`
   - `getNumDelims()` renamed to `getLength()`
   - `setNumDelims()` renamed to `setLength()`
 - Made additional classes final:
   - `DelimiterStack`
   - `ReferenceMap`
   - `ReferenceParser`
 - Moved `ReferenceParser` into the `Reference` sub-namespace

### Removed

 - Removed unused `Delimiter` methods:
   - `setCanOpen()`
   - `setCanClose()`
   - `setChar()`
   - `setIndex()`
   - `setInlineNode()`
 - Removed fluent interface from `Delimiter` (setter methods now have no return values)

## [1.0.0-beta2] - 2019-05-27

### Changed

 - `DelimiterProcessorInterface::process()` will accept any type of `AbstractStringContainer` now, not just `Text` nodes
 - The `Delimiter` constructor, `getInlineNode()`, and `setInlineNode()` no longer accept generic `Node` elements - only `AbstractStringContainer`s


### Removed

 - Removed all deprecated functionality:
   - The `safe` option (use `html_input` and `allow_unsafe_links` options instead)
   - All deprecated `RegexHelper` constants
   - `DocParser::getEnvironment()` (you should obtain it some other way)
   - `AbstractInlineContainer` (use `AbstractInline` instead and make `isContainer()` return `true`)

## [1.0.0-beta1] - 2019-05-26

### Added

 - Added proper support for delimiters, including custom delimiters
   - `addDelimiterProcessor()` added to `ConfigurableEnvironmentInterface` and `Environment`
 - Basic delimiters no longer need custom parsers - they'll be parsed automatically
 - Added new methods:
   - `AdjacentTextMerger::mergeTextNodesBetweenExclusive()`
   - `CommonMarkConveter::getEnvironment()`
   - `Configuration::set()`
 - Extracted some new interfaces from base classes:
   - `DocParserInterface` created from `DocParser`
   - `ConfigurationInterface` created from `Configuration`
   - `ReferenceInterface` created from `Reference`

### Changed

 - Renamed several methods of the `Configuration` class:
   - `getConfig()` renamed to `get()`
   - `mergeConfig()` renamed to `merge()`
   - `setConfig()` renamed to `replace()`
 - Changed `ConfigurationAwareInterface::setConfiguration()` to accept the new `ConfigurationInterface` instead of the concrete class
 - Renamed the `AdjoiningTextCollapser` class to `AdjacentTextMerger`
   - Replaced its `collapseTextNodes()` method with the new `mergeChildNodes()` method
 - Made several classes `final`:
   - `Configuration`
   - `DocParser`
   - `HtmlRenderer`
   - `InlineParserEngine`
   - `NodeWalker`
   - `Reference`
   - All of the block/inline parsers and renderers
 - Reduced visibility of several internal methods to `private`:
    - `DelimiterStack::findEarliest()`
    - All `protected` methods in `InlineParserEngine`
 - Marked some classes and methods as `@internal`
 - `ElementRendererInterface` now requires a public `renderInline()` method; added this to `HtmlRenderer`
 - Changed `InlineParserEngine::parse()` to require an `AbstractStringContainerBlock` instead of the generic `Node` class
 - Un-deprecated the `CommonmarkConverter::VERSION` constant
 - The `Converter` constructor now requires an instance of `DocParserInterface` instead of the concrete `DocParser`
 - Changed `Emphasis`, `Strong`, and `AbstractWebResource` to directly extend `AbstractInline` instead of the (now-deprecated) intermediary `AbstractInlineContainer` class

### Fixed

 - Fixed null errors when inserting sibling `Node`s without parents
 - Fixed `NodeWalkerEvent` not requiring a `Node` via its constructor
 - Fixed `Reference::normalizeReference()` improperly converting to uppercase instead of performing proper Unicode case-folding
 - Fixed strong emphasis delimiters not being preserved when `enable_strong` is set to `false` (it now works identically to `enable_em`)

### Deprecated

 - Deprecated `DocParser::getEnvironment()` (you should obtain it some other way)
 - Deprecated `AbstractInlineContainer` (use `AbstractInline` instead and make `isContainer()` return `true`)

### Removed

 - Removed inline processor functionality now that we have proper delimiter support:
   - Removed `addInlineProcessor()` from `ConfigurableEnvironmentInterface` and `Environment`
   - Removed `getInlineProcessors()` from `EnvironmentInterface` and `Environment`
   - Removed `EmphasisProcessor`
   - Removed `InlineProcessorInterface`
 - Removed `EmphasisParser` now that we have proper delimiter support
 - Removed support for non-UTF-8-compatible encodings
    - Removed `getEncoding()` from `ContextInterface`
    - Removed `getEncoding()`, `setEncoding()`, and `$encoding` from `Context`
    - Removed `getEncoding()` and the second `$encoding` constructor param from `Cursor`
 - Removed now-unused methods
   - Removed `DelimiterStack::getTop()` (no replacement)
   - Removed `DelimiterStack::iterateByCharacters()` (use the new `processDelimiters()` method instead)
   - Removed the protected `DelimiterStack::findMatchingOpener()` method

[unreleased]: https://github.com/thephpleague/commonmark/compare/1.5.7...1.5
[1.5.7]: https://github.com/thephpleague/commonmark/compare/1.5.6...1.5.7
[1.5.6]: https://github.com/thephpleague/commonmark/compare/1.5.5...1.5.6
[1.5.5]: https://github.com/thephpleague/commonmark/compare/1.5.4...1.5.5
[1.5.4]: https://github.com/thephpleague/commonmark/compare/1.5.3...1.5.4
[1.5.3]: https://github.com/thephpleague/commonmark/compare/1.5.2...1.5.3
[1.5.2]: https://github.com/thephpleague/commonmark/compare/1.5.1...1.5.2
[1.5.1]: https://github.com/thephpleague/commonmark/compare/1.5.0...1.5.1
[1.5.0]: https://github.com/thephpleague/commonmark/compare/1.4.3...1.5.0
[1.4.3]: https://github.com/thephpleague/commonmark/compare/1.4.2...1.4.3
[1.4.2]: https://github.com/thephpleague/commonmark/compare/1.4.1...1.4.2
[1.4.1]: https://github.com/thephpleague/commonmark/compare/1.4.0...1.4.1
[1.4.0]: https://github.com/thephpleague/commonmark/compare/1.3.4...1.4.0
[1.3.4]: https://github.com/thephpleague/commonmark/compare/1.3.3...1.3.4
[1.3.3]: https://github.com/thephpleague/commonmark/compare/1.3.2...1.3.3
[1.3.2]: https://github.com/thephpleague/commonmark/compare/1.3.1...1.3.2
[1.3.1]: https://github.com/thephpleague/commonmark/compare/1.3.0...1.3.1
[1.3.0]: https://github.com/thephpleague/commonmark/compare/1.2.2...1.3.0
[1.2.2]: https://github.com/thephpleague/commonmark/compare/1.2.1...1.2.2
[1.2.1]: https://github.com/thephpleague/commonmark/compare/1.2.0...1.2.1
[1.2.0]: https://github.com/thephpleague/commonmark/compare/1.1.2...1.2.0
[1.1.3]: https://github.com/thephpleague/commonmark/compare/1.1.2...1.1.3
[1.1.2]: https://github.com/thephpleague/commonmark/compare/1.1.1...1.1.2
[1.1.1]: https://github.com/thephpleague/commonmark/compare/1.1.0...1.1.1
[1.1.0]: https://github.com/thephpleague/commonmark/compare/1.0.0...1.1.0
[1.0.0]: https://github.com/thephpleague/commonmark/compare/1.0.0-rc1...1.0.0
[1.0.0-rc1]: https://github.com/thephpleague/commonmark/compare/1.0.0-beta4...1.0.0-rc1
[1.0.0-beta4]: https://github.com/thephpleague/commonmark/compare/1.0.0-beta3...1.0.0-beta4
[1.0.0-beta3]: https://github.com/thephpleague/commonmark/compare/1.0.0-beta2...1.0.0-beta3
[1.0.0-beta2]: https://github.com/thephpleague/commonmark/compare/1.0.0-beta1...1.0.0-beta2
[1.0.0-beta1]: https://github.com/thephpleague/commonmark/compare/0.19.2...1.0.0-beta1
