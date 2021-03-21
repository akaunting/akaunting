<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\TypeHints;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use SlevomatCodingStandard\Helpers\AnnotationHelper;
use SlevomatCodingStandard\Helpers\DocCommentHelper;
use SlevomatCodingStandard\Helpers\TokenHelper;
use function array_key_exists;
use function count;
use const T_CONST;
use const T_DOC_COMMENT_WHITESPACE;
use const T_WHITESPACE;

class UselessConstantTypeHintSniff implements Sniff
{

	public const CODE_USELESS_DOC_COMMENT = 'UselessDocComment';
	public const CODE_USELESS_VAR_ANNOTATION = 'UselessVarAnnotation';

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

		$docCommentOpenPointer = DocCommentHelper::findDocCommentOpenToken($phpcsFile, $constantPointer);
		if ($docCommentOpenPointer === null) {
			return;
		}

		$annotations = AnnotationHelper::getAnnotations($phpcsFile, $constantPointer);

		$uselessAnnotation = array_key_exists('@var', $annotations);
		if (!$uselessAnnotation) {
			return;
		}

		$uselessDocComment = !DocCommentHelper::hasDocCommentDescription($phpcsFile, $constantPointer) && count($annotations) === 1;
		if ($uselessDocComment) {
			$fix = $phpcsFile->addFixableError('Useless documentation comment.', $docCommentOpenPointer, self::CODE_USELESS_DOC_COMMENT);

			/** @var int $fixerStart */
			$fixerStart = TokenHelper::findPreviousContent($phpcsFile, T_WHITESPACE, $phpcsFile->eolChar, $docCommentOpenPointer - 1);
			$fixerEnd = $tokens[$docCommentOpenPointer]['comment_closer'];
		} else {
			$annotation = $annotations['@var'][0];

			$fix = $phpcsFile->addFixableError(
				'Useless @var annotation.',
				$annotation->getStartPointer(),
				self::CODE_USELESS_VAR_ANNOTATION
			);

			/** @var int $fixerStart */
			$fixerStart = TokenHelper::findPreviousContent(
				$phpcsFile,
				T_DOC_COMMENT_WHITESPACE,
				$phpcsFile->eolChar,
				$annotation->getStartPointer() - 1
			);
			$fixerEnd = $annotation->getEndPointer();
		}

		if (!$fix) {
			return;
		}

		$phpcsFile->fixer->beginChangeset();
		for ($i = $fixerStart; $i <= $fixerEnd; $i++) {
			$phpcsFile->fixer->replaceToken($i, '');
		}
		$phpcsFile->fixer->endChangeset();
	}

}
