<?php

namespace Sabberworm\CSS\Value;

use Sabberworm\CSS\Parsing\ParserState;
use Sabberworm\CSS\Parsing\UnexpectedTokenException;

class CalcFunction extends CSSFunction {
	const T_OPERAND  = 1;
	const T_OPERATOR = 2;

	public static function parse(ParserState $oParserState) {
		$aOperators = array('+', '-', '*', '/');
		$sFunction = trim($oParserState->consumeUntil('(', false, true));
		$oCalcList = new CalcRuleValueList($oParserState->currentLine());
		$oList = new RuleValueList(',', $oParserState->currentLine());
		$iNestingLevel = 0;
		$iLastComponentType = NULL;
		while(!$oParserState->comes(')') || $iNestingLevel > 0) {
			$oParserState->consumeWhiteSpace();
			if ($oParserState->comes('(')) {
				$iNestingLevel++;
				$oCalcList->addListComponent($oParserState->consume(1));
				continue;
			} else if ($oParserState->comes(')')) {
				$iNestingLevel--;
				$oCalcList->addListComponent($oParserState->consume(1));
				continue;
			}
			if ($iLastComponentType != CalcFunction::T_OPERAND) {
				$oVal = Value::parsePrimitiveValue($oParserState);
				$oCalcList->addListComponent($oVal);
				$iLastComponentType = CalcFunction::T_OPERAND;
			} else {
				if (in_array($oParserState->peek(), $aOperators)) {
					if (($oParserState->comes('-') || $oParserState->comes('+'))) {
						if ($oParserState->peek(1, -1) != ' ' || !($oParserState->comes('- ') || $oParserState->comes('+ '))) {
							throw new UnexpectedTokenException(" {$oParserState->peek()} ", $oParserState->peek(1, -1) . $oParserState->peek(2), 'literal', $oParserState->currentLine());
						}
					}
					$oCalcList->addListComponent($oParserState->consume(1));
					$iLastComponentType = CalcFunction::T_OPERATOR;
				} else {
					throw new UnexpectedTokenException(
						sprintf(
							'Next token was expected to be an operand of type %s. Instead "%s" was found.',
							implode(', ', $aOperators),
							$oVal
						),
						'',
						'custom',
						$oParserState->currentLine()
					);
				}
			}
		}
		$oList->addListComponent($oCalcList);
		$oParserState->consume(')');
		return new CalcFunction($sFunction, $oList, ',', $oParserState->currentLine());
	}

}
