<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\TypeHints;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use SlevomatCodingStandard\Helpers\Annotation\GenericAnnotation;
use SlevomatCodingStandard\Helpers\AnnotationHelper;
use SlevomatCodingStandard\Helpers\AnnotationTypeHelper;
use function sprintf;
use function strtolower;
use const T_DOC_COMMENT_OPEN_TAG;

class LongTypeHintsSniff implements Sniff
{

	public const CODE_USED_LONG_TYPE_HINT = 'UsedLongTypeHint';

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
		$annotations = AnnotationHelper::getAnnotations($phpcsFile, $docCommentOpenPointer);

		foreach ($annotations as $annotationName => $annotationByName) {
			foreach ($annotationByName as $annotation) {
				if ($annotation instanceof GenericAnnotation) {
					continue;
				}

				if ($annotation->isInvalid()) {
					continue;
				}

				foreach (AnnotationHelper::getAnnotationTypes($annotation) as $annotationType) {
					foreach (AnnotationTypeHelper::getIdentifierTypeNodes($annotationType) as $typeHintNode) {
						$typeHint = AnnotationTypeHelper::getTypeHintFromNode($typeHintNode);

						$lowercasedTypeHint = strtolower($typeHint);

						$shortTypeHint = null;
						if ($lowercasedTypeHint === 'integer') {
							$shortTypeHint = 'int';
						} elseif ($lowercasedTypeHint === 'boolean') {
							$shortTypeHint = 'bool';
						}

						if ($shortTypeHint === null) {
							continue;
						}

						$fix = $phpcsFile->addFixableError(sprintf(
							'Expected "%s" but found "%s" in %s annotation.',
							$shortTypeHint,
							$typeHint,
							$annotationName
						), $annotation->getStartPointer(), self::CODE_USED_LONG_TYPE_HINT);

						if (!$fix) {
							continue;
						}

						$fixedAnnotationContent = AnnotationHelper::fixAnnotationType(
							$phpcsFile,
							$annotation,
							$typeHintNode,
							new IdentifierTypeNode($shortTypeHint)
						);

						$phpcsFile->fixer->beginChangeset();

						$phpcsFile->fixer->replaceToken($annotation->getStartPointer(), $fixedAnnotationContent);
						for ($i = $annotation->getStartPointer() + 1; $i <= $annotation->getEndPointer(); $i++) {
							$phpcsFile->fixer->replaceToken($i, '');
						}

						$phpcsFile->fixer->endChangeset();
					}
				}
			}
		}
	}

}
