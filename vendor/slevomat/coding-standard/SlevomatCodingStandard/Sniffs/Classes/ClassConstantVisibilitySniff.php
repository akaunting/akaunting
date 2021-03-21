<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Classes;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use SlevomatCodingStandard\Helpers\ClassHelper;
use SlevomatCodingStandard\Helpers\TokenHelper;
use function array_keys;
use function count;
use function in_array;
use function sprintf;
use const T_ANON_CLASS;
use const T_CLASS;
use const T_CONST;
use const T_INTERFACE;
use const T_PRIVATE;
use const T_PROTECTED;
use const T_PUBLIC;

class ClassConstantVisibilitySniff implements Sniff
{

	public const CODE_MISSING_CONSTANT_VISIBILITY = 'MissingConstantVisibility';

	/** @var bool */
	public $fixable = false;

	/**
	 * @return array<int, (int|string)>
	 */
	public function register(): array
	{
		return [
			T_CONST,
		];
	}

	/**
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
	 * @param File $phpcsFile
	 * @param int $constantPointer
	 */
	public function process(File $phpcsFile, $constantPointer): void
	{
		$tokens = $phpcsFile->getTokens();

		if (count($tokens[$constantPointer]['conditions']) === 0) {
			return;
		}

		/** @var int $classPointer */
		$classPointer = array_keys($tokens[$constantPointer]['conditions'])[count($tokens[$constantPointer]['conditions']) - 1];
		if (!in_array($tokens[$classPointer]['code'], [T_CLASS, T_INTERFACE, T_ANON_CLASS], true)) {
			return;
		}

		$visibilityPointer = TokenHelper::findPreviousEffective($phpcsFile, $constantPointer - 1);
		if (in_array($tokens[$visibilityPointer]['code'], [T_PUBLIC, T_PROTECTED, T_PRIVATE], true)) {
			return;
		}

		$message = sprintf(
			'Constant %s::%s visibility missing.',
			ClassHelper::getFullyQualifiedName($phpcsFile, $classPointer),
			$tokens[TokenHelper::findNextEffective($phpcsFile, $constantPointer + 1)]['content']
		);

		if ($this->fixable) {
			$fix = $phpcsFile->addFixableError($message, $constantPointer, self::CODE_MISSING_CONSTANT_VISIBILITY);
			if ($fix) {
				$phpcsFile->fixer->beginChangeset();
				$phpcsFile->fixer->addContentBefore($constantPointer, 'public ');
				$phpcsFile->fixer->endChangeset();
			}
		} else {
			$phpcsFile->addError($message, $constantPointer, self::CODE_MISSING_CONSTANT_VISIBILITY);
		}
	}

}
