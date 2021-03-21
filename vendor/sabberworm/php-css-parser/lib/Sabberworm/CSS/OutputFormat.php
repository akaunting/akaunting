<?php

namespace Sabberworm\CSS;

use Sabberworm\CSS\Parsing\OutputException;

/**
 * Class OutputFormat
 *
 * @method OutputFormat setSemicolonAfterLastRule( bool $bSemicolonAfterLastRule ) Set whether semicolons are added after last rule.
 */
class OutputFormat {
	/**
	* Value format
	*/
	// " means double-quote, ' means single-quote
	public $sStringQuotingType = '"';
	// Output RGB colors in hash notation if possible
	public $bRGBHashNotation = true;
	
	/**
	* Declaration format
	*/
	// Semicolon after the last rule of a declaration block can be omitted. To do that, set this false.
	public $bSemicolonAfterLastRule = true;
	
	/**
	* Spacing
	* Note that these strings are not sanity-checked: the value should only consist of whitespace
	* Any newline character will be indented according to the current level.
	* The triples (After, Before, Between) can be set using a wildcard (e.g. `$oFormat->set('Space*Rules', "\n");`)
	*/
	public $sSpaceAfterRuleName = ' ';

	public $sSpaceBeforeRules = '';
	public $sSpaceAfterRules = '';
	public $sSpaceBetweenRules = '';

	public $sSpaceBeforeBlocks = '';
	public $sSpaceAfterBlocks = '';
	public $sSpaceBetweenBlocks = "\n";

	// Content injected in and around @-rule blocks.
	public $sBeforeAtRuleBlock = '';
	public $sAfterAtRuleBlock = '';

	// This is what’s printed before and after the comma if a declaration block contains multiple selectors.
	public $sSpaceBeforeSelectorSeparator = '';
	public $sSpaceAfterSelectorSeparator = ' ';
	// This is what’s printed after the comma of value lists
	public $sSpaceBeforeListArgumentSeparator = '';
	public $sSpaceAfterListArgumentSeparator = '';
	
	public $sSpaceBeforeOpeningBrace = ' ';

	// Content injected in and around declaration blocks.
	public $sBeforeDeclarationBlock = '';
	public $sAfterDeclarationBlockSelectors = '';
	public $sAfterDeclarationBlock = '';

	/**
	* Indentation
	*/
	// Indentation character(s) per level. Only applicable if newlines are used in any of the spacing settings.
	public $sIndentation = "\t";
	
	/**
	* Output exceptions.
	*/
	public $bIgnoreExceptions = false;
	
	
	private $oFormatter = null;
	private $oNextLevelFormat = null;
	private $iIndentationLevel = 0;
	
	public function __construct() {
	}
	
	public function get($sName) {
		$aVarPrefixes = array('a', 's', 'm', 'b', 'f', 'o', 'c', 'i');
		foreach($aVarPrefixes as $sPrefix) {
			$sFieldName = $sPrefix.ucfirst($sName);
			if(isset($this->$sFieldName)) {
				return $this->$sFieldName;
			}
		}
		return null;
	}
	
	public function set($aNames, $mValue) {
		$aVarPrefixes = array('a', 's', 'm', 'b', 'f', 'o', 'c', 'i');
		if(is_string($aNames) && strpos($aNames, '*') !== false) {
			$aNames = array(str_replace('*', 'Before', $aNames), str_replace('*', 'Between', $aNames), str_replace('*', 'After', $aNames));
		} else if(!is_array($aNames)) {
			$aNames = array($aNames);
		}
		foreach($aVarPrefixes as $sPrefix) {
			$bDidReplace = false;
			foreach($aNames as $sName) {
				$sFieldName = $sPrefix.ucfirst($sName);
				if(isset($this->$sFieldName)) {
					$this->$sFieldName = $mValue;
					$bDidReplace = true;
				}
			}
			if($bDidReplace) {
				return $this;
			}
		}
		// Break the chain so the user knows this option is invalid
		return false;
	}
	
	public function __call($sMethodName, $aArguments) {
		if(strpos($sMethodName, 'set') === 0) {
			return $this->set(substr($sMethodName, 3), $aArguments[0]);
		} else if(strpos($sMethodName, 'get') === 0) {
			return $this->get(substr($sMethodName, 3));
		} else if(method_exists('\\Sabberworm\\CSS\\OutputFormatter', $sMethodName)) {
			return call_user_func_array(array($this->getFormatter(), $sMethodName), $aArguments);
		} else {
			throw new \Exception('Unknown OutputFormat method called: '.$sMethodName);
		}
	}
	
	public function indentWithTabs($iNumber = 1) {
		return $this->setIndentation(str_repeat("\t", $iNumber));
	}
	
	public function indentWithSpaces($iNumber = 2) {
		return $this->setIndentation(str_repeat(" ", $iNumber));
	}
	
	public function nextLevel() {
		if($this->oNextLevelFormat === null) {
			$this->oNextLevelFormat = clone $this;
			$this->oNextLevelFormat->iIndentationLevel++;
			$this->oNextLevelFormat->oFormatter = null;
		}
		return $this->oNextLevelFormat;
	}
	
	public function beLenient() {
		$this->bIgnoreExceptions = true;
	}
	
	public function getFormatter() {
		if($this->oFormatter === null) {
			$this->oFormatter = new OutputFormatter($this);
		}
		return $this->oFormatter;
	}
	
	public function level() {
		return $this->iIndentationLevel;
	}

	/**
	 * Create format.
	 *
	 * @return OutputFormat Format.
	 */
	public static function create() {
		return new OutputFormat();
	}

	/**
	 * Create compact format.
	 *
	 * @return OutputFormat Format.
	 */
	public static function createCompact() {
		$format = self::create();
		$format->set('Space*Rules', "")->set('Space*Blocks', "")->setSpaceAfterRuleName('')->setSpaceBeforeOpeningBrace('')->setSpaceAfterSelectorSeparator('');
		return $format;
	}

	/**
	 * Create pretty format.
	 *
	 * @return OutputFormat Format.
	 */
	public static function createPretty() {
		$format = self::create();
		$format->set('Space*Rules', "\n")->set('Space*Blocks', "\n")->setSpaceBetweenBlocks("\n\n")->set('SpaceAfterListArgumentSeparator', array('default' => '', ',' => ' '));
		return $format;
	}
}

class OutputFormatter {
	private $oFormat;
	
	public function __construct(OutputFormat $oFormat) {
		$this->oFormat = $oFormat;
	}
	
	public function space($sName, $sType = null) {
		$sSpaceString = $this->oFormat->get("Space$sName");
		// If $sSpaceString is an array, we have multple values configured depending on the type of object the space applies to
		if(is_array($sSpaceString)) {
			if($sType !== null && isset($sSpaceString[$sType])) {
				$sSpaceString = $sSpaceString[$sType];
			} else {
				$sSpaceString = reset($sSpaceString);
			}
		}
		return $this->prepareSpace($sSpaceString);
	}
	
	public function spaceAfterRuleName() {
		return $this->space('AfterRuleName');
	}
	
	public function spaceBeforeRules() {
		return $this->space('BeforeRules');
	}
	
	public function spaceAfterRules() {
		return $this->space('AfterRules');
	}
	
	public function spaceBetweenRules() {
		return $this->space('BetweenRules');
	}
	
	public function spaceBeforeBlocks() {
		return $this->space('BeforeBlocks');
	}
	
	public function spaceAfterBlocks() {
		return $this->space('AfterBlocks');
	}
	
	public function spaceBetweenBlocks() {
		return $this->space('BetweenBlocks');
	}
	
	public function spaceBeforeSelectorSeparator() {
		return $this->space('BeforeSelectorSeparator');
	}

	public function spaceAfterSelectorSeparator() {
		return $this->space('AfterSelectorSeparator');
	}

	public function spaceBeforeListArgumentSeparator($sSeparator) {
		return $this->space('BeforeListArgumentSeparator', $sSeparator);
	}

	public function spaceAfterListArgumentSeparator($sSeparator) {
		return $this->space('AfterListArgumentSeparator', $sSeparator);
	}

	public function spaceBeforeOpeningBrace() {
		return $this->space('BeforeOpeningBrace');
	}

	/**
	* Runs the given code, either swallowing or passing exceptions, depending on the bIgnoreExceptions setting.
	*/
	public function safely($cCode) {
		if($this->oFormat->get('IgnoreExceptions')) {
			// If output exceptions are ignored, run the code with exception guards
			try {
				return $cCode();
			} catch (OutputException $e) {
				return null;
			} //Do nothing
		} else {
			// Run the code as-is
			return $cCode();
		}
	}

	/**
	* Clone of the implode function but calls ->render with the current output format instead of __toString()
	*/
	public function implode($sSeparator, $aValues, $bIncreaseLevel = false) {
		$sResult = '';
		$oFormat = $this->oFormat;
		if($bIncreaseLevel) {
			$oFormat = $oFormat->nextLevel();
		}
		$bIsFirst = true;
		foreach($aValues as $mValue) {
			if($bIsFirst) {
				$bIsFirst = false;
			} else {
				$sResult .= $sSeparator;
			}
			if($mValue instanceof \Sabberworm\CSS\Renderable) {
				$sResult .= $mValue->render($oFormat);
			} else {
				$sResult .= $mValue;
			}
		}
		return $sResult;
	}
	
	public function removeLastSemicolon($sString) {
		if($this->oFormat->get('SemicolonAfterLastRule')) {
			return $sString;
		}
		$sString = explode(';', $sString);
		if(count($sString) < 2) {
			return $sString[0];
		}
		$sLast = array_pop($sString);
		$sNextToLast = array_pop($sString);
		array_push($sString, $sNextToLast.$sLast);
		return implode(';', $sString);
	}

	private function prepareSpace($sSpaceString) {
		return str_replace("\n", "\n".$this->indent(), $sSpaceString);
	}

	private function indent() {
		return str_repeat($this->oFormat->sIndentation, $this->oFormat->level());
	}
}
