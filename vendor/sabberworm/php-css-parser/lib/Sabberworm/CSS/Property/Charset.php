<?php

namespace Sabberworm\CSS\Property;

/**
 * Class representing an @charset rule.
 * The following restrictions apply:
 * • May not be found in any CSSList other than the Document.
 * • May only appear at the very top of a Document’s contents.
 * • Must not appear more than once.
 */
class Charset implements AtRule {

	private $sCharset;
	protected $iLineNo;
	protected $aComment;

	public function __construct($sCharset, $iLineNo = 0) {
		$this->sCharset = $sCharset;
		$this->iLineNo = $iLineNo;
		$this->aComments = array();
	}

	/**
	 * @return int
	 */
	public function getLineNo() {
		return $this->iLineNo;
	}

	public function setCharset($sCharset) {
		$this->sCharset = $sCharset;
	}

	public function getCharset() {
		return $this->sCharset;
	}

	public function __toString() {
		return $this->render(new \Sabberworm\CSS\OutputFormat());
	}

	public function render(\Sabberworm\CSS\OutputFormat $oOutputFormat) {
		return "@charset {$this->sCharset->render($oOutputFormat)};";
	}

	public function atRuleName() {
		return 'charset';
	}

	public function atRuleArgs() {
		return $this->sCharset;
	}

	public function addComments(array $aComments) {
		$this->aComments = array_merge($this->aComments, $aComments);
	}

	public function getComments() {
		return $this->aComments;
	}

	public function setComments(array $aComments) {
		$this->aComments = $aComments;
	}
}