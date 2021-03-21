<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Helpers;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprNode;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstFetchNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\InvalidTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagValueNode;
use PHPStan\PhpDocParser\Ast\Type\CallableTypeNode;
use PHPStan\PhpDocParser\Ast\Type\ConstTypeNode;
use PHPStan\PhpDocParser\Ast\Type\GenericTypeNode;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\PhpDocParser\Ast\Type\ThisTypeNode;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\PhpDocParser\Ast\Type\UnionTypeNode;
use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\Parser\ConstExprParser;
use PHPStan\PhpDocParser\Parser\PhpDocParser;
use PHPStan\PhpDocParser\Parser\TokenIterator;
use PHPStan\PhpDocParser\Parser\TypeParser;
use SlevomatCodingStandard\Helpers\Annotation\Annotation;
use SlevomatCodingStandard\Helpers\Annotation\ExtendsAnnotation;
use SlevomatCodingStandard\Helpers\Annotation\GenericAnnotation;
use SlevomatCodingStandard\Helpers\Annotation\ImplementsAnnotation;
use SlevomatCodingStandard\Helpers\Annotation\MethodAnnotation;
use SlevomatCodingStandard\Helpers\Annotation\MixinAnnotation;
use SlevomatCodingStandard\Helpers\Annotation\ParameterAnnotation;
use SlevomatCodingStandard\Helpers\Annotation\PropertyAnnotation;
use SlevomatCodingStandard\Helpers\Annotation\ReturnAnnotation;
use SlevomatCodingStandard\Helpers\Annotation\TemplateAnnotation;
use SlevomatCodingStandard\Helpers\Annotation\ThrowsAnnotation;
use SlevomatCodingStandard\Helpers\Annotation\UseAnnotation;
use SlevomatCodingStandard\Helpers\Annotation\VariableAnnotation;
use function array_key_exists;
use function array_merge;
use function get_class;
use function in_array;
use function preg_match;
use function preg_match_all;
use function preg_replace;
use function sprintf;
use function strtolower;
use function trim;
use const T_DOC_COMMENT_CLOSE_TAG;
use const T_DOC_COMMENT_STAR;
use const T_DOC_COMMENT_STRING;
use const T_DOC_COMMENT_TAG;
use const T_DOC_COMMENT_WHITESPACE;

class AnnotationHelper
{

	public const PREFIXES = ['psalm', 'phpstan'];

	/**
	 * @internal
	 * @param VariableAnnotation|ParameterAnnotation|ReturnAnnotation|ThrowsAnnotation|PropertyAnnotation|MethodAnnotation|TemplateAnnotation|ExtendsAnnotation|ImplementsAnnotation|UseAnnotation|MixinAnnotation $annotation
	 * @return TypeNode[]
	 */
	public static function getAnnotationTypes(Annotation $annotation): array
	{
		$annotationTypes = [];

		if ($annotation instanceof MethodAnnotation) {
			if ($annotation->getMethodReturnType() !== null) {
				$annotationTypes[] = $annotation->getMethodReturnType();
			}
			foreach ($annotation->getMethodParameters() as $methodParameterAnnotation) {
				if ($methodParameterAnnotation->type === null) {
					continue;
				}

				$annotationTypes[] = $methodParameterAnnotation->type;
			}
		} elseif ($annotation instanceof TemplateAnnotation) {
			if ($annotation->getBound() !== null) {
				$annotationTypes[] = $annotation->getBound();
			}
		} else {
			$annotationTypes[] = $annotation->getType();
		}

		return $annotationTypes;
	}

	/**
	 * @internal
	 * @param VariableAnnotation|ParameterAnnotation|ReturnAnnotation|ThrowsAnnotation|PropertyAnnotation|MethodAnnotation|TemplateAnnotation|ExtendsAnnotation|ImplementsAnnotation|UseAnnotation|MixinAnnotation $annotation
	 * @return ConstExprNode[]
	 */
	public static function getAnnotationConstantExpressions(Annotation $annotation): array
	{
		$constantExpressions = [];

		if ($annotation instanceof MethodAnnotation) {
			foreach ($annotation->getMethodParameters() as $methodParameterAnnotation) {
				if ($methodParameterAnnotation->defaultValue === null) {
					continue;
				}

				$constantExpressions[] = $methodParameterAnnotation->defaultValue;
			}
		}

		foreach (self::getAnnotationTypes($annotation) as $annotationType) {
			foreach (AnnotationTypeHelper::getConstantTypeNodes($annotationType) as $constTypeNode) {
				$constantExpressions[] = $constTypeNode->constExpr;
			}
		}

		return $constantExpressions;
	}

	/**
	 * @internal
	 * @param File $phpcsFile
	 * @param VariableAnnotation|ParameterAnnotation|ReturnAnnotation|ThrowsAnnotation|PropertyAnnotation|MethodAnnotation|TemplateAnnotation|ExtendsAnnotation|ImplementsAnnotation|UseAnnotation|MixinAnnotation $annotation
	 * @param TypeNode $typeNode
	 * @param TypeNode $fixedTypeNode
	 * @return string
	 */
	public static function fixAnnotationType(File $phpcsFile, Annotation $annotation, TypeNode $typeNode, TypeNode $fixedTypeNode): string
	{
		$fixedAnnotation = self::fixAnnotation($annotation, $typeNode, $fixedTypeNode);

		return self::fix($phpcsFile, $annotation, $fixedAnnotation);
	}

	/**
	 * @internal
	 * @param File $phpcsFile
	 * @param VariableAnnotation|ParameterAnnotation|ReturnAnnotation|ThrowsAnnotation|PropertyAnnotation|MethodAnnotation|TemplateAnnotation|ExtendsAnnotation|ImplementsAnnotation|UseAnnotation|MixinAnnotation $annotation
	 * @param ConstFetchNode $node
	 * @param ConstFetchNode $fixedNode
	 * @return string
	 */
	public static function fixAnnotationConstantFetchNode(
		File $phpcsFile,
		Annotation $annotation,
		ConstFetchNode $node,
		ConstFetchNode $fixedNode
	): string
	{
		if ($annotation instanceof MethodAnnotation) {
			$fixedContentNode = clone $annotation->getContentNode();

			foreach ($fixedContentNode->parameters as $parameterNo => $parameterNode) {
				if ($parameterNode->defaultValue === null) {
					continue;
				}

				$fixedContentNode->parameters[$parameterNo] = clone $parameterNode;
				$fixedContentNode->parameters[$parameterNo]->defaultValue = AnnotationConstantExpressionHelper::change(
					$parameterNode->defaultValue,
					$node,
					$fixedNode
				);
			}

			$fixedAnnotation = new MethodAnnotation(
				$annotation->getName(),
				$annotation->getStartPointer(),
				$annotation->getEndPointer(),
				$annotation->getContent(),
				$fixedContentNode
			);
		} else {
			$fixedAnnotation = $annotation;

			foreach (self::getAnnotationTypes($annotation) as $annotationType) {
				foreach (AnnotationTypeHelper::getConstantTypeNodes($annotationType) as $constTypeNode) {
					foreach (AnnotationConstantExpressionHelper::getConstantFetchNodes($constTypeNode->constExpr) as $constFetchNode) {
						if ($constFetchNode !== $node) {
							continue;
						}

						$fixedConstTypeNode = new ConstTypeNode(
							AnnotationConstantExpressionHelper::change($constTypeNode->constExpr, $node, $fixedNode)
						);
						$fixedAnnotation = self::fixAnnotation($annotation, $constTypeNode, $fixedConstTypeNode);
						break 3;
					}
				}
			}
		}

		return self::fix($phpcsFile, $annotation, $fixedAnnotation);
	}

	/**
	 * @param File $phpcsFile
	 * @param int $pointer
	 * @param string $annotationName
	 * @return (VariableAnnotation|ParameterAnnotation|ReturnAnnotation|ThrowsAnnotation|PropertyAnnotation|MethodAnnotation|TemplateAnnotation|ExtendsAnnotation|ImplementsAnnotation|UseAnnotation|MixinAnnotation|GenericAnnotation)[]
	 */
	public static function getAnnotationsByName(File $phpcsFile, int $pointer, string $annotationName): array
	{
		$annotations = self::getAnnotations($phpcsFile, $pointer);

		return $annotations[$annotationName] ?? [];
	}

	/**
	 * @param File $phpcsFile
	 * @param int $pointer
	 * @return (VariableAnnotation|ParameterAnnotation|ReturnAnnotation|ThrowsAnnotation|PropertyAnnotation|MethodAnnotation|TemplateAnnotation|ExtendsAnnotation|ImplementsAnnotation|UseAnnotation|MixinAnnotation|GenericAnnotation)[][]
	 */
	public static function getAnnotations(File $phpcsFile, int $pointer): array
	{
		return SniffLocalCache::getAndSetIfNotCached(
			$phpcsFile,
			sprintf('annotations-%d', $pointer),
			static function () use ($phpcsFile, $pointer): array {
				$annotations = [];

				$docCommentOpenToken = DocCommentHelper::findDocCommentOpenToken($phpcsFile, $pointer);
				if ($docCommentOpenToken === null) {
					return $annotations;
				}

				$annotationNameCodes = array_merge([T_DOC_COMMENT_TAG], Tokens::$phpcsCommentTokens);

				$tokens = $phpcsFile->getTokens();
				$i = $docCommentOpenToken + 1;
				while ($i < $tokens[$docCommentOpenToken]['comment_closer']) {
					if (!in_array($tokens[$i]['code'], $annotationNameCodes, true)) {
						$i++;
						continue;
					}

					$annotationStartPointer = $i;
					$annotationEndPointer = $i;

					// Fix for wrong PHPCS parsing
					$parenthesesLevel = (int) preg_match_all('~[({]~', $tokens[$i]['content']) - (int) preg_match_all(
						'~[)}]~',
						$tokens[$i]['content']
					);
					$annotationCode = $tokens[$i]['content'];

					for ($j = $i + 1; $j <= $tokens[$docCommentOpenToken]['comment_closer']; $j++) {
						if ($tokens[$j]['code'] === T_DOC_COMMENT_CLOSE_TAG) {
							$i = $j;
							break;
						}

						if (in_array($tokens[$j]['code'], $annotationNameCodes, true) && $parenthesesLevel === 0) {
							$i = $j;
							break;
						}

						if ($tokens[$j]['code'] === T_DOC_COMMENT_STAR) {
							continue;
						}

						if (in_array($tokens[$j]['code'], array_merge([T_DOC_COMMENT_STRING], $annotationNameCodes), true)) {
							$annotationEndPointer = $j;
						} elseif ($tokens[$j]['code'] === T_DOC_COMMENT_WHITESPACE) {
							if (array_key_exists($j - 1, $tokens) && $tokens[$j - 1]['code'] === T_DOC_COMMENT_STAR) {
								continue;
							}
							if (array_key_exists($j + 1, $tokens) && $tokens[$j + 1]['code'] === T_DOC_COMMENT_STAR) {
								continue;
							}
						}

						$parenthesesLevel += (int) preg_match_all('~[({]~', $tokens[$j]['content']) - (int) preg_match_all(
							'~[)}]~',
							$tokens[$j]['content']
						);
						$annotationCode .= $tokens[$j]['content'];
					}

					$annotationName = $tokens[$annotationStartPointer]['content'];
					$annotationParameters = null;
					$annotationContent = null;
					if (preg_match('~^(@[-a-zA-Z\\\\:]+)(?:\((.*)\))?(?:\\s+(.+))?($)~s', trim($annotationCode), $matches) !== 0) {
						$annotationName = $matches[1];
						$annotationParameters = trim($matches[2]);
						if ($annotationParameters === '') {
							$annotationParameters = null;
						}
						$annotationContent = trim($matches[3]);
						if ($annotationContent === '') {
							$annotationContent = null;
						}
					}

					$mapping = [
						'@param' => ParameterAnnotation::class,
						'@psalm-param' => ParameterAnnotation::class,
						'@phpstan-param' => ParameterAnnotation::class,
						'@return' => ReturnAnnotation::class,
						'@psalm-return' => ReturnAnnotation::class,
						'@phpstan-return' => ReturnAnnotation::class,
						'@var' => VariableAnnotation::class,
						'@psalm-var' => VariableAnnotation::class,
						'@phpstan-var' => VariableAnnotation::class,
						'@throws' => ThrowsAnnotation::class,
						'@phpstan-throws' => ThrowsAnnotation::class,
						'@property' => PropertyAnnotation::class,
						'@psalm-property' => PropertyAnnotation::class,
						'@phpstan-property' => PropertyAnnotation::class,
						'@property-read' => PropertyAnnotation::class,
						'@psalm-property-read' => PropertyAnnotation::class,
						'@phpstan-property-read' => PropertyAnnotation::class,
						'@property-write' => PropertyAnnotation::class,
						'@psalm-property-write' => PropertyAnnotation::class,
						'@phpstan-property-write' => PropertyAnnotation::class,
						'@method' => MethodAnnotation::class,
						'@psalm-method' => MethodAnnotation::class,
						'@phpstan-method' => MethodAnnotation::class,
						'@template' => TemplateAnnotation::class,
						'@psalm-template' => TemplateAnnotation::class,
						'@phpstan-template' => TemplateAnnotation::class,
						'@template-covariant' => TemplateAnnotation::class,
						'@psalm-template-covariant' => TemplateAnnotation::class,
						'@phpstan-template-covariant' => TemplateAnnotation::class,
						'@extends' => ExtendsAnnotation::class,
						'@template-extends' => ExtendsAnnotation::class,
						'@phpstan-extends' => ExtendsAnnotation::class,
						'@implements' => ImplementsAnnotation::class,
						'@template-implements' => ImplementsAnnotation::class,
						'@phpstan-implements' => ImplementsAnnotation::class,
						'@use' => UseAnnotation::class,
						'@template-use' => UseAnnotation::class,
						'@phpstan-use' => UseAnnotation::class,
						'@mixin' => MixinAnnotation::class,
					];

					if (array_key_exists($annotationName, $mapping)) {
						$className = $mapping[$annotationName];

						$parsedContent = null;
						if ($annotationContent !== null) {
							$parsedContent = self::parseAnnotationContent($annotationName, $annotationContent);
							if ($parsedContent instanceof InvalidTagValueNode) {
								$parsedContent = null;
							}
						}

						$annotation = new $className($annotationName, $annotationStartPointer, $annotationEndPointer, $annotationContent, $parsedContent);
					} else {
						$annotation = new GenericAnnotation(
							$annotationName,
							$annotationStartPointer,
							$annotationEndPointer,
							$annotationParameters,
							$annotationContent
						);
					}

					$annotations[$annotationName][] = $annotation;
				}

				return $annotations;
			}
		);
	}

	/**
	 * @param File $phpcsFile
	 * @param int $functionPointer
	 * @param ReturnTypeHint|ParameterTypeHint|PropertyTypeHint|null $typeHint
	 * @param ReturnAnnotation|ParameterAnnotation|VariableAnnotation $annotation
	 * @param array<int, string> $traversableTypeHints
	 * @return bool
	 */
	public static function isAnnotationUseless(
		File $phpcsFile,
		int $functionPointer,
		$typeHint,
		Annotation $annotation,
		array $traversableTypeHints
	): bool
	{
		if ($annotation->isInvalid()) {
			return false;
		}

		if ($typeHint === null || $annotation->getContent() === null) {
			return false;
		}

		if ($annotation->hasDescription()) {
			return false;
		}

		if (TypeHintHelper::isTraversableType(
			TypeHintHelper::getFullyQualifiedTypeHint($phpcsFile, $functionPointer, $typeHint->getTypeHint()),
			$traversableTypeHints
		)) {
			return false;
		}

		if (AnnotationTypeHelper::containsStaticOrThisType($annotation->getType())) {
			return false;
		}

		if (AnnotationTypeHelper::isCompoundOfNull($annotation->getType())) {
			/** @var UnionTypeNode $annotationTypeNode */
			$annotationTypeNode = $annotation->getType();

			$annotationTypeHintNode = AnnotationTypeHelper::getTypeFromNullableType($annotationTypeNode);
			$annotationTypeHint = $annotationTypeHintNode instanceof IdentifierTypeNode
				? $annotationTypeHintNode->name
				: (string) $annotationTypeHintNode;
			return TypeHintHelper::typeHintEqualsAnnotation($phpcsFile, $functionPointer, $typeHint->getTypeHint(), $annotationTypeHint);
		}

		if (!AnnotationTypeHelper::containsOneType($annotation->getType())) {
			return false;
		}

		if ($annotation->getType() instanceof ConstTypeNode) {
			return false;
		}

		if ($annotation->getType() instanceof GenericTypeNode) {
			return false;
		}

		if ($annotation->getType() instanceof CallableTypeNode) {
			return false;
		}

		/** @var GenericTypeNode|CallableTypeNode|IdentifierTypeNode|ThisTypeNode $annotationTypeNode */
		$annotationTypeNode = $annotation->getType();

		if (
			$annotationTypeNode instanceof IdentifierTypeNode
			&& in_array(
				strtolower($annotationTypeNode->name),
				['true', 'false', 'class-string', 'trait-string', 'callable-string', 'numeric-string'],
				true
			)
		) {
			return false;
		}

		$annotationTypeHint = AnnotationTypeHelper::getTypeHintFromOneType($annotationTypeNode);
		return TypeHintHelper::typeHintEqualsAnnotation($phpcsFile, $functionPointer, $typeHint->getTypeHint(), $annotationTypeHint);
	}

	/**
	 * @param VariableAnnotation|ParameterAnnotation|ReturnAnnotation|ThrowsAnnotation|PropertyAnnotation|MethodAnnotation|TemplateAnnotation|ExtendsAnnotation|ImplementsAnnotation|UseAnnotation|MixinAnnotation $annotation
	 * @param TypeNode $typeNode
	 * @param TypeNode $fixedTypeNode
	 * @return Annotation
	 */
	private static function fixAnnotation(Annotation $annotation, TypeNode $typeNode, TypeNode $fixedTypeNode): Annotation
	{
		if ($annotation instanceof MethodAnnotation) {
			$fixedContentNode = clone $annotation->getContentNode();

			if ($fixedContentNode->returnType !== null) {
				$fixedContentNode->returnType = AnnotationTypeHelper::change($fixedContentNode->returnType, $typeNode, $fixedTypeNode);
			}
			foreach ($fixedContentNode->parameters as $parameterNo => $parameterNode) {
				if ($parameterNode->type === null) {
					continue;
				}

				$fixedContentNode->parameters[$parameterNo] = clone $parameterNode;
				$fixedContentNode->parameters[$parameterNo]->type = AnnotationTypeHelper::change(
					$parameterNode->type,
					$typeNode,
					$fixedTypeNode
				);
			}
		} elseif ($annotation instanceof TemplateAnnotation) {
			$fixedContentNode = clone $annotation->getContentNode();
			$fixedContentNode->bound = AnnotationTypeHelper::change($annotation->getBound(), $typeNode, $fixedTypeNode);
		} elseif (
			$annotation instanceof ExtendsAnnotation
			|| $annotation instanceof ImplementsAnnotation
			|| $annotation instanceof UseAnnotation
		) {
			$fixedContentNode = clone $annotation->getContentNode();
			/** @var GenericTypeNode $fixedType */
			$fixedType = AnnotationTypeHelper::change($annotation->getType(), $typeNode, $fixedTypeNode);
			$fixedContentNode->type = $fixedType;
		} else {
			$fixedContentNode = clone $annotation->getContentNode();
			$fixedContentNode->type = AnnotationTypeHelper::change($annotation->getType(), $typeNode, $fixedTypeNode);
		}

		$annotationClassName = get_class($annotation);

		return new $annotationClassName(
			$annotation->getName(),
			$annotation->getStartPointer(),
			$annotation->getEndPointer(),
			$annotation->getContent(),
			$fixedContentNode
		);
	}

	private static function fix(File $phpcsFile, Annotation $annotation, Annotation $fixedAnnotation): string
	{
		$spaceAfterContent = '';
		if (preg_match(
			'~(\\s+)$~',
			TokenHelper::getContent($phpcsFile, $annotation->getStartPointer(), $annotation->getEndPointer()),
			$matches
		) > 0) {
			$spaceAfterContent = $matches[1];
		}

		$fixedAnnotationContent = $fixedAnnotation->export() . $spaceAfterContent;

		return preg_replace('~(\r\n|\n|\r)~', '\1 * ', $fixedAnnotationContent);
	}

	private static function parseAnnotationContent(string $annotationName, string $annotationContent): PhpDocTagValueNode
	{
		$annotationContentWithoutNewLines = preg_replace('~[\r\n]~', ' ', $annotationContent);

		$tokens = new TokenIterator(self::getPhpDocLexer()->tokenize($annotationContentWithoutNewLines));
		return self::getPhpDocParser()->parseTagValue($tokens, $annotationName);
	}

	private static function getPhpDocLexer(): Lexer
	{
		static $phpDocLexer;

		if ($phpDocLexer === null) {
			$phpDocLexer = new Lexer();
		}

		return $phpDocLexer;
	}

	private static function getPhpDocParser(): PhpDocParser
	{
		static $phpDocParser;

		if ($phpDocParser === null) {
			$constantExpressionParser = new ConstExprParser();
			$phpDocParser = new PhpDocParser(new TypeParser($constantExpressionParser), $constantExpressionParser);
		}

		return $phpDocParser;
	}

}
