> # UKRAINE NEEDS YOUR HELP NOW!
>
> On 24 February 2022, Russian [President Vladimir Putin ordered an invasion of Ukraine by Russian Armed Forces](https://www.bbc.com/news/world-europe-60504334).
>
> Your support is urgently needed.
>
> - Donate to the volunteers. Here is the volunteer fund helping the Ukrainian army to provide all the necessary equipment:
>  https://bank.gov.ua/en/news/all/natsionalniy-bank-vidkriv-spetsrahunok-dlya-zboru-koshtiv-na-potrebi-armiyi or https://savelife.in.ua/en/donate/
> - Triple-check social media sources. Russian disinformation is attempting to coverup and distort the reality in Ukraine.
> - Help Ukrainian refugees who are fleeing Russian attacks and shellings: https://www.globalcitizen.org/en/content/ways-to-help-ukraine-conflict/
> -  Put pressure on your political representatives to provide help to Ukraine.
> -  Believe in the Ukrainian people, they will not surrender, they don't have another Ukraine.
>
> THANK YOU!
----

# HTML5-PHP

HTML5 is a standards-compliant HTML5 parser and writer written entirely in PHP.
It is stable and used in many production websites, and has
well over [five million downloads](https://packagist.org/packages/masterminds/html5).

HTML5 provides the following features.

- An HTML5 serializer
- Support for PHP namespaces
- Composer support
- Event-based (SAX-like) parser
- A DOM tree builder
- Interoperability with [QueryPath](https://github.com/technosophos/querypath)
- Runs on **PHP** 5.3.0 or newer

[![Build Status](https://travis-ci.org/Masterminds/html5-php.png?branch=master)](https://travis-ci.org/Masterminds/html5-php)
[![Latest Stable Version](https://poser.pugx.org/masterminds/html5/v/stable.png)](https://packagist.org/packages/masterminds/html5)
[![Code Coverage](https://scrutinizer-ci.com/g/Masterminds/html5-php/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/Masterminds/html5-php/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Masterminds/html5-php/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Masterminds/html5-php/?branch=master)
[![Stability: Sustained](https://masterminds.github.io/stability/sustained.svg)](https://masterminds.github.io/stability/sustained.html)

## Installation

Install HTML5-PHP using [composer](http://getcomposer.org/).

By adding the `masterminds/html5` dependency to your `composer.json` file:

```json
{
  "require" : {
    "masterminds/html5": "^2.0"
  },
}
```

By invoking require command via composer executable:

```bash
composer require masterminds/html5
```

## Basic Usage

HTML5-PHP has a high-level API and a low-level API.

Here is how you use the high-level `HTML5` library API:

```php
<?php
// Assuming you installed from Composer:
require "vendor/autoload.php";

use Masterminds\HTML5;

// An example HTML document:
$html = <<< 'HERE'
  <html>
  <head>
    <title>TEST</title>
  </head>
  <body id='foo'>
    <h1>Hello World</h1>
    <p>This is a test of the HTML5 parser.</p>
  </body>
  </html>
HERE;

// Parse the document. $dom is a DOMDocument.
$html5 = new HTML5();
$dom = $html5->loadHTML($html);

// Render it as HTML5:
print $html5->saveHTML($dom);

// Or save it to a file:
$html5->save($dom, 'out.html');
```

The `$dom` created by the parser is a full `DOMDocument` object. And the
`save()` and `saveHTML()` methods will take any DOMDocument.

### Options

It is possible to pass in an array of configuration options when loading
an HTML5 document.

```php
// An associative array of options
$options = array(
  'option_name' => 'option_value',
);

// Provide the options to the constructor
$html5 = new HTML5($options);

$dom = $html5->loadHTML($html);
```

The following options are supported:

* `encode_entities` (boolean): Indicates that the serializer should aggressively
  encode characters as entities. Without this, it only encodes the bare
  minimum.
* `disable_html_ns` (boolean): Prevents the parser from automatically
  assigning the HTML5 namespace to the DOM document. This is for
  non-namespace aware DOM tools.
* `target_document` (\DOMDocument): A DOM document that will be used as the
  destination for the parsed nodes.
* `implicit_namespaces` (array): An assoc array of namespaces that should be
  used by the parser. Name is tag prefix, value is NS URI.

## The Low-Level API

This library provides the following low-level APIs that you can use to
create more customized HTML5 tools:

- A SAX-like event-based parser that you can hook into for special kinds
of parsing.
- A flexible error-reporting mechanism that can be tuned to document
syntax checking.
- A DOM implementation that uses PHP's built-in DOM library.

The unit tests exercise each piece of the API, and every public function
is well-documented.

### Parser Design

The parser is designed as follows:

- The `Scanner` handles scanning on behalf of the parser.
- The `Tokenizer` requests data off of the scanner, parses it, clasifies
it, and sends it to an `EventHandler`. It is a *recursive descent parser.*
- The `EventHandler` receives notifications and data for each specific
semantic event that occurs during tokenization.
- The `DOMBuilder` is an `EventHandler` that listens for tokenizing
events and builds a document tree (`DOMDocument`) based on the events.

### Serializer Design

The serializer takes a data structure (the `DOMDocument`) and transforms
it into a character representation -- an HTML5 document.

The serializer is broken into three parts:

- The `OutputRules` contain the rules to turn DOM elements into strings. The
rules are an implementation of the interface `RulesInterface` allowing for
different rule sets to be used.
- The `Traverser`, which is a special-purpose tree walker. It visits
each node node in the tree and uses the `OutputRules` to transform the node
into a string.
- `HTML5` manages the `Traverser` and stores the resultant data
in the correct place.

The serializer (`save()`, `saveHTML()`) follows the
[section 8.9 of the HTML 5.0 spec](http://www.w3.org/TR/2012/CR-html5-20121217/syntax.html#serializing-html-fragments).
So tags are serialized according to these rules:

- A tag with children: &lt;foo&gt;CHILDREN&lt;/foo&gt;
- A tag that cannot have content: &lt;foo&gt; (no closing tag)
- A tag that could have content, but doesn't: &lt;foo&gt;&lt;/foo&gt;

## Known Issues (Or, Things We Designed Against the Spec)

Please check the issue queue for a full list, but the following are
issues known issues that are not presently on the roadmap:

- Namespaces: HTML5 only [supports a selected list of namespaces](http://www.w3.org/TR/html5/infrastructure.html#namespaces)
  and they do not operate in the same way as XML namespaces. A `:` has no special
  meaning.
  By default the parser does not support XML style namespaces via `:`;
  to enable the XML namespaces see the  [XML Namespaces section](#xml-namespaces)
- Scripts: This parser does not contain a JavaScript or a CSS
  interpreter. While one may be supplied, not all features will be
  supported.
- Reentrance: The current parser is not re-entrant. (Thus you can't pause
  the parser to modify the HTML string mid-parse.)
- Validation: The current tree builder is **not** a validating parser.
  While it will correct some HTML, it does not check that the HTML
  conforms to the standard. (Should you wish, you can build a validating
  parser by extending DOMTree or building your own EventHandler
  implementation.)
  * There is limited support for insertion modes.
  * Some autocorrection is done automatically.
  * Per the spec, many legacy tags are admitted and correctly handled,
    even though they are technically not part of HTML5.
- Attribute names and values: Due to the implementation details of the
  PHP implementation of DOM, attribute names that do not follow the
  XML 1.0 standard are not inserted into the DOM. (Effectively, they
  are ignored.) If you've got a clever fix for this, jump in!
- Processor Instructions: The HTML5 spec does not allow processor
  instructions. We do. Since this is a server-side library, we think
  this is useful. And that means, dear reader, that in some cases you
  can parse the HTML from a mixed PHP/HTML document. This, however,
  is an incidental feature, not a core feature.
- HTML manifests: Unsupported.
- PLAINTEXT: Unsupported.
- Adoption Agency Algorithm: Not yet implemented. (8.2.5.4.7)

## XML Namespaces

To use XML style namespaces you have to configure well the main `HTML5` instance.

```php
use Masterminds\HTML5;
$html = new HTML5(array(
    "xmlNamespaces" => true
));

$dom = $html->loadHTML('<t:tag xmlns:t="http://www.example.com"/>');

$dom->documentElement->namespaceURI; // http://www.example.com

```

You can also add some default prefixes that will not require the namespace declaration,
but its elements will be namespaced.

```php
use Masterminds\HTML5;
$html = new HTML5(array(
    "implicitNamespaces"=>array(
        "t"=>"http://www.example.com"
    )
));

$dom = $html->loadHTML('<t:tag/>');

$dom->documentElement->namespaceURI; // http://www.example.com

```

## Thanks to...

The dedicated (and patient) contributors of patches small and large,
who have already made this library better.See the CREDITS file for
a list of contributors.

We owe a huge debt of gratitude to the original authors of html5lib.

While not much of the original parser remains, we learned a lot from
reading the html5lib library. And some pieces remain here. In
particular, much of the UTF-8 and Unicode handling is derived from the
html5lib project.

## License

This software is released under the MIT license. The original html5lib
library was also released under the MIT license.

See LICENSE.txt

Certain files contain copyright assertions by specific individuals
involved with html5lib. Those have been retained where appropriate.
