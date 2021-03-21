<?php

namespace Sabberworm\CSS\RuleSet;

use Sabberworm\CSS\Comment\Commentable;
use Sabberworm\CSS\Parsing\ParserState;
use Sabberworm\CSS\Parsing\UnexpectedTokenException;
use Sabberworm\CSS\Renderable;
use Sabberworm\CSS\Rule\Rule;

/**
 * RuleSet is a generic superclass denoting rules. The typical example for rule sets are declaration block.
 * However, unknown At-Rules (like @font-face) are also rule sets.
 */
abstract class RuleSet implements Renderable, Commentable {

	private $aRules;
	protected $iLineNo;
	protected $aComments;

	public function __construct($iLineNo = 0) {
		$this->aRules = array();
		$this->iLineNo = $iLineNo;
		$this->aComments = array();
	}

	public static function parseRuleSet(ParserState $oParserState, RuleSet $oRuleSet) {
		while ($oParserState->comes(';')) {
			$oParserState->consume(';');
		}
		while (!$oParserState->comes('}')) {
			$oRule = null;
			if($oParserState->getSettings()->bLenientParsing) {
				try {
					$oRule = Rule::parse($oParserState);
				} catch (UnexpectedTokenException $e) {
					try {
						$sConsume = $oParserState->consumeUntil(array("\n", ";", '}'), true);
						// We need to “unfind” the matches to the end of the ruleSet as this will be matched later
						if($oParserState->streql(substr($sConsume, -1), '}')) {
							$oParserState->backtrack(1);
						} else {
							while ($oParserState->comes(';')) {
								$oParserState->consume(';');
							}
						}
					} catch (UnexpectedTokenException $e) {
						// We’ve reached the end of the document. Just close the RuleSet.
						return;
					}
				}
			} else {
				$oRule = Rule::parse($oParserState);
			}
			if($oRule) {
				$oRuleSet->addRule($oRule);
			}
		}
		$oParserState->consume('}');
	}

	/**
	 * @return int
	 */
	public function getLineNo() {
		return $this->iLineNo;
	}

	public function addRule(Rule $oRule, Rule $oSibling = null) {
		$sRule = $oRule->getRule();
		if(!isset($this->aRules[$sRule])) {
			$this->aRules[$sRule] = array();
		}

		$iPosition = count($this->aRules[$sRule]);

		if ($oSibling !== null) {
			$iSiblingPos = array_search($oSibling, $this->aRules[$sRule], true);
			if ($iSiblingPos !== false) {
				$iPosition = $iSiblingPos;
			}
		}

		array_splice($this->aRules[$sRule], $iPosition, 0, array($oRule));
	}

	/**
	 * Returns all rules matching the given rule name
	 * @param (null|string|Rule) $mRule pattern to search for. If null, returns all rules. if the pattern ends with a dash, all rules starting with the pattern are returned as well as one matching the pattern with the dash excluded. passing a Rule behaves like calling getRules($mRule->getRule()).
	 * @example $oRuleSet->getRules('font-') //returns an array of all rules either beginning with font- or matching font.
	 * @example $oRuleSet->getRules('font') //returns array(0 => $oRule, …) or array().
	 * @return Rule[] Rules.
	 */
	public function getRules($mRule = null) {
		if ($mRule instanceof Rule) {
			$mRule = $mRule->getRule();
		}
		$aResult = array();
		foreach($this->aRules as $sName => $aRules) {
			// Either no search rule is given or the search rule matches the found rule exactly or the search rule ends in “-” and the found rule starts with the search rule.
			if(!$mRule || $sName === $mRule || (strrpos($mRule, '-') === strlen($mRule) - strlen('-') && (strpos($sName, $mRule) === 0 || $sName === substr($mRule, 0, -1)))) {
				$aResult = array_merge($aResult, $aRules);
			}
		}
		return $aResult;
	}

	/**
	 * Override all the rules of this set.
	 * @param Rule[] $aRules The rules to override with.
	 */
	public function setRules(array $aRules) {
		$this->aRules = array();
		foreach ($aRules as $rule) {
			$this->addRule($rule);
		}
	}

	/**
	 * Returns all rules matching the given pattern and returns them in an associative array with the rule’s name as keys. This method exists mainly for backwards-compatibility and is really only partially useful.
	 * @param (string) $mRule pattern to search for. If null, returns all rules. if the pattern ends with a dash, all rules starting with the pattern are returned as well as one matching the pattern with the dash excluded. passing a Rule behaves like calling getRules($mRule->getRule()).
	 * Note: This method loses some information: Calling this (with an argument of 'background-') on a declaration block like { background-color: green; background-color; rgba(0, 127, 0, 0.7); } will only yield an associative array containing the rgba-valued rule while @link{getRules()} would yield an indexed array containing both.
	 * @return Rule[] Rules.
	 */
	public function getRulesAssoc($mRule = null) {
		$aResult = array();
		foreach($this->getRules($mRule) as $oRule) {
			$aResult[$oRule->getRule()] = $oRule;
		}
		return $aResult;
	}

	/**
	 * Remove a rule from this RuleSet. This accepts all the possible values that @link{getRules()} accepts. If given a Rule, it will only remove this particular rule (by identity). If given a name, it will remove all rules by that name. Note: this is different from pre-v.2.0 behaviour of PHP-CSS-Parser, where passing a Rule instance would remove all rules with the same name. To get the old behvaiour, use removeRule($oRule->getRule()).
	 * @param (null|string|Rule) $mRule pattern to remove. If $mRule is null, all rules are removed. If the pattern ends in a dash, all rules starting with the pattern are removed as well as one matching the pattern with the dash excluded. Passing a Rule behaves matches by identity.
	 */
	public function removeRule($mRule) {
		if($mRule instanceof Rule) {
			$sRule = $mRule->getRule();
			if(!isset($this->aRules[$sRule])) {
				return;
			}
			foreach($this->aRules[$sRule] as $iKey => $oRule) {
				if($oRule === $mRule) {
					unset($this->aRules[$sRule][$iKey]);
				}
			}
		} else {
			foreach($this->aRules as $sName => $aRules) {
				// Either no search rule is given or the search rule matches the found rule exactly or the search rule ends in “-” and the found rule starts with the search rule or equals it (without the trailing dash).
				if(!$mRule || $sName === $mRule || (strrpos($mRule, '-') === strlen($mRule) - strlen('-') && (strpos($sName, $mRule) === 0 || $sName === substr($mRule, 0, -1)))) {
					unset($this->aRules[$sName]);
				}
			}
		}
	}

	public function __toString() {
		return $this->render(new \Sabberworm\CSS\OutputFormat());
	}

	public function render(\Sabberworm\CSS\OutputFormat $oOutputFormat) {
		$sResult = '';
		$bIsFirst = true;
		foreach ($this->aRules as $aRules) {
			foreach($aRules as $oRule) {
				$sRendered = $oOutputFormat->safely(function() use ($oRule, $oOutputFormat) {
					return $oRule->render($oOutputFormat->nextLevel());
				});
				if($sRendered === null) {
					continue;
				}
				if($bIsFirst) {
					$bIsFirst = false;
					$sResult .= $oOutputFormat->nextLevel()->spaceBeforeRules();
				} else {
					$sResult .= $oOutputFormat->nextLevel()->spaceBetweenRules();
				}
				$sResult .= $sRendered;
			}
		}
		
		if(!$bIsFirst) {
			// Had some output
			$sResult .= $oOutputFormat->spaceAfterRules();
		}

		return $oOutputFormat->removeLastSemicolon($sResult);
	}

	/**
	 * @param array $aComments Array of comments.
	 */
	public function addComments(array $aComments) {
		$this->aComments = array_merge($this->aComments, $aComments);
	}

	/**
	 * @return array
	 */
	public function getComments() {
		return $this->aComments;
	}

	/**
	 * @param array $aComments Array containing Comment objects.
	 */
	public function setComments(array $aComments) {
		$this->aComments = $aComments;
	}

}
