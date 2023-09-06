# Release Notes

2.7.6  (2021-08-18)

- #218: Address comment handling issues 

2.7.5  (2021-07-01)

- #204: Travis: Enable tests on PHP 8.0 
- #207: Fix PHP 8.1 deprecations 

2.7.4  (2020-10-01)

- #191: Fix travisci build 
- #195: Add .gitattributes file with export-ignore rules 
- #194: Fix query parameter parsed as character entity

2.7.3 (2020-07-05)

- #190: mitigate cyclic reference between output rules and the traverser objects 

2.7.2 (2020-07-01)

- #187: Fixed memory leak in HTML5::saveHTML() 
- #186: Add special case for end tag </br>

2.7.1 (2020-06-14)

- #171: add PHP 7.4 job 
- #178: Prevent infinite loop on un-terminated entity declaration at EOF 

2.7.0 (2019-07-25)

- #164: Drop HHVM support
- #168: Set default encoding in the DOMDocument object

2.6.0 (2019-03-10)

- #163: Allow to pass a charset to the Scanner

2.5.0 (2018-12-27)

- #162, #161, #155, #154, #153, #151: big performance improvements
- #156: fixed typos
- #160: adopt and enforce code style
- #159: remove deprecated php unit base test case
- #150: backport changes from old master branch 

2.4.0 (2018-11-17)

- #148: Improve performance by moving sequence matching 
- #147: Improve the Tokenizer performance 
- #146: Improve performance by relying on a native string instead of InputStream 
- #144: Add DOM extension in composer.json
- #145: Add more extensions on composer.json, improve phpdocs and remove dead code 
- #143: Remove experimental comment 

2.3.1 (2018-10-18)

- #121: Audio is not a block tag (fixed by #141)
- #136: Handle illegal self-closing according to spec (fixed by #137)
- #141: Minor fixes in the README

2.3.0 (2017-09-04)

- #129: image within inline svg breaks system (fixed by #133) 
- #131: &sup2; does not work (fixed by #132)
- #134: Improve tokenizer performance by 20% (alternative version of #130 thanks to @MichaelHeerklotz)
- #135: Raw & in attributes

2.2.2 (2016-09-22)

- #116: In XML mode, tags are case sensitive
- #115: Fix PHP Notice in OutputRules
- #112: fix parsing of options of an optgroup
- #111: Adding test for the address tag

2.2.1 (2016-05-10)

- #109: Fixed issue where address tag could be written without closing tag (thanks sylus)

2.2.0 (2016-04-11)

- #105: Enable composer cache (for CI/CD)
- #100: Use mb_substitute_character inset of ini_set for environments where ini_set is disable (e.g., shared hosting)
- #98: Allow link, meta, style tags in noscript tags
- #96: Fixed xml:href on svgs that use the "use" breaking
- #94: Counting UTF8 characters performance improvement
- #93: Use newer version of coveralls package
- #90: Remove duplicate test
- #87: Allow multiple root nodes

2.1.2 (2015-06-07)
- #82: Support for PHP7
- #84: Improved boolean attribute handling

2.1.1 (2015-03-23)
- #78: Fixes bug where unmatched entity like string drops everything after &.

2.1.0 (2015-02-01)
- #74: Added `disable_html_ns` and `target_doc` dom parsing options
- Unified option names
- #73: Fixed alphabet, &szlig; now can be detected
- #75 and #76: Allow whitespace in RCDATA tags
- #77: Fixed parsing blunder for json embeds
- #72: Add options to HTML methods

2.0.2 (2014-12-17)
- #50: empty document handling
- #63: tags with strange capitalization
- #65: dashes and underscores as allowed characters in tag names
- #68: Fixed issue with non-inline elements inside inline containers

2.0.1 (2014-09-23)
- #59: Fixed issue parsing some fragments.
- #56: Incorrectly saw 0 as empty string
- Sami as new documentation generator

2.0.0 (2014-07-28)
- #53: Improved boolean attributes handling
- #52: Facebook HHVM compatibility
- #48: Adopted PSR-2 as coding standard
- #47: Moved everything to Masterminds namespace
- #45: Added custom namespaces
- #44: Added support to XML-style namespaces
- #37: Refactored HTML5 class removing static methods

1.0.5 (2014-06-10)
- #38: Set the dev-master branch as the 1.0.x branch for composer (goetas)
- #34: Tests use PSR-4 for autoloading. (goetas)
- #40, #41: Fix entity handling in RCDATA sections. (KitaitiMakoto)
- #32: Fixed issue where wharacter references were being incorrectly encoded in style tags.

1.0.4 (2014-04-29)
- #30/#31 Don't throw an exception for invalid tag names.

1.0.3 (2014-02-28)
- #23 and #29: Ignore attributes with illegal chars in name for the PHP DOM.

1.0.2 (2014-02-12)
- #23: Handle missing tag close in attribute list.
- #25: Fixed text escaping in the serializer (HTML% 8.3).
- #27: Fixed tests on Windows: changed "\n" -> PHP_EOL.
- #28: Fixed infinite loop for char "&" in unquoted attribute in parser.
- #26: Updated tag name case handling to deal with uppercase usage.
- #24: Newlines and tabs are allowed inside quoted attributes (HTML5 8.2.4).
- Fixed Travis CI testing.

1.0.1 (2013-11-07)
- CDATA encoding is improved. (Non-standard; Issue #19)
- Some parser rules were not returning the new current element. (Issue #20)
- Added, to the README, details on code test coverage and to packagist version.
- Fixed processor instructions.
- Improved test coverage and documentation coverage.

1.0.0 (2013-10-02)
- Initial release.
