<?php

namespace Sabberworm\CSS;

use Sabberworm\CSS\Rule\Rule;

/**
 * Parser settings class.
 *
 * Configure parser behaviour here.
 */
class Settings {
	/**
	* Multi-byte string support. If true (mbstring extension must be enabled), will use (slower) mb_strlen, mb_convert_case, mb_substr and mb_strpos functions. Otherwise, the normal (ASCII-Only) functions will be used.
	*/
	public $bMultibyteSupport;

	/**
	* The default charset for the CSS if no `@charset` rule is found. Defaults to utf-8.
	*/
	public $sDefaultCharset = 'utf-8';

	/**
	* Lenient parsing. When used (which is true by default), the parser will not choke on unexpected tokens but simply ignore them.
	*/
	public $bLenientParsing = true;

	private function __construct() {
		$this->bMultibyteSupport = extension_loaded('mbstring');
	}

	public static function create() {
		return new Settings();
	}
	
	public function withMultibyteSupport($bMultibyteSupport = true) {
		$this->bMultibyteSupport = $bMultibyteSupport;
		return $this;
	}
	
	public function withDefaultCharset($sDefaultCharset) {
		$this->sDefaultCharset = $sDefaultCharset;
		return $this;
	}
	
	public function withLenientParsing($bLenientParsing = true) {
		$this->bLenientParsing = $bLenientParsing;
		return $this;
	}
	
	public function beStrict() {
		return $this->withLenientParsing(false);
	}
}