# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [2.0.0-beta3]
### Changed
- Remove the deprecated class 'Barryvdh\DomPDF\Facade' Facade in favor of Barryvdh\DomPDF\Facade\Pdf
- Set default Facade to Pdf instead of PDF

## [2.0.0-beta2]

### Added
- Upgraded to use dompdf/dompdf 2.x
- `setOption` to change only the specified option(s), instead of replace all options. 
- Magic methods to allow calls to Dompdf methods easier. (#892)
- `default_paper_orientation` option has been added to the defaults.
- Add option to set public path (#890)

### Changed
- HTML5 parser option is deprecated, because this is always on.
- `orientation` option was never used. Removed in favor of `options.default_paper_orientation`

### Deprecated
- `setOptions` is now deprecated. Use `setOption` instead.
- Config `dompdf.defines` has been renamed to `dompdf.options`


## Dompdf 2.0.0, highlights since 1.2.x
> https://github.com/dompdf/dompdf/releases/tag/v2.0.0
> - Addresses multiple security vulnerabilities (see link)
> - Modifies callback and page_script/page_text handling (breaking change, see link)
> - Switches the HTML5 parser to Masterminds/HTML5
> - Improves CSS property parsing and representation
> - Improves border, outline, and background rendering for inline elements
> - Switches installed fonts and font metrics cache file format to JSON
> - Adds support for the inset CSS shorthand property and the legacy break-word keyword for word-break
> - Adds "end_document" callback event
