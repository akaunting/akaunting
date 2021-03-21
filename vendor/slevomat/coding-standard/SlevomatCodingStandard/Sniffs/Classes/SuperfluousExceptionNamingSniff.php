<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Classes;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use SlevomatCodingStandard\Helpers\ClassHelper;
use SlevomatCodingStandard\Helpers\TokenHelper;
use function sprintf;
use function strtolower;
use function substr;
use const T_ABSTRACT;
use const T_CLASS;

class SuperfluousExceptionNamingSniff implements Sniff
{

	public const CODE_SUPERFLUOUS_SUFFIX = 'SuperfluousSuffix';

	/**
	 * @return array<int, (int|string)>
	 */
	public function register(): array
	{
		return [
			T_CLASS,
		];
	}

	/**
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
	 * @param File $phpcsFile
	 * @param int $classPointer
	 */
	public function process(File $phpcsFile, $classPointer): void
	{
		$className = ClassHelper::getName($phpcsFile, $classPointer);

		$previousPointer = TokenHelper::findPreviousEffective($phpcsFile, $classPointer - 1);
		if ($phpcsFile->getTokens()[$previousPointer]['code'] === T_ABSTRACT) {
			return;
		}

		if (strtolower($className) === 'exception') {
			return;
		}

		$suffix = substr($className, -9);
		if (strtolower($suffix) !== 'exception') {
			return;
		}

		$phpcsFile->addError(sprintf('Superfluous suffix "%s".', $suffix), $classPointer, self::CODE_SUPERFLUOUS_SUFFIX);
	}

}
