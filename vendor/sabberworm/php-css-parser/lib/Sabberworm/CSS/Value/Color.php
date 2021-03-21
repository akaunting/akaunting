<?php

namespace Sabberworm\CSS\Value;

use Sabberworm\CSS\Parsing\ParserState;

class Color extends CSSFunction {

	public function __construct($aColor, $iLineNo = 0) {
		parent::__construct(implode('', array_keys($aColor)), $aColor, ',', $iLineNo);
	}

	public static function parse(ParserState $oParserState) {
		$aColor = array();
		if ($oParserState->comes('#')) {
			$oParserState->consume('#');
			$sValue = $oParserState->parseIdentifier(false);
			if ($oParserState->strlen($sValue) === 3) {
				$sValue = $sValue[0] . $sValue[0] . $sValue[1] . $sValue[1] . $sValue[2] . $sValue[2];
			} else if ($oParserState->strlen($sValue) === 4) {
				$sValue = $sValue[0] . $sValue[0] . $sValue[1] . $sValue[1] . $sValue[2] . $sValue[2] . $sValue[3] . $sValue[3];
			}

			if ($oParserState->strlen($sValue) === 8) {
				$aColor = array(
					'r' => new Size(intval($sValue[0] . $sValue[1], 16), null, true, $oParserState->currentLine()),
					'g' => new Size(intval($sValue[2] . $sValue[3], 16), null, true, $oParserState->currentLine()),
					'b' => new Size(intval($sValue[4] . $sValue[5], 16), null, true, $oParserState->currentLine()),
					'a' => new Size(round(self::mapRange(intval($sValue[6] . $sValue[7], 16), 0, 255, 0, 1), 2), null, true, $oParserState->currentLine())
				);
			} else {
				$aColor = array(
					'r' => new Size(intval($sValue[0] . $sValue[1], 16), null, true, $oParserState->currentLine()),
					'g' => new Size(intval($sValue[2] . $sValue[3], 16), null, true, $oParserState->currentLine()),
					'b' => new Size(intval($sValue[4] . $sValue[5], 16), null, true, $oParserState->currentLine())
				);
			}
		} else {
			$sColorMode = $oParserState->parseIdentifier(true);
			$oParserState->consumeWhiteSpace();
			$oParserState->consume('(');
			$iLength = $oParserState->strlen($sColorMode);
			for ($i = 0; $i < $iLength; ++$i) {
				$oParserState->consumeWhiteSpace();
				$aColor[$sColorMode[$i]] = Size::parse($oParserState, true);
				$oParserState->consumeWhiteSpace();
				if ($i < ($iLength - 1)) {
					$oParserState->consume(',');
				}
			}
			$oParserState->consume(')');
		}
		return new Color($aColor, $oParserState->currentLine());
	}

	private static function mapRange($fVal, $fFromMin, $fFromMax, $fToMin, $fToMax) {
		$fFromRange = $fFromMax - $fFromMin;
		$fToRange = $fToMax - $fToMin;
		$fMultiplier = $fToRange / $fFromRange;
		$fNewVal = $fVal - $fFromMin;
		$fNewVal *= $fMultiplier;
		return $fNewVal + $fToMin;
	}

	public function getColor() {
		return $this->aComponents;
	}

	public function setColor($aColor) {
		$this->setName(implode('', array_keys($aColor)));
		$this->aComponents = $aColor;
	}

	public function getColorDescription() {
		return $this->getName();
	}

	public function __toString() {
		return $this->render(new \Sabberworm\CSS\OutputFormat());
	}

	public function render(\Sabberworm\CSS\OutputFormat $oOutputFormat) {
		// Shorthand RGB color values
		if($oOutputFormat->getRGBHashNotation() && implode('', array_keys($this->aComponents)) === 'rgb') {
			$sResult = sprintf(
				'%02x%02x%02x',
				$this->aComponents['r']->getSize(),
				$this->aComponents['g']->getSize(),
				$this->aComponents['b']->getSize()
			);
			return '#'.(($sResult[0] == $sResult[1]) && ($sResult[2] == $sResult[3]) && ($sResult[4] == $sResult[5]) ? "$sResult[0]$sResult[2]$sResult[4]" : $sResult);
		}
		return parent::render($oOutputFormat);
	}
}
