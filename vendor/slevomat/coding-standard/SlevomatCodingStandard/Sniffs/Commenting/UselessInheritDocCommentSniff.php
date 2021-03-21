<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Commenting;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use SlevomatCodingStandard\Helpers\FunctionHelper;
use SlevomatCodingStandard\Helpers\TokenHelper;
use SlevomatCodingStandard\Helpers\TypeHintHelper;
use function array_merge;
use function in_array;
use function preg_match;
use const T_DOC_COMMENT_OPEN_TAG;
use const T_DOC_COMMENT_STAR;
use const T_DOC_COMMENT_WHITESPACE;
use const T_WHITESPACE;

class UselessInheritDocCommentSniff implements Sniff
{

	public const CODE_USELESS_INHERIT_DOC_COMMENT = 'UselessInheritDocComment';

	/**
	 * @return array<int, (int|string)>
	 */
	public function register(): array
	{
		return [
			T_DOC_COMMENT_OPEN_TAG,
		];
	}

	/**
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
	 * @param File $phpcsFile
	 * @param int $docCommentOpenPointer
	 */
	public function process(File $phpcsFile, $docCommentOpenPointer): void
	{
		$tokens = $phpcsFile->getTokens();

		$docCommentContent = '';
		for ($i = $docCommentOpenPointer + 1; $i < $tokens[$docCommentOpenPointer]['comment_closer']; $i++) {
			if (in_array($tokens[$i]['code'], [T_DOC_COMMENT_WHITESPACE, T_DOC_COMMENT_STAR], true)) {
				continue;
			}

			$docCommentContent .= $tokens[$i]['content'];
		}

		if (preg_match('~^(?:\{@inheritDoc\}|@inheritDoc)$~i', $docCommentContent) === 0) {
			return;
		}

		$docCommentOwnerPointer = TokenHelper::findNext(
			$phpcsFile,
			array_merge(TokenHelper::$functionTokenCodes, TokenHelper::getTypeHintTokenCodes()),
			$tokens[$docCommentOpenPointer]['comment_closer'] + 1
		);
		if ($docCommentOwnerPointer === null) {
			return;
		}

		if (in_array($tokens[$docCommentOwnerPointer]['code'], TokenHelper::$functionTokenCodes, true)) {
			$returnTypeHint = FunctionHelper::findReturnTypeHint($phpcsFile, $docCommentOwnerPointer);
			if ($returnTypeHint === null) {
				return;
			}

			if (TypeHintHelper::isSimpleIterableTypeHint($returnTypeHint->getTypeHint())) {
				return;
			}

			$parametersTypeHints = FunctionHelper::getParametersTypeHints($phpcsFile, $docCommentOwnerPointer);
			foreach ($parametersTypeHints as $parameterTypeHint) {
				if ($parameterTypeHint === null) {
					return;
				}

				if (TypeHintHelper::isSimpleIterableTypeHint($parameterTypeHint->getTypeHint())) {
					return;
				}
			}
		}

		$fix = $phpcsFile->addFixableError(
			'Useless documentation comment with @inheritDoc.',
			$docCommentOpenPointer,
			self::CODE_USELESS_INHERIT_DOC_COMMENT
		);

		if (!$fix) {
			return;
		}

		/** @var int $fixerStart */
		$fixerStart = TokenHelper::findPreviousContent($phpcsFile, T_WHITESPACE, $phpcsFile->eolChar, $docCommentOpenPointer - 1);

		$phpcsFile->fixer->beginChangeset();
		for ($i = $fixerStart; $i <= $tokens[$docCommentOpenPointer]['comment_closer']; $i++) {
			$phpcsFile->fixer->replaceToken($i, '');
		}
		$phpcsFile->fixer->endChangeset();
	}

}
