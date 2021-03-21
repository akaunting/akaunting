<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Classes;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use SlevomatCodingStandard\Helpers\ClassHelper;
use SlevomatCodingStandard\Helpers\TokenHelper;
use const T_ANON_CLASS;
use const T_CLASS;
use const T_COMMA;
use const T_OPEN_CURLY_BRACKET;
use const T_SEMICOLON;
use const T_TRAIT;
use const T_WHITESPACE;

class TraitUseDeclarationSniff implements Sniff
{

	public const CODE_MULTIPLE_TRAITS_PER_DECLARATION = 'MultipleTraitsPerDeclaration';

	/**
	 * @return array<int, (int|string)>
	 */
	public function register(): array
	{
		return [
			T_CLASS,
			T_ANON_CLASS,
			T_TRAIT,
		];
	}

	/**
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
	 * @param File $phpcsFile
	 * @param int $classPointer
	 */
	public function process(File $phpcsFile, $classPointer): void
	{
		$usePointers = ClassHelper::getTraitUsePointers($phpcsFile, $classPointer);

		foreach ($usePointers as $usePointer) {
			$this->checkDeclaration($phpcsFile, $usePointer);
		}
	}

	private function checkDeclaration(File $phpcsFile, int $usePointer): void
	{
		$commaPointer = TokenHelper::findNextLocal($phpcsFile, T_COMMA, $usePointer + 1);
		if ($commaPointer === null) {
			return;
		}

		$endPointer = TokenHelper::findNext($phpcsFile, [T_OPEN_CURLY_BRACKET, T_SEMICOLON], $usePointer + 1);

		$tokens = $phpcsFile->getTokens();
		if ($tokens[$endPointer]['code'] === T_OPEN_CURLY_BRACKET) {
			$phpcsFile->addError(
				'Multiple traits per use statement are forbidden.',
				$usePointer,
				self::CODE_MULTIPLE_TRAITS_PER_DECLARATION
			);
			return;
		}

		$fix = $phpcsFile->addFixableError(
			'Multiple traits per use statement are forbidden.',
			$usePointer,
			self::CODE_MULTIPLE_TRAITS_PER_DECLARATION
		);

		if (!$fix) {
			return;
		}

		$indentation = '';
		$currentPointer = $usePointer - 1;
		while (
			$tokens[$currentPointer]['code'] === T_WHITESPACE
			&& $tokens[$currentPointer]['content'] !== $phpcsFile->eolChar
		) {
			$indentation .= $tokens[$currentPointer]['content'];
			$currentPointer--;
		}

		$phpcsFile->fixer->beginChangeset();

		$otherCommaPointers = TokenHelper::findNextAll($phpcsFile, T_COMMA, $usePointer + 1, $endPointer);
		foreach ($otherCommaPointers as $otherCommaPointer) {
			$pointerAfterComma = TokenHelper::findNextEffective($phpcsFile, $otherCommaPointer + 1);
			$phpcsFile->fixer->replaceToken($otherCommaPointer, ';' . $phpcsFile->eolChar . $indentation . 'use ');
			for ($i = $otherCommaPointer + 1; $i < $pointerAfterComma; $i++) {
				$phpcsFile->fixer->replaceToken($i, '');
			}
		}

		$phpcsFile->fixer->endChangeset();
	}

}
