<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Commenting;

use PHP_CodeSniffer\Files\File;

class RequireOneLineDocCommentSniff extends AbstractRequireOneLineDocComment
{

	public const CODE_MULTI_LINE_DOC_COMMENT = 'MultiLineDocComment';

	protected function addError(File $phpcsFile, int $docCommentStartPointer): bool
	{
		$error = 'Found multi-line doc comment with single line content, use one-line doc comment instead.';

		return $phpcsFile->addFixableError($error, $docCommentStartPointer, self::CODE_MULTI_LINE_DOC_COMMENT);
	}

}
