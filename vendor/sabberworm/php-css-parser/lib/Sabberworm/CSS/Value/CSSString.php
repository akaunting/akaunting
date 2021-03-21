<?php

namespace Sabberworm\CSS\Value;

use Sabberworm\CSS\Parsing\ParserState;
use Sabberworm\CSS\Parsing\SourceException;

class CSSString extends PrimitiveValue {

	private $sString;

	public function __construct($sString, $iLineNo = 0) {
		$this->sString = $sString;
		parent::__construct($iLineNo);
	}

	public static function parse(ParserState $oParserState) {
		$sBegin = $oParserState->peek();
		$sQuote = null;
		if ($sBegin === "'") {
			$sQuote = "'";
		} else if ($sBegin === '"') {
			$sQuote = '"';
		}
		if ($sQuote !== null) {
			$oParserState->consume($sQuote);
		}
		$sResult = "";
		$sContent = null;
		if ($sQuote === null) {
			// Unquoted strings end in whitespace or with braces, brackets, parentheses
			while (!preg_match('/[\\s{}()<>\\[\\]]/isu', $oParserState->peek())) {
				$sResult .= $oParserState->parseCharacter(false);
			}
		} else {
			while (!$oParserState->comes($sQuote)) {
				$sContent = $oParserState->parseCharacter(false);
				if ($sContent === null) {
					throw new SourceException("Non-well-formed quoted string {$oParserState->peek(3)}", $oParserState->currentLine());
				}
				$sResult .= $sContent;
			}
			$oParserState->consume($sQuote);
		}
		return new CSSString($sResult, $oParserState->currentLine());
	}

	public function setString($sString) {
		$this->sString = $sString;
	}

	public function getString() {
		return $this->sString;
	}

	public function __toString() {
		return $this->render(new \Sabberworm\CSS\OutputFormat());
	}

	public function render(\Sabberworm\CSS\OutputFormat $oOutputFormat) {
		$sString = addslashes($this->sString);
		$sString = str_replace("\n", '\A', $sString);
		return $oOutputFormat->getStringQuotingType() . $sString . $oOutputFormat->getStringQuotingType();
	}

}