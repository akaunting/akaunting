<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\TypeHints;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\PhpDocParser\Ast\Type\UnionTypeNode;
use SlevomatCodingStandard\Helpers\Annotation\GenericAnnotation;
use SlevomatCodingStandard\Helpers\AnnotationHelper;
use SlevomatCodingStandard\Helpers\AnnotationTypeHelper;
use function count;
use function sprintf;
use function strtolower;
use const T_DOC_COMMENT_OPEN_TAG;

class NullTypeHintOnLastPositionSniff implements Sniff
{

	public const CODE_NULL_TYPE_HINT_NOT_ON_LAST_POSITION = 'NullTypeHintNotOnLastPosition';

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

		foreach ($annotations as $annotationByName) {
			foreach ($annotationByName as $annotation) {
				if ($annotation instanceof GenericAnnotation) {
					continue;
				}

				if ($annotation->isInvalid()) {
					continue;
				}

				foreach (AnnotationHelper::getAnnotationTypes($annotation) as $annotationType) {
					foreach (AnnotationTypeHelper::getUnionTypeNodes($annotationType) as $unionTypeNode) {
						$nullTypeNode = null;
						$nullPosition = 0;
						$position = 0;
						foreach ($unionTypeNode->types as $typeNode) {
							if ($typeNode instanceof IdentifierTypeNode && strtolower($typeNode->name) === 'null') {
								$nullTypeNode = $typeNode;
								$nullPosition = $position;
								break;
							}

							$position++;
						}

						if ($nullTypeNode === null) {
							continue;
						}

						if ($nullPosition === count($unionTypeNode->types) - 1) {
							continue;
						}

						$fix = $phpcsFile->addFixableError(
							sprintf('Null type hint should be on last position in "%s".', AnnotationTypeHelper::export($unionTypeNode)),
							$annotation->getStartPointer(),
							self::CODE_NULL_TYPE_HINT_NOT_ON_LAST_POSITION
						);

						if (!$fix) {
							continue;
						}

						$phpcsFile->fixer->beginChangeset();

						for ($i = $annotation->getStartPointer(); $i <= $annotation->getEndPointer(); $i++) {
							$phpcsFile->fixer->replaceToken($i, '');
						}

						$fixedTypeNodes = [];
						foreach ($unionTypeNode->types as $typeNode) {
							if ($typeNode === $nullTypeNode) {
								continue;
							}

							$fixedTypeNodes[] = $typeNode;
						}
						$fixedTypeNodes[] = $nullTypeNode;
						$fixedUnionTypeNode = new UnionTypeNode($fixedTypeNodes);

						$fixedAnnotationContent = AnnotationHelper::fixAnnotationType(
							$phpcsFile,
							$annotation,
							$unionTypeNode,
							$fixedUnionTypeNode
						);

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
