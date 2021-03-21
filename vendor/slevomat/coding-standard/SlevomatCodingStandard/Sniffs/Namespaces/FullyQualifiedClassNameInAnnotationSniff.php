<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Namespaces;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstFetchNode;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use SlevomatCodingStandard\Helpers\Annotation\GenericAnnotation;
use SlevomatCodingStandard\Helpers\AnnotationConstantExpressionHelper;
use SlevomatCodingStandard\Helpers\AnnotationHelper;
use SlevomatCodingStandard\Helpers\AnnotationTypeHelper;
use SlevomatCodingStandard\Helpers\TypeHelper;
use SlevomatCodingStandard\Helpers\TypeHintHelper;
use function sprintf;
use function strtolower;
use const T_DOC_COMMENT_OPEN_TAG;

class FullyQualifiedClassNameInAnnotationSniff implements Sniff
{

	public const CODE_NON_FULLY_QUALIFIED_CLASS_NAME = 'NonFullyQualifiedClassName';

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

		foreach ($annotations as $annotationName => $annotationsByName) {
			foreach ($annotationsByName as $annotation) {
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
						if (
							TypeHintHelper::isSimpleTypeHint($lowercasedTypeHint)
							|| TypeHintHelper::isSimpleUnofficialTypeHints($lowercasedTypeHint)
							|| !TypeHelper::isTypeName($typeHint)
							|| TypeHintHelper::isTemplate($phpcsFile, $docCommentOpenPointer, $typeHint)
						) {
							continue;
						}

						$fullyQualifiedTypeHint = TypeHintHelper::getFullyQualifiedTypeHint(
							$phpcsFile,
							$annotation->getStartPointer(),
							$typeHint
						);
						if ($fullyQualifiedTypeHint === $typeHint) {
							continue;
						}
						$fix = $phpcsFile->addFixableError(sprintf(
							'Class name %s in %s should be referenced via a fully qualified name.',
							$fullyQualifiedTypeHint,
							$annotationName
						), $annotation->getStartPointer(), self::CODE_NON_FULLY_QUALIFIED_CLASS_NAME);

						if (!$fix) {
							continue;
						}

						$fixedAnnotationContent = AnnotationHelper::fixAnnotationType(
							$phpcsFile,
							$annotation,
							$typeHintNode,
							new IdentifierTypeNode($fullyQualifiedTypeHint)
						);

						$phpcsFile->fixer->beginChangeset();

						$phpcsFile->fixer->replaceToken($annotation->getStartPointer(), $fixedAnnotationContent);
						for ($i = $annotation->getStartPointer() + 1; $i <= $annotation->getEndPointer(); $i++) {
							$phpcsFile->fixer->replaceToken($i, '');
						}

						$phpcsFile->fixer->endChangeset();
					}
				}

				foreach (AnnotationHelper::getAnnotationConstantExpressions($annotation) as $constantExpression) {
					foreach (AnnotationConstantExpressionHelper::getConstantFetchNodes($constantExpression) as $constantFetchNode) {
						$typeHint = $constantFetchNode->className;

						$fullyQualifiedTypeHint = TypeHintHelper::getFullyQualifiedTypeHint(
							$phpcsFile,
							$annotation->getStartPointer(),
							$typeHint
						);
						if ($fullyQualifiedTypeHint === $typeHint) {
							continue;
						}

						$fix = $phpcsFile->addFixableError(sprintf(
							'Class name %s in %s should be referenced via a fully qualified name.',
							$fullyQualifiedTypeHint,
							$annotationName
						), $annotation->getStartPointer(), self::CODE_NON_FULLY_QUALIFIED_CLASS_NAME);

						if (!$fix) {
							continue;
						}

						$fixedAnnotationContent = AnnotationHelper::fixAnnotationConstantFetchNode(
							$phpcsFile,
							$annotation,
							$constantFetchNode,
							new ConstFetchNode($fullyQualifiedTypeHint, $constantFetchNode->name)
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
