<?php
namespace Sabberworm\CSS\Parsing;

use Sabberworm\CSS\Comment\Comment;
use Sabberworm\CSS\Parsing\UnexpectedTokenException;
use Sabberworm\CSS\Settings;

class ParserState {
	private $oParserSettings;

	private $sText;

	private $aText;
	private $iCurrentPosition;
	private $sCharset;
	private $iLength;
	private $iLineNo;

	public function __construct($sText, Settings $oParserSettings, $iLineNo = 1) {
		$this->oParserSettings = $oParserSettings;
		$this->sText = $sText;
		$this->iCurrentPosition = 0;
		$this->iLineNo = $iLineNo;
		$this->setCharset($this->oParserSettings->sDefaultCharset);
	}

	public function setCharset($sCharset) {
		$this->sCharset = $sCharset;
		$this->aText = $this->strsplit($this->sText);
		$this->iLength = count($this->aText);
	}

	public function getCharset() {
		$this->oParserHelper->getCharset();
		return $this->sCharset;
	}

	public function currentLine() {
		return $this->iLineNo;
	}

	public function getSettings() {
		return $this->oParserSettings;
	}

	public function parseIdentifier($bIgnoreCase = true) {
		$sResult = $this->parseCharacter(true);
		if ($sResult === null) {
			throw new UnexpectedTokenException($sResult, $this->peek(5), 'identifier', $this->iLineNo);
		}
		$sCharacter = null;
		while (($sCharacter = $this->parseCharacter(true)) !== null) {
			$sResult .= $sCharacter;
		}
		if ($bIgnoreCase) {
			$sResult = $this->strtolower($sResult);
		}
		return $sResult;
	}

	public function parseCharacter($bIsForIdentifier) {
		if ($this->peek() === '\\') {
			if ($bIsForIdentifier && $this->oParserSettings->bLenientParsing && ($this->comes('\0') || $this->comes('\9'))) {
				// Non-strings can contain \0 or \9 which is an IE hack supported in lenient parsing.
				return null;
			}
			$this->consume('\\');
			if ($this->comes('\n') || $this->comes('\r')) {
				return '';
			}
			if (preg_match('/[0-9a-fA-F]/Su', $this->peek()) === 0) {
				return $this->consume(1);
			}
			$sUnicode = $this->consumeExpression('/^[0-9a-fA-F]{1,6}/u', 6);
			if ($this->strlen($sUnicode) < 6) {
				//Consume whitespace after incomplete unicode escape
				if (preg_match('/\\s/isSu', $this->peek())) {
					if ($this->comes('\r\n')) {
						$this->consume(2);
					} else {
						$this->consume(1);
					}
				}
			}
			$iUnicode = intval($sUnicode, 16);
			$sUtf32 = "";
			for ($i = 0; $i < 4; ++$i) {
				$sUtf32 .= chr($iUnicode & 0xff);
				$iUnicode = $iUnicode >> 8;
			}
			return iconv('utf-32le', $this->sCharset, $sUtf32);
		}
		if ($bIsForIdentifier) {
			$peek = ord($this->peek());
			// Ranges: a-z A-Z 0-9 - _
			if (($peek >= 97 && $peek <= 122) ||
				($peek >= 65 && $peek <= 90) ||
				($peek >= 48 && $peek <= 57) ||
				($peek === 45) ||
				($peek === 95) ||
				($peek > 0xa1)) {
				return $this->consume(1);
			}
		} else {
			return $this->consume(1);
		}
		return null;
	}

	public function consumeWhiteSpace() {
		$comments = array();
		do {
			while (preg_match('/\\s/isSu', $this->peek()) === 1) {
				$this->consume(1);
			}
			if($this->oParserSettings->bLenientParsing) {
				try {
					$oComment = $this->consumeComment();
				} catch(UnexpectedTokenException $e) {
					// When we can’t find the end of a comment, we assume the document is finished.
					$this->iCurrentPosition = $this->iLength;
					return;
				}
			} else {
				$oComment = $this->consumeComment();
			}
			if ($oComment !== false) {
				$comments[] = $oComment;
			}
		} while($oComment !== false);
		return $comments;
	}

	public function comes($sString, $bCaseInsensitive = false) {
		$sPeek = $this->peek(strlen($sString));
		return ($sPeek == '')
			? false
			: $this->streql($sPeek, $sString, $bCaseInsensitive);
	}

	public function peek($iLength = 1, $iOffset = 0) {
		$iOffset += $this->iCurrentPosition;
		if ($iOffset >= $this->iLength) {
			return '';
		}
		return $this->substr($iOffset, $iLength);
	}

	public function consume($mValue = 1) {
		if (is_string($mValue)) {
			$iLineCount = substr_count($mValue, "\n");
			$iLength = $this->strlen($mValue);
			if (!$this->streql($this->substr($this->iCurrentPosition, $iLength), $mValue)) {
				throw new UnexpectedTokenException($mValue, $this->peek(max($iLength, 5)), $this->iLineNo);
			}
			$this->iLineNo += $iLineCount;
			$this->iCurrentPosition += $this->strlen($mValue);
			return $mValue;
		} else {
			if ($this->iCurrentPosition + $mValue > $this->iLength) {
				throw new UnexpectedTokenException($mValue, $this->peek(5), 'count', $this->iLineNo);
			}
			$sResult = $this->substr($this->iCurrentPosition, $mValue);
			$iLineCount = substr_count($sResult, "\n");
			$this->iLineNo += $iLineCount;
			$this->iCurrentPosition += $mValue;
			return $sResult;
		}
	}

	public function consumeExpression($mExpression, $iMaxLength = null) {
		$aMatches = null;
		$sInput = $iMaxLength !== null ? $this->peek($iMaxLength) : $this->inputLeft();
		if (preg_match($mExpression, $sInput, $aMatches, PREG_OFFSET_CAPTURE) === 1) {
			return $this->consume($aMatches[0][0]);
		}
		throw new UnexpectedTokenException($mExpression, $this->peek(5), 'expression', $this->iLineNo);
	}

	/**
	 * @return false|Comment
	 */
	public function consumeComment() {
		$mComment = false;
		if ($this->comes('/*')) {
			$iLineNo = $this->iLineNo;
			$this->consume(1);
			$mComment = '';
			while (($char = $this->consume(1)) !== '') {
				$mComment .= $char;
				if ($this->comes('*/')) {
					$this->consume(2);
					break;
				}
			}
		}

		if ($mComment !== false) {
			// We skip the * which was included in the comment.
			return new Comment(substr($mComment, 1), $iLineNo);
		}

		return $mComment;
	}

	public function isEnd() {
		return $this->iCurrentPosition >= $this->iLength;
	}

	public function consumeUntil($aEnd, $bIncludeEnd = false, $consumeEnd = false, array &$comments = array()) {
		$aEnd = is_array($aEnd) ? $aEnd : array($aEnd);
		$out = '';
		$start = $this->iCurrentPosition;

		while (($char = $this->consume(1)) !== '') {
			if (in_array($char, $aEnd)) {
				if ($bIncludeEnd) {
					$out .= $char;
				} elseif (!$consumeEnd) {
					$this->iCurrentPosition -= $this->strlen($char);
				}
				return $out;
			}
			$out .= $char;
			if ($comment = $this->consumeComment()) {
				$comments[] = $comment;
			}
		}

		$this->iCurrentPosition = $start;
		throw new UnexpectedTokenException('One of ("'.implode('","', $aEnd).'")', $this->peek(5), 'search', $this->iLineNo);
	}

	private function inputLeft() {
		return $this->substr($this->iCurrentPosition, -1);
	}

	public function streql($sString1, $sString2, $bCaseInsensitive = true) {
		if($bCaseInsensitive) {
			return $this->strtolower($sString1) === $this->strtolower($sString2);
		} else {
			return $sString1 === $sString2;
		}
	}

	public function backtrack($iAmount) {
		$this->iCurrentPosition -= $iAmount;
	}

	public function strlen($sString) {
		if ($this->oParserSettings->bMultibyteSupport) {
			return mb_strlen($sString, $this->sCharset);
		} else {
			return strlen($sString);
		}	
	}	

	private function substr($iStart, $iLength) {
		if ($iLength < 0) {
			$iLength = $this->iLength - $iStart + $iLength;
		}	
		if ($iStart + $iLength > $this->iLength) {
			$iLength = $this->iLength - $iStart;
		}	
		$sResult = '';
		while ($iLength > 0) {
			$sResult .= $this->aText[$iStart];
			$iStart++;
			$iLength--;
		}	
		return $sResult;
	}

	private function strtolower($sString) {
		if ($this->oParserSettings->bMultibyteSupport) {
			return mb_strtolower($sString, $this->sCharset);
		} else {
			return strtolower($sString);
		}
	}

	private function strsplit($sString) {
		if ($this->oParserSettings->bMultibyteSupport) {
			if ($this->streql($this->sCharset, 'utf-8')) {
				return preg_split('//u', $sString, null, PREG_SPLIT_NO_EMPTY);
			} else {
				$iLength = mb_strlen($sString, $this->sCharset);
				$aResult = array();
				for ($i = 0; $i < $iLength; ++$i) {
					$aResult[] = mb_substr($sString, $i, 1, $this->sCharset);
				}
				return $aResult;
			}
		} else {
			if($sString === '') {
				return array();
			} else {
				return str_split($sString);
			}
		}
	}

	private function strpos($sString, $sNeedle, $iOffset) {
		if ($this->oParserSettings->bMultibyteSupport) {
			return mb_strpos($sString, $sNeedle, $iOffset, $this->sCharset);
		} else {
			return strpos($sString, $sNeedle, $iOffset);
		}
	}
}