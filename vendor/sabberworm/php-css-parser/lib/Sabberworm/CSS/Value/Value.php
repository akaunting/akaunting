<?php

namespace Sabberworm\CSS\Value;

use Sabberworm\CSS\Parsing\ParserState;
use Sabberworm\CSS\Parsing\UnexpectedTokenException;
use Sabberworm\CSS\Renderable;

abstract class Value implements Renderable {
	protected $iLineNo;

	public function __construct($iLineNo = 0) {
		$this->iLineNo = $iLineNo;
	}

	public static function parseValue(ParserState $oParserState, $aListDelimiters = array()) {
		$aStack = array();
		$oParserState->consumeWhiteSpace();
		//Build a list of delimiters and parsed values
		while (!($oParserState->comes('}') || $oParserState->comes(';') || $oParserState->comes('!') || $oParserState->comes(')') || $oParserState->comes('\\'))) {
			if (count($aStack) > 0) {
				$bFoundDelimiter = false;
				foreach ($aListDelimiters as $sDelimiter) {
					if ($oParserState->comes($sDelimiter)) {
						array_push($aStack, $oParserState->consume($sDelimiter));
						$oParserState->consumeWhiteSpace();
						$bFoundDelimiter = true;
						break;
					}
				}
				if (!$bFoundDelimiter) {
					//Whitespace was the list delimiter
					array_push($aStack, ' ');
				}
			}
			array_push($aStack, self::parsePrimitiveValue($oParserState));
			$oParserState->consumeWhiteSpace();
		}
		//Convert the list to list objects
		foreach ($aListDelimiters as $sDelimiter) {
			if (count($aStack) === 1) {
				return $aStack[0];
			}
			$iStartPosition = null;
			while (($iStartPosition = array_search($sDelimiter, $aStack, true)) !== false) {
				$iLength = 2; //Number of elements to be joined
				for ($i = $iStartPosition + 2; $i < count($aStack); $i+=2, ++$iLength) {
					if ($sDelimiter !== $aStack[$i]) {
						break;
					}
				}
				$oList = new RuleValueList($sDelimiter, $oParserState->currentLine());
				for ($i = $iStartPosition - 1; $i - $iStartPosition + 1 < $iLength * 2; $i+=2) {
					$oList->addListComponent($aStack[$i]);
				}
				array_splice($aStack, $iStartPosition - 1, $iLength * 2 - 1, array($oList));
			}
		}
		if (!isset($aStack[0])) {
			throw new UnexpectedTokenException(" {$oParserState->peek()} ", $oParserState->peek(1, -1) . $oParserState->peek(2), 'literal', $oParserState->currentLine());
		}
		return $aStack[0];
	}

	public static function parseIdentifierOrFunction(ParserState $oParserState, $bIgnoreCase = false) {
		$sResult = $oParserState->parseIdentifier($bIgnoreCase);

		if ($oParserState->comes('(')) {
			$oParserState->consume('(');
			$aArguments = Value::parseValue($oParserState, array('=', ' ', ','));
			$sResult = new CSSFunction($sResult, $aArguments, ',', $oParserState->currentLine());
			$oParserState->consume(')');
		}

		return $sResult;
	}

	public static function parsePrimitiveValue(ParserState $oParserState) {
		$oValue = null;
		$oParserState->consumeWhiteSpace();
		if (is_numeric($oParserState->peek()) || ($oParserState->comes('-.') && is_numeric($oParserState->peek(1, 2))) || (($oParserState->comes('-') || $oParserState->comes('.')) && is_numeric($oParserState->peek(1, 1)))) {
			$oValue = Size::parse($oParserState);
		} else if ($oParserState->comes('#') || $oParserState->comes('rgb', true) || $oParserState->comes('hsl', true)) {
			$oValue = Color::parse($oParserState);
		} else if ($oParserState->comes('url', true)) {
			$oValue = URL::parse($oParserState);
		} else if ($oParserState->comes('calc', true) || $oParserState->comes('-webkit-calc', true) || $oParserState->comes('-moz-calc', true)) {
			$oValue = CalcFunction::parse($oParserState);
		} else if ($oParserState->comes("'") || $oParserState->comes('"')) {
			$oValue = CSSString::parse($oParserState);
		} else if ($oParserState->comes("progid:") && $oParserState->getSettings()->bLenientParsing) {
			$oValue = self::parseMicrosoftFilter($oParserState);
		} else if ($oParserState->comes("[")) {
			$oValue = LineName::parse($oParserState);
		} else if ($oParserState->comes("U+")) {
			$oValue = self::parseUnicodeRangeValue($oParserState);
		} else {
			$oValue = self::parseIdentifierOrFunction($oParserState);
		}
		$oParserState->consumeWhiteSpace();
		return $oValue;
	}

	private static function parseMicrosoftFilter(ParserState $oParserState) {
		$sFunction = $oParserState->consumeUntil('(', false, true);
		$aArguments = Value::parseValue($oParserState, array(',', '='));
		return new CSSFunction($sFunction, $aArguments, ',', $oParserState->currentLine());
	}

	private static function parseUnicodeRangeValue(ParserState $oParserState) {
		$iCodepointMaxLenth = 6; // Code points outside BMP can use up to six digits
		$sRange = "";
		$oParserState->consume("U+");
		do {
			if ($oParserState->comes('-')) $iCodepointMaxLenth = 13; // Max length is 2 six digit code points + the dash(-) between them
			$sRange .= $oParserState->consume(1);
		} while (strlen($sRange) < $iCodepointMaxLenth && preg_match("/[A-Fa-f0-9\?-]/", $oParserState->peek()));
		return "U+{$sRange}";
	}
	
	/**
	 * @return int
	 */
	public function getLineNo() {
		return $this->iLineNo;
	}

	//Methods are commented out because re-declaring them here is a fatal error in PHP < 5.3.9
	//public abstract function __toString();
	//public abstract function render(\Sabberworm\CSS\OutputFormat $oOutputFormat);
}
