<?php

namespace Sabberworm\CSS;

use Sabberworm\CSS\CSSList\Document;
use Sabberworm\CSS\Parsing\ParserState;

/**
 * Parser class parses CSS from text into a data structure.
 */
class Parser {
	private $oParserState;

	/**
	 * Parser constructor.
	 * Note that that iLineNo starts from 1 and not 0
	 *
	 * @param $sText
	 * @param Settings|null $oParserSettings
	 * @param int $iLineNo
	 */
	public function __construct($sText, Settings $oParserSettings = null, $iLineNo = 1) {
		if ($oParserSettings === null) {
			$oParserSettings = Settings::create();
		}
		$this->oParserState = new ParserState($sText, $oParserSettings, $iLineNo);
	}

	public function setCharset($sCharset) {
		$this->oParserState->setCharset($sCharset);
	}

	public function getCharset() {
		$this->oParserState->getCharset();
	}

	public function parse() {
		return Document::parse($this->oParserState);
	}

}
