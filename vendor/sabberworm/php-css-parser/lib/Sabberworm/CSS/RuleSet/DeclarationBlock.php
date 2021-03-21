<?php

namespace Sabberworm\CSS\RuleSet;

use Sabberworm\CSS\Parsing\ParserState;
use Sabberworm\CSS\Parsing\OutputException;
use Sabberworm\CSS\Property\Selector;
use Sabberworm\CSS\Rule\Rule;
use Sabberworm\CSS\Value\RuleValueList;
use Sabberworm\CSS\Value\Value;
use Sabberworm\CSS\Value\Size;
use Sabberworm\CSS\Value\Color;
use Sabberworm\CSS\Value\URL;

/**
 * Declaration blocks are the parts of a css file which denote the rules belonging to a selector.
 * Declaration blocks usually appear directly inside a Document or another CSSList (mostly a MediaQuery).
 */
class DeclarationBlock extends RuleSet {

	private $aSelectors;

	public function __construct($iLineNo = 0) {
		parent::__construct($iLineNo);
		$this->aSelectors = array();
	}

	public static function parse(ParserState $oParserState) {
		$aComments = array();
		$oResult = new DeclarationBlock($oParserState->currentLine());
		$oResult->setSelector($oParserState->consumeUntil('{', false, true, $aComments));
		$oResult->setComments($aComments);
		RuleSet::parseRuleSet($oParserState, $oResult);
		return $oResult;
	}


	public function setSelectors($mSelector) {
		if (is_array($mSelector)) {
			$this->aSelectors = $mSelector;
		} else {
			$this->aSelectors = explode(',', $mSelector);
		}
		foreach ($this->aSelectors as $iKey => $mSelector) {
			if (!($mSelector instanceof Selector)) {
				$this->aSelectors[$iKey] = new Selector($mSelector);
			}
		}
	}

	// remove one of the selector of the block
	public function removeSelector($mSelector) {
		if($mSelector instanceof Selector) {
			$mSelector = $mSelector->getSelector();
		}
		foreach($this->aSelectors as $iKey => $oSelector) {
			if($oSelector->getSelector() === $mSelector) {
				unset($this->aSelectors[$iKey]);
				return true;
			}
		}
		return false;
	}

	/**
	 * @deprecated use getSelectors()
	 */
	public function getSelector() {
		return $this->getSelectors();
	}

	/**
	 * @deprecated use setSelectors()
	 */
	public function setSelector($mSelector) {
		$this->setSelectors($mSelector);
	}

	/**
	 * Get selectors.
	 *
	 * @return Selector[] Selectors.
	 */
	public function getSelectors() {
		return $this->aSelectors;
	}

	/**
	 * Split shorthand declarations (e.g. +margin+ or +font+) into their constituent parts.
	 * */
	public function expandShorthands() {
		// border must be expanded before dimensions
		$this->expandBorderShorthand();
		$this->expandDimensionsShorthand();
		$this->expandFontShorthand();
		$this->expandBackgroundShorthand();
		$this->expandListStyleShorthand();
	}

	/**
	 * Create shorthand declarations (e.g. +margin+ or +font+) whenever possible.
	 * */
	public function createShorthands() {
		$this->createBackgroundShorthand();
		$this->createDimensionsShorthand();
		// border must be shortened after dimensions 
		$this->createBorderShorthand();
		$this->createFontShorthand();
		$this->createListStyleShorthand();
	}

	/**
	 * Split shorthand border declarations (e.g. <tt>border: 1px red;</tt>)
	 * Additional splitting happens in expandDimensionsShorthand
	 * Multiple borders are not yet supported as of 3
	 * */
	public function expandBorderShorthand() {
		$aBorderRules = array(
			'border', 'border-left', 'border-right', 'border-top', 'border-bottom'
		);
		$aBorderSizes = array(
			'thin', 'medium', 'thick'
		);
		$aRules = $this->getRulesAssoc();
		foreach ($aBorderRules as $sBorderRule) {
			if (!isset($aRules[$sBorderRule]))
				continue;
			$oRule = $aRules[$sBorderRule];
			$mRuleValue = $oRule->getValue();
			$aValues = array();
			if (!$mRuleValue instanceof RuleValueList) {
				$aValues[] = $mRuleValue;
			} else {
				$aValues = $mRuleValue->getListComponents();
			}
			foreach ($aValues as $mValue) {
				if ($mValue instanceof Value) {
					$mNewValue = clone $mValue;
				} else {
					$mNewValue = $mValue;
				}
				if ($mValue instanceof Size) {
					$sNewRuleName = $sBorderRule . "-width";
				} else if ($mValue instanceof Color) {
					$sNewRuleName = $sBorderRule . "-color";
				} else {
					if (in_array($mValue, $aBorderSizes)) {
						$sNewRuleName = $sBorderRule . "-width";
					} else/* if(in_array($mValue, $aBorderStyles)) */ {
						$sNewRuleName = $sBorderRule . "-style";
					}
				}
				$oNewRule = new Rule($sNewRuleName, $this->iLineNo);
				$oNewRule->setIsImportant($oRule->getIsImportant());
				$oNewRule->addValue(array($mNewValue));
				$this->addRule($oNewRule);
			}
			$this->removeRule($sBorderRule);
		}
	}

	/**
	 * Split shorthand dimensional declarations (e.g. <tt>margin: 0px auto;</tt>)
	 * into their constituent parts.
	 * Handles margin, padding, border-color, border-style and border-width.
	 * */
	public function expandDimensionsShorthand() {
		$aExpansions = array(
			'margin' => 'margin-%s',
			'padding' => 'padding-%s',
			'border-color' => 'border-%s-color',
			'border-style' => 'border-%s-style',
			'border-width' => 'border-%s-width'
		);
		$aRules = $this->getRulesAssoc();
		foreach ($aExpansions as $sProperty => $sExpanded) {
			if (!isset($aRules[$sProperty]))
				continue;
			$oRule = $aRules[$sProperty];
			$mRuleValue = $oRule->getValue();
			$aValues = array();
			if (!$mRuleValue instanceof RuleValueList) {
				$aValues[] = $mRuleValue;
			} else {
				$aValues = $mRuleValue->getListComponents();
			}
			$top = $right = $bottom = $left = null;
			switch (count($aValues)) {
				case 1:
					$top = $right = $bottom = $left = $aValues[0];
					break;
				case 2:
					$top = $bottom = $aValues[0];
					$left = $right = $aValues[1];
					break;
				case 3:
					$top = $aValues[0];
					$left = $right = $aValues[1];
					$bottom = $aValues[2];
					break;
				case 4:
					$top = $aValues[0];
					$right = $aValues[1];
					$bottom = $aValues[2];
					$left = $aValues[3];
					break;
			}
			foreach (array('top', 'right', 'bottom', 'left') as $sPosition) {
				$oNewRule = new Rule(sprintf($sExpanded, $sPosition), $this->iLineNo);
				$oNewRule->setIsImportant($oRule->getIsImportant());
				$oNewRule->addValue(${$sPosition});
				$this->addRule($oNewRule);
			}
			$this->removeRule($sProperty);
		}
	}

	/**
	 * Convert shorthand font declarations
	 * (e.g. <tt>font: 300 italic 11px/14px verdana, helvetica, sans-serif;</tt>)
	 * into their constituent parts.
	 * */
	public function expandFontShorthand() {
		$aRules = $this->getRulesAssoc();
		if (!isset($aRules['font']))
			return;
		$oRule = $aRules['font'];
		// reset properties to 'normal' per http://www.w3.org/TR/21/fonts.html#font-shorthand
		$aFontProperties = array(
			'font-style' => 'normal',
			'font-variant' => 'normal',
			'font-weight' => 'normal',
			'font-size' => 'normal',
			'line-height' => 'normal'
		);
		$mRuleValue = $oRule->getValue();
		$aValues = array();
		if (!$mRuleValue instanceof RuleValueList) {
			$aValues[] = $mRuleValue;
		} else {
			$aValues = $mRuleValue->getListComponents();
		}
		foreach ($aValues as $mValue) {
			if (!$mValue instanceof Value) {
				$mValue = mb_strtolower($mValue);
			}
			if (in_array($mValue, array('normal', 'inherit'))) {
				foreach (array('font-style', 'font-weight', 'font-variant') as $sProperty) {
					if (!isset($aFontProperties[$sProperty])) {
						$aFontProperties[$sProperty] = $mValue;
					}
				}
			} else if (in_array($mValue, array('italic', 'oblique'))) {
				$aFontProperties['font-style'] = $mValue;
			} else if ($mValue == 'small-caps') {
				$aFontProperties['font-variant'] = $mValue;
			} else if (
					in_array($mValue, array('bold', 'bolder', 'lighter'))
					|| ($mValue instanceof Size
					&& in_array($mValue->getSize(), range(100, 900, 100)))
			) {
				$aFontProperties['font-weight'] = $mValue;
			} else if ($mValue instanceof RuleValueList && $mValue->getListSeparator() == '/') {
				list($oSize, $oHeight) = $mValue->getListComponents();
				$aFontProperties['font-size'] = $oSize;
				$aFontProperties['line-height'] = $oHeight;
			} else if ($mValue instanceof Size && $mValue->getUnit() !== null) {
				$aFontProperties['font-size'] = $mValue;
			} else {
				$aFontProperties['font-family'] = $mValue;
			}
		}
		foreach ($aFontProperties as $sProperty => $mValue) {
			$oNewRule = new Rule($sProperty, $this->iLineNo);
			$oNewRule->addValue($mValue);
			$oNewRule->setIsImportant($oRule->getIsImportant());
			$this->addRule($oNewRule);
		}
		$this->removeRule('font');
	}

	/*
	 * Convert shorthand background declarations
	 * (e.g. <tt>background: url("chess.png") gray 50% repeat fixed;</tt>)
	 * into their constituent parts.
	 * @see http://www.w3.org/TR/21/colors.html#propdef-background
	 * */

	public function expandBackgroundShorthand() {
		$aRules = $this->getRulesAssoc();
		if (!isset($aRules['background']))
			return;
		$oRule = $aRules['background'];
		$aBgProperties = array(
			'background-color' => array('transparent'), 'background-image' => array('none'),
			'background-repeat' => array('repeat'), 'background-attachment' => array('scroll'),
			'background-position' => array(new Size(0, '%', null, false, $this->iLineNo), new Size(0, '%', null, false, $this->iLineNo))
		);
		$mRuleValue = $oRule->getValue();
		$aValues = array();
		if (!$mRuleValue instanceof RuleValueList) {
			$aValues[] = $mRuleValue;
		} else {
			$aValues = $mRuleValue->getListComponents();
		}
		if (count($aValues) == 1 && $aValues[0] == 'inherit') {
			foreach ($aBgProperties as $sProperty => $mValue) {
				$oNewRule = new Rule($sProperty, $this->iLineNo);
				$oNewRule->addValue('inherit');
				$oNewRule->setIsImportant($oRule->getIsImportant());
				$this->addRule($oNewRule);
			}
			$this->removeRule('background');
			return;
		}
		$iNumBgPos = 0;
		foreach ($aValues as $mValue) {
			if (!$mValue instanceof Value) {
				$mValue = mb_strtolower($mValue);
			}
			if ($mValue instanceof URL) {
				$aBgProperties['background-image'] = $mValue;
			} else if ($mValue instanceof Color) {
				$aBgProperties['background-color'] = $mValue;
			} else if (in_array($mValue, array('scroll', 'fixed'))) {
				$aBgProperties['background-attachment'] = $mValue;
			} else if (in_array($mValue, array('repeat', 'no-repeat', 'repeat-x', 'repeat-y'))) {
				$aBgProperties['background-repeat'] = $mValue;
			} else if (in_array($mValue, array('left', 'center', 'right', 'top', 'bottom'))
					|| $mValue instanceof Size
			) {
				if ($iNumBgPos == 0) {
					$aBgProperties['background-position'][0] = $mValue;
					$aBgProperties['background-position'][1] = 'center';
				} else {
					$aBgProperties['background-position'][$iNumBgPos] = $mValue;
				}
				$iNumBgPos++;
			}
		}
		foreach ($aBgProperties as $sProperty => $mValue) {
			$oNewRule = new Rule($sProperty, $this->iLineNo);
			$oNewRule->setIsImportant($oRule->getIsImportant());
			$oNewRule->addValue($mValue);
			$this->addRule($oNewRule);
		}
		$this->removeRule('background');
	}

	public function expandListStyleShorthand() {
		$aListProperties = array(
			'list-style-type' => 'disc',
			'list-style-position' => 'outside',
			'list-style-image' => 'none'
		);
		$aListStyleTypes = array(
			'none', 'disc', 'circle', 'square', 'decimal-leading-zero', 'decimal',
			'lower-roman', 'upper-roman', 'lower-greek', 'lower-alpha', 'lower-latin',
			'upper-alpha', 'upper-latin', 'hebrew', 'armenian', 'georgian', 'cjk-ideographic',
			'hiragana', 'hira-gana-iroha', 'katakana-iroha', 'katakana'
		);
		$aListStylePositions = array(
			'inside', 'outside'
		);
		$aRules = $this->getRulesAssoc();
		if (!isset($aRules['list-style']))
			return;
		$oRule = $aRules['list-style'];
		$mRuleValue = $oRule->getValue();
		$aValues = array();
		if (!$mRuleValue instanceof RuleValueList) {
			$aValues[] = $mRuleValue;
		} else {
			$aValues = $mRuleValue->getListComponents();
		}
		if (count($aValues) == 1 && $aValues[0] == 'inherit') {
			foreach ($aListProperties as $sProperty => $mValue) {
				$oNewRule = new Rule($sProperty, $this->iLineNo);
				$oNewRule->addValue('inherit');
				$oNewRule->setIsImportant($oRule->getIsImportant());
				$this->addRule($oNewRule);
			}
			$this->removeRule('list-style');
			return;
		}
		foreach ($aValues as $mValue) {
			if (!$mValue instanceof Value) {
				$mValue = mb_strtolower($mValue);
			}
			if ($mValue instanceof Url) {
				$aListProperties['list-style-image'] = $mValue;
			} else if (in_array($mValue, $aListStyleTypes)) {
				$aListProperties['list-style-types'] = $mValue;
			} else if (in_array($mValue, $aListStylePositions)) {
				$aListProperties['list-style-position'] = $mValue;
			}
		}
		foreach ($aListProperties as $sProperty => $mValue) {
			$oNewRule = new Rule($sProperty, $this->iLineNo);
			$oNewRule->setIsImportant($oRule->getIsImportant());
			$oNewRule->addValue($mValue);
			$this->addRule($oNewRule);
		}
		$this->removeRule('list-style');
	}

	public function createShorthandProperties(array $aProperties, $sShorthand) {
		$aRules = $this->getRulesAssoc();
		$aNewValues = array();
		foreach ($aProperties as $sProperty) {
			if (!isset($aRules[$sProperty]))
				continue;
			$oRule = $aRules[$sProperty];
			if (!$oRule->getIsImportant()) {
				$mRuleValue = $oRule->getValue();
				$aValues = array();
				if (!$mRuleValue instanceof RuleValueList) {
					$aValues[] = $mRuleValue;
				} else {
					$aValues = $mRuleValue->getListComponents();
				}
				foreach ($aValues as $mValue) {
					$aNewValues[] = $mValue;
				}
				$this->removeRule($sProperty);
			}
		}
		if (count($aNewValues)) {
			$oNewRule = new Rule($sShorthand, $this->iLineNo);
			foreach ($aNewValues as $mValue) {
				$oNewRule->addValue($mValue);
			}
			$this->addRule($oNewRule);
		}
	}

	public function createBackgroundShorthand() {
		$aProperties = array(
			'background-color', 'background-image', 'background-repeat',
			'background-position', 'background-attachment'
		);
		$this->createShorthandProperties($aProperties, 'background');
	}

	public function createListStyleShorthand() {
		$aProperties = array(
			'list-style-type', 'list-style-position', 'list-style-image'
		);
		$this->createShorthandProperties($aProperties, 'list-style');
	}

	/**
	 * Combine border-color, border-style and border-width into border
	 * Should be run after create_dimensions_shorthand!
	 * */
	public function createBorderShorthand() {
		$aProperties = array(
			'border-width', 'border-style', 'border-color'
		);
		$this->createShorthandProperties($aProperties, 'border');
	}

	/*
	 * Looks for long format CSS dimensional properties
	 * (margin, padding, border-color, border-style and border-width) 
	 * and converts them into shorthand CSS properties.
	 * */

	public function createDimensionsShorthand() {
		$aPositions = array('top', 'right', 'bottom', 'left');
		$aExpansions = array(
			'margin' => 'margin-%s',
			'padding' => 'padding-%s',
			'border-color' => 'border-%s-color',
			'border-style' => 'border-%s-style',
			'border-width' => 'border-%s-width'
		);
		$aRules = $this->getRulesAssoc();
		foreach ($aExpansions as $sProperty => $sExpanded) {
			$aFoldable = array();
			foreach ($aRules as $sRuleName => $oRule) {
				foreach ($aPositions as $sPosition) {
					if ($sRuleName == sprintf($sExpanded, $sPosition)) {
						$aFoldable[$sRuleName] = $oRule;
					}
				}
			}
			// All four dimensions must be present
			if (count($aFoldable) == 4) {
				$aValues = array();
				foreach ($aPositions as $sPosition) {
					$oRule = $aRules[sprintf($sExpanded, $sPosition)];
					$mRuleValue = $oRule->getValue();
					$aRuleValues = array();
					if (!$mRuleValue instanceof RuleValueList) {
						$aRuleValues[] = $mRuleValue;
					} else {
						$aRuleValues = $mRuleValue->getListComponents();
					}
					$aValues[$sPosition] = $aRuleValues;
				}
				$oNewRule = new Rule($sProperty, $this->iLineNo);
				if ((string) $aValues['left'][0] == (string) $aValues['right'][0]) {
					if ((string) $aValues['top'][0] == (string) $aValues['bottom'][0]) {
						if ((string) $aValues['top'][0] == (string) $aValues['left'][0]) {
							// All 4 sides are equal
							$oNewRule->addValue($aValues['top']);
						} else {
							// Top and bottom are equal, left and right are equal
							$oNewRule->addValue($aValues['top']);
							$oNewRule->addValue($aValues['left']);
						}
					} else {
						// Only left and right are equal
						$oNewRule->addValue($aValues['top']);
						$oNewRule->addValue($aValues['left']);
						$oNewRule->addValue($aValues['bottom']);
					}
				} else {
					// No sides are equal 
					$oNewRule->addValue($aValues['top']);
					$oNewRule->addValue($aValues['left']);
					$oNewRule->addValue($aValues['bottom']);
					$oNewRule->addValue($aValues['right']);
				}
				$this->addRule($oNewRule);
				foreach ($aPositions as $sPosition) {
					$this->removeRule(sprintf($sExpanded, $sPosition));
				}
			}
		}
	}

	/**
	 * Looks for long format CSS font properties (e.g. <tt>font-weight</tt>) and 
	 * tries to convert them into a shorthand CSS <tt>font</tt> property. 
	 * At least font-size AND font-family must be present in order to create a shorthand declaration.
	 * */
	public function createFontShorthand() {
		$aFontProperties = array(
			'font-style', 'font-variant', 'font-weight', 'font-size', 'line-height', 'font-family'
		);
		$aRules = $this->getRulesAssoc();
		if (!isset($aRules['font-size']) || !isset($aRules['font-family'])) {
			return;
		}
		$oNewRule = new Rule('font', $this->iLineNo);
		foreach (array('font-style', 'font-variant', 'font-weight') as $sProperty) {
			if (isset($aRules[$sProperty])) {
				$oRule = $aRules[$sProperty];
				$mRuleValue = $oRule->getValue();
				$aValues = array();
				if (!$mRuleValue instanceof RuleValueList) {
					$aValues[] = $mRuleValue;
				} else {
					$aValues = $mRuleValue->getListComponents();
				}
				if ($aValues[0] !== 'normal') {
					$oNewRule->addValue($aValues[0]);
				}
			}
		}
		// Get the font-size value
		$oRule = $aRules['font-size'];
		$mRuleValue = $oRule->getValue();
		$aFSValues = array();
		if (!$mRuleValue instanceof RuleValueList) {
			$aFSValues[] = $mRuleValue;
		} else {
			$aFSValues = $mRuleValue->getListComponents();
		}
		// But wait to know if we have line-height to add it
		if (isset($aRules['line-height'])) {
			$oRule = $aRules['line-height'];
			$mRuleValue = $oRule->getValue();
			$aLHValues = array();
			if (!$mRuleValue instanceof RuleValueList) {
				$aLHValues[] = $mRuleValue;
			} else {
				$aLHValues = $mRuleValue->getListComponents();
			}
			if ($aLHValues[0] !== 'normal') {
				$val = new RuleValueList('/', $this->iLineNo);
				$val->addListComponent($aFSValues[0]);
				$val->addListComponent($aLHValues[0]);
				$oNewRule->addValue($val);
			}
		} else {
			$oNewRule->addValue($aFSValues[0]);
		}
		$oRule = $aRules['font-family'];
		$mRuleValue = $oRule->getValue();
		$aFFValues = array();
		if (!$mRuleValue instanceof RuleValueList) {
			$aFFValues[] = $mRuleValue;
		} else {
			$aFFValues = $mRuleValue->getListComponents();
		}
		$oFFValue = new RuleValueList(',', $this->iLineNo);
		$oFFValue->setListComponents($aFFValues);
		$oNewRule->addValue($oFFValue);

		$this->addRule($oNewRule);
		foreach ($aFontProperties as $sProperty) {
			$this->removeRule($sProperty);
		}
	}

	public function __toString() {
		return $this->render(new \Sabberworm\CSS\OutputFormat());
	}

	public function render(\Sabberworm\CSS\OutputFormat $oOutputFormat) {
		if(count($this->aSelectors) === 0) {
			// If all the selectors have been removed, this declaration block becomes invalid
			throw new OutputException("Attempt to print declaration block with missing selector", $this->iLineNo);
		}
		$sResult = $oOutputFormat->sBeforeDeclarationBlock;
		$sResult .= $oOutputFormat->implode($oOutputFormat->spaceBeforeSelectorSeparator() . ',' . $oOutputFormat->spaceAfterSelectorSeparator(), $this->aSelectors);
		$sResult .= $oOutputFormat->sAfterDeclarationBlockSelectors;
		$sResult .= $oOutputFormat->spaceBeforeOpeningBrace() . '{';
		$sResult .= parent::render($oOutputFormat);
		$sResult .= '}';
		$sResult .= $oOutputFormat->sAfterDeclarationBlock;
		return $sResult;
	}

}
