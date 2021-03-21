<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Helpers;

use PHP_CodeSniffer\Files\File;
use function in_array;
use function ltrim;
use function preg_replace_callback;
use function rtrim;
use function str_repeat;
use function strlen;
use function substr;
use const T_END_HEREDOC;
use const T_END_NOWDOC;
use const T_START_HEREDOC;
use const T_START_NOWDOC;

/**
 * @internal
 */
class IndentationHelper
{

	public const DEFAULT_INDENTATION_WIDTH = 4;

	public const TAB_INDENT = "\t";
	public const SPACES_INDENT = '    ';

	public static function getIndentation(File $phpcsFile, int $pointer): string
	{
		$firstPointerOnLine = TokenHelper::findFirstTokenOnLine($phpcsFile, $pointer);

		return TokenHelper::getContent($phpcsFile, $firstPointerOnLine, $pointer - 1);
	}

	public static function addIndentation(string $identation, int $level = 1): string
	{
		$whitespace = self::getOneIndentationLevel($identation);

		return $identation . str_repeat($whitespace, $level);
	}

	public static function getOneIndentationLevel(string $identation): string
	{
		return $identation === ''
			? self::TAB_INDENT
			: ($identation[0] === self::TAB_INDENT ? self::TAB_INDENT : self::SPACES_INDENT);
	}

	/**
	 * @param File $phpcsFile
	 * @param int[] $codePointers
	 * @param string $defaultIndentation
	 * @return string
	 */
	public static function fixIndentation(File $phpcsFile, array $codePointers, string $defaultIndentation): string
	{
		$tokens = $phpcsFile->getTokens();

		$eolLength = strlen($phpcsFile->eolChar);

		$code = '';
		$inHeredoc = false;

		foreach ($codePointers as $no => $codePointer) {
			$content = $tokens[$codePointer]['content'];

			if (
				!$inHeredoc
				&& (
					$no === 0
					|| substr($tokens[$codePointer - 1]['content'], -$eolLength) === $phpcsFile->eolChar
				)
			) {
				if ($content === $phpcsFile->eolChar) {
					// Nothing
				} elseif ($content[0] === self::TAB_INDENT) {
					$content = substr($content, 1);
				} elseif (substr($content, 0, self::DEFAULT_INDENTATION_WIDTH) === self::SPACES_INDENT) {
					$content = substr($content, self::DEFAULT_INDENTATION_WIDTH);
				} else {
					$content = $defaultIndentation . ltrim($content);
				}
			}

			if (in_array($tokens[$codePointer]['code'], [T_START_HEREDOC, T_START_NOWDOC], true)) {
				$inHeredoc = true;
			} elseif (in_array($tokens[$codePointer]['code'], [T_END_HEREDOC, T_END_NOWDOC], true)) {
				$inHeredoc = false;
			}

			$code .= $content;
		}

		return rtrim($code);
	}

	public static function convertTabsToSpaces(File $phpcsFile, string $code): string
	{
		return preg_replace_callback('~^(\t+)~', static function (array $matches) use ($phpcsFile): string {
			$indentation = str_repeat(
				' ',
				$phpcsFile->config->tabWidth !== 0 ? $phpcsFile->config->tabWidth : self::DEFAULT_INDENTATION_WIDTH
			);
			return str_repeat($indentation, strlen($matches[1]));
		}, $code);
	}

}
