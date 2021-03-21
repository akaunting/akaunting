<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Commenting;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use SlevomatCodingStandard\Helpers\Annotation\Annotation;
use SlevomatCodingStandard\Helpers\AnnotationHelper;
use SlevomatCodingStandard\Helpers\DocCommentHelper;
use SlevomatCodingStandard\Helpers\IndentationHelper;
use SlevomatCodingStandard\Helpers\SniffSettingsHelper;
use SlevomatCodingStandard\Helpers\TokenHelper;
use function array_combine;
use function array_diff;
use function array_flip;
use function array_key_exists;
use function array_keys;
use function array_map;
use function array_merge;
use function array_values;
use function asort;
use function count;
use function explode;
use function in_array;
use function ksort;
use function max;
use function preg_match;
use function sprintf;
use function strlen;
use function strpos;
use function substr;
use function substr_count;
use function uasort;
use function usort;
use const T_DOC_COMMENT_OPEN_TAG;
use const T_DOC_COMMENT_STAR;
use const T_DOC_COMMENT_STRING;
use const T_DOC_COMMENT_WHITESPACE;

class DocCommentSpacingSniff implements Sniff
{

	public const CODE_INCORRECT_LINES_COUNT_BEFORE_FIRST_CONTENT = 'IncorrectLinesCountBeforeFirstContent';
	public const CODE_INCORRECT_LINES_COUNT_BETWEEN_DESCRIPTION_AND_ANNOTATIONS = 'IncorrectLinesCountBetweenDescriptionAndAnnotations';
	public const CODE_INCORRECT_LINES_COUNT_BETWEEN_DIFFERENT_ANNOTATIONS_TYPES = 'IncorrectLinesCountBetweenDifferentAnnotationsTypes';
	public const CODE_INCORRECT_LINES_COUNT_BETWEEN_ANNOTATIONS_GROUPS = 'IncorrectLinesCountBetweenAnnotationsGroups';
	public const CODE_INCORRECT_LINES_COUNT_AFTER_LAST_CONTENT = 'IncorrectLinesCountAfterLastContent';
	public const CODE_INCORRECT_ANNOTATIONS_GROUP = 'IncorrectAnnotationsGroup';
	public const CODE_INCORRECT_ORDER_OF_ANNOTATIONS_GROUPS = 'IncorrectOrderOfAnnotationsGroup';
	public const CODE_INCORRECT_ORDER_OF_ANNOTATIONS_IN_GROUP = 'IncorrectOrderOfAnnotationsInGroup';

	/** @var int */
	public $linesCountBeforeFirstContent = 0;

	/** @var int */
	public $linesCountBetweenDescriptionAndAnnotations = 1;

	/** @var int */
	public $linesCountBetweenDifferentAnnotationsTypes = 0;

	/** @var int */
	public $linesCountBetweenAnnotationsGroups = 1;

	/** @var int */
	public $linesCountAfterLastContent = 0;

	/** @var string[] */
	public $annotationsGroups = [];

	/** @var string[][]|null */
	private $normalizedAnnotationsGroups = null;

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
	 * @param int $docCommentOpenerPointer
	 */
	public function process(File $phpcsFile, $docCommentOpenerPointer): void
	{
		if (DocCommentHelper::isInline($phpcsFile, $docCommentOpenerPointer)) {
			return;
		}

		$tokens = $phpcsFile->getTokens();

		$firstContentStartPointer = TokenHelper::findNextExcluding(
			$phpcsFile,
			[T_DOC_COMMENT_WHITESPACE, T_DOC_COMMENT_STAR],
			$docCommentOpenerPointer + 1,
			$tokens[$docCommentOpenerPointer]['comment_closer']
		);

		if ($firstContentStartPointer === null) {
			return;
		}

		$firstContentEndPointer = $firstContentStartPointer;
		$actualPointer = $firstContentStartPointer;
		do {
			/** @var int $actualPointer */
			$actualPointer = TokenHelper::findNextExcluding(
				$phpcsFile,
				[T_DOC_COMMENT_STAR, T_DOC_COMMENT_WHITESPACE],
				$actualPointer + 1,
				$tokens[$docCommentOpenerPointer]['comment_closer'] + 1
			);

			if ($tokens[$actualPointer]['code'] !== T_DOC_COMMENT_STRING) {
				break;
			}

			$firstContentEndPointer = $actualPointer;
		} while (true);

		$annotations = array_merge([], ...array_values(AnnotationHelper::getAnnotations($phpcsFile, $docCommentOpenerPointer)));
		uasort($annotations, static function (Annotation $a, Annotation $b): int {
			return $a->getStartPointer() <=> $b->getEndPointer();
		});
		$annotations = array_values($annotations);
		$annotationsCount = count($annotations);

		$firstAnnotation = $annotationsCount > 0 ? $annotations[0] : null;

		$lastContentEndPointer = $annotationsCount > 0 ? $annotations[$annotationsCount - 1]->getEndPointer() : $firstContentEndPointer;

		$this->checkLinesBeforeFirstContent($phpcsFile, $docCommentOpenerPointer, $firstContentStartPointer);
		$this->checkLinesBetweenDescriptionAndFirstAnnotation(
			$phpcsFile,
			$docCommentOpenerPointer,
			$firstContentStartPointer,
			$firstContentEndPointer,
			$firstAnnotation
		);

		if (count($annotations) > 1) {
			if (count($this->getAnnotationsGroups()) === 0) {
				$this->checkLinesBetweenDifferentAnnotationsTypes($phpcsFile, $docCommentOpenerPointer, $annotations);
			} else {
				$this->checkAnnotationsGroups($phpcsFile, $docCommentOpenerPointer, $annotations);
			}
		}

		$this->checkLinesAfterLastContent(
			$phpcsFile,
			$docCommentOpenerPointer,
			$tokens[$docCommentOpenerPointer]['comment_closer'],
			$lastContentEndPointer
		);
	}

	private function checkLinesBeforeFirstContent(File $phpcsFile, int $docCommentOpenerPointer, int $firstContentStartPointer): void
	{
		$tokens = $phpcsFile->getTokens();

		$whitespaceBeforeFirstContent = substr($tokens[$docCommentOpenerPointer]['content'], 0, strlen('/**'));
		$whitespaceBeforeFirstContent .= TokenHelper::getContent($phpcsFile, $docCommentOpenerPointer + 1, $firstContentStartPointer - 1);

		$requiredLinesCountBeforeFirstContent = SniffSettingsHelper::normalizeInteger($this->linesCountBeforeFirstContent);
		$linesCountBeforeFirstContent = max(substr_count($whitespaceBeforeFirstContent, $phpcsFile->eolChar) - 1, 0);
		if ($linesCountBeforeFirstContent === $requiredLinesCountBeforeFirstContent) {
			return;
		}

		$fix = $phpcsFile->addFixableError(
			sprintf(
				'Expected %d lines before first content, found %d.',
				$requiredLinesCountBeforeFirstContent,
				$linesCountBeforeFirstContent
			),
			$firstContentStartPointer,
			self::CODE_INCORRECT_LINES_COUNT_BEFORE_FIRST_CONTENT
		);

		if (!$fix) {
			return;
		}

		$indentation = IndentationHelper::getIndentation($phpcsFile, $docCommentOpenerPointer);

		$phpcsFile->fixer->beginChangeset();

		$phpcsFile->fixer->replaceToken($docCommentOpenerPointer, '/**' . $phpcsFile->eolChar);
		for ($i = $docCommentOpenerPointer + 1; $i < $firstContentStartPointer; $i++) {
			$phpcsFile->fixer->replaceToken($i, '');
		}

		for ($i = 1; $i <= $requiredLinesCountBeforeFirstContent; $i++) {
			$phpcsFile->fixer->addContent($docCommentOpenerPointer, sprintf('%s *%s', $indentation, $phpcsFile->eolChar));
		}

		$phpcsFile->fixer->addContentBefore($firstContentStartPointer, $indentation . ' * ');

		$phpcsFile->fixer->endChangeset();
	}

	private function checkLinesBetweenDescriptionAndFirstAnnotation(
		File $phpcsFile,
		int $docCommentOpenerPointer,
		int $firstContentStartPointer,
		int $firstContentEndPointer,
		?Annotation $firstAnnotation
	): void
	{
		if ($firstAnnotation === null) {
			return;
		}

		if ($firstContentStartPointer === $firstAnnotation->getStartPointer()) {
			return;
		}

		$tokens = $phpcsFile->getTokens();

		preg_match('~(\\s+)$~', $tokens[$firstContentEndPointer]['content'], $matches);

		$whitespaceBetweenDescriptionAndFirstAnnotation = $matches[1] ?? '';
		$whitespaceBetweenDescriptionAndFirstAnnotation .= TokenHelper::getContent(
			$phpcsFile,
			$firstContentEndPointer + 1,
			$firstAnnotation->getStartPointer() - 1
		);

		$requiredLinesCountBetweenDescriptionAndAnnotations = SniffSettingsHelper::normalizeInteger(
			$this->linesCountBetweenDescriptionAndAnnotations
		);
		$linesCountBetweenDescriptionAndAnnotations = max(
			substr_count($whitespaceBetweenDescriptionAndFirstAnnotation, $phpcsFile->eolChar) - 1,
			0
		);
		if ($linesCountBetweenDescriptionAndAnnotations === $requiredLinesCountBetweenDescriptionAndAnnotations) {
			return;
		}

		$fix = $phpcsFile->addFixableError(
			sprintf(
				'Expected %d lines between description and annotations, found %d.',
				$requiredLinesCountBetweenDescriptionAndAnnotations,
				$linesCountBetweenDescriptionAndAnnotations
			),
			$firstAnnotation->getStartPointer(),
			self::CODE_INCORRECT_LINES_COUNT_BETWEEN_DESCRIPTION_AND_ANNOTATIONS
		);

		if (!$fix) {
			return;
		}

		$indentation = IndentationHelper::getIndentation($phpcsFile, $docCommentOpenerPointer);

		$phpcsFile->fixer->beginChangeset();

		$phpcsFile->fixer->addNewline($firstContentEndPointer);
		for ($i = $firstContentEndPointer + 1; $i < $firstAnnotation->getStartPointer(); $i++) {
			$phpcsFile->fixer->replaceToken($i, '');
		}

		for ($i = 1; $i <= $requiredLinesCountBetweenDescriptionAndAnnotations; $i++) {
			$phpcsFile->fixer->addContent($firstContentEndPointer, sprintf('%s *%s', $indentation, $phpcsFile->eolChar));
		}

		$phpcsFile->fixer->addContentBefore($firstAnnotation->getStartPointer(), $indentation . ' * ');

		$phpcsFile->fixer->endChangeset();
	}

	/**
	 * @param File $phpcsFile
	 * @param int $docCommentOpenerPointer
	 * @param Annotation[] $annotations
	 */
	private function checkLinesBetweenDifferentAnnotationsTypes(File $phpcsFile, int $docCommentOpenerPointer, array $annotations): void
	{
		$requiredLinesCountBetweenDifferentAnnotationsTypes = SniffSettingsHelper::normalizeInteger(
			$this->linesCountBetweenDifferentAnnotationsTypes
		);

		$tokens = $phpcsFile->getTokens();

		$indentation = IndentationHelper::getIndentation($phpcsFile, $docCommentOpenerPointer);

		$previousAnnotation = null;
		foreach ($annotations as $annotation) {
			if ($previousAnnotation === null) {
				$previousAnnotation = $annotation;
				continue;
			}

			if ($annotation->getName() === $previousAnnotation->getName()) {
				$previousAnnotation = $annotation;
				continue;
			}

			preg_match('~(\\s+)$~', $tokens[$previousAnnotation->getEndPointer()]['content'], $matches);

			$linesCountAfterPreviousAnnotation = $matches[1] ?? '';
			$linesCountAfterPreviousAnnotation .= TokenHelper::getContent(
				$phpcsFile,
				$previousAnnotation->getEndPointer() + 1,
				$annotation->getStartPointer() - 1
			);

			$linesCountAfterPreviousAnnotation = max(substr_count($linesCountAfterPreviousAnnotation, $phpcsFile->eolChar) - 1, 0);

			if ($linesCountAfterPreviousAnnotation === $requiredLinesCountBetweenDifferentAnnotationsTypes) {
				$previousAnnotation = $annotation;
				continue;
			}

			$fix = $phpcsFile->addFixableError(
				sprintf(
					'Expected %d lines between different annotations types, found %d.',
					$requiredLinesCountBetweenDifferentAnnotationsTypes,
					$linesCountAfterPreviousAnnotation
				),
				$annotation->getStartPointer(),
				self::CODE_INCORRECT_LINES_COUNT_BETWEEN_DIFFERENT_ANNOTATIONS_TYPES
			);

			if (!$fix) {
				$previousAnnotation = $annotation;
				continue;
			}

			$phpcsFile->fixer->beginChangeset();

			$phpcsFile->fixer->addNewline($previousAnnotation->getEndPointer());
			for ($i = $previousAnnotation->getEndPointer() + 1; $i < $annotation->getStartPointer(); $i++) {
				$phpcsFile->fixer->replaceToken($i, '');
			}

			for ($i = 1; $i <= $requiredLinesCountBetweenDifferentAnnotationsTypes; $i++) {
				$phpcsFile->fixer->addContent($previousAnnotation->getEndPointer(), sprintf('%s *%s', $indentation, $phpcsFile->eolChar));
			}

			$phpcsFile->fixer->addContentBefore($annotation->getStartPointer(), $indentation . ' * ');

			$phpcsFile->fixer->endChangeset();
		}
	}

	/**
	 * @param File $phpcsFile
	 * @param int $docCommentOpenerPointer
	 * @param Annotation[] $annotations
	 */
	private function checkAnnotationsGroups(File $phpcsFile, int $docCommentOpenerPointer, array $annotations): void
	{
		$tokens = $phpcsFile->getTokens();

		$annotationsGroups = [];
		$annotationsGroup = [];
		$previousAnnotation = null;
		foreach ($annotations as $annotation) {
			if (
				$previousAnnotation === null
				|| $tokens[$previousAnnotation->getEndPointer()]['line'] + 1 === $tokens[$annotation->getStartPointer()]['line']
			) {
				$annotationsGroup[] = $annotation;
				$previousAnnotation = $annotation;
				continue;
			}

			$annotationsGroups[] = $annotationsGroup;
			$annotationsGroup = [$annotation];
			$previousAnnotation = $annotation;
		}

		if (count($annotationsGroup) > 0) {
			$annotationsGroups[] = $annotationsGroup;
		}

		$this->checkAnnotationsGroupsOrder($phpcsFile, $docCommentOpenerPointer, $annotationsGroups, $annotations);
		$this->checkLinesBetweenAnnotationsGroups($phpcsFile, $docCommentOpenerPointer, $annotationsGroups);
	}

	/**
	 * @param File $phpcsFile
	 * @param int $docCommentOpenerPointer
	 * @param Annotation[][] $annotationsGroups
	 */
	private function checkLinesBetweenAnnotationsGroups(File $phpcsFile, int $docCommentOpenerPointer, array $annotationsGroups): void
	{
		$tokens = $phpcsFile->getTokens();

		$requiredLinesCountBetweenAnnotationsGroups = SniffSettingsHelper::normalizeInteger($this->linesCountBetweenAnnotationsGroups);

		$previousAnnotationsGroup = null;
		foreach ($annotationsGroups as $annotationsGroup) {
			if ($previousAnnotationsGroup === null) {
				$previousAnnotationsGroup = $annotationsGroup;
				continue;
			}

			/** @var Annotation $lastAnnotationInPreviousGroup */
			$lastAnnotationInPreviousGroup = $previousAnnotationsGroup[count($previousAnnotationsGroup) - 1];
			$firstAnnotationInActualGroup = $annotationsGroup[0];

			$actualLinesCountBetweenAnnotationsGroups = $tokens[$firstAnnotationInActualGroup->getStartPointer()]['line'] - $tokens[$lastAnnotationInPreviousGroup->getEndPointer()]['line'] - 1;
			if ($actualLinesCountBetweenAnnotationsGroups === $requiredLinesCountBetweenAnnotationsGroups) {
				$previousAnnotationsGroup = $annotationsGroup;
				continue;
			}

			$fix = $phpcsFile->addFixableError(
				sprintf(
					'Expected %d lines between annotations groups, found %d.',
					$requiredLinesCountBetweenAnnotationsGroups,
					$actualLinesCountBetweenAnnotationsGroups
				),
				$firstAnnotationInActualGroup->getStartPointer(),
				self::CODE_INCORRECT_LINES_COUNT_BETWEEN_ANNOTATIONS_GROUPS
			);

			if (!$fix) {
				$previousAnnotationsGroup = $annotationsGroup;
				continue;
			}

			$indentation = IndentationHelper::getIndentation($phpcsFile, $docCommentOpenerPointer);

			$phpcsFile->fixer->beginChangeset();

			$phpcsFile->fixer->addNewline($lastAnnotationInPreviousGroup->getEndPointer());
			for ($i = $lastAnnotationInPreviousGroup->getEndPointer() + 1; $i < $firstAnnotationInActualGroup->getStartPointer(); $i++) {
				$phpcsFile->fixer->replaceToken($i, '');
			}

			for ($i = 1; $i <= $requiredLinesCountBetweenAnnotationsGroups; $i++) {
				$phpcsFile->fixer->addContent(
					$lastAnnotationInPreviousGroup->getEndPointer(),
					sprintf('%s *%s', $indentation, $phpcsFile->eolChar)
				);
			}

			$phpcsFile->fixer->addContentBefore(
				$firstAnnotationInActualGroup->getStartPointer(),
				$indentation . ' * '
			);

			$phpcsFile->fixer->endChangeset();
		}
	}

	/**
	 * @param File $phpcsFile
	 * @param int $docCommentOpenerPointer
	 * @param Annotation[][] $annotationsGroups
	 * @param Annotation[] $annotations
	 */
	private function checkAnnotationsGroupsOrder(
		File $phpcsFile,
		int $docCommentOpenerPointer,
		array $annotationsGroups,
		array $annotations
	): void
	{
		$equals = static function (array $firstAnnotationsGroup, array $secondAnnotationsGroup): bool {
			$getAnnotationsPointers = static function (Annotation $annotation): int {
				return $annotation->getStartPointer();
			};

			$firstAnnotationsPointers = array_map($getAnnotationsPointers, $firstAnnotationsGroup);
			$secondAnnotationsPointers = array_map($getAnnotationsPointers, $secondAnnotationsGroup);

			return count(array_diff($firstAnnotationsPointers, $secondAnnotationsPointers)) === 0
				&& count(array_diff($secondAnnotationsPointers, $firstAnnotationsPointers)) === 0;
		};

		$sortedAnnotationsGroups = $this->sortAnnotationsToGroups($annotations);
		$incorrectAnnotationsGroupsExist = false;
		$annotationsGroupsPositions = [];

		$fix = false;
		$undefinedAnnotationsGroups = [];
		foreach ($annotationsGroups as $annotationsGroupPosition => $annotationsGroup) {
			foreach ($sortedAnnotationsGroups as $sortedAnnotationsGroupPosition => $sortedAnnotationsGroup) {
				if ($equals($annotationsGroup, $sortedAnnotationsGroup)) {
					$annotationsGroupsPositions[$annotationsGroupPosition] = $sortedAnnotationsGroupPosition;
					continue 2;
				}

				$undefinedAnnotationsGroup = true;
				foreach ($annotationsGroup as $annotation) {
					foreach ($this->getAnnotationsGroups() as $annotationNames) {
						foreach ($annotationNames as $annotationName) {
							if ($this->isAnnotationMatched($annotation, $annotationName)) {
								$undefinedAnnotationsGroup = false;
								break 3;
							}
						}
					}
				}

				if ($undefinedAnnotationsGroup) {
					$undefinedAnnotationsGroups[] = $annotationsGroupPosition;
					continue 2;
				}
			}

			$incorrectAnnotationsGroupsExist = true;

			$fix = $phpcsFile->addFixableError(
				'Incorrect annotations group.',
				$annotationsGroup[0]->getStartPointer(),
				self::CODE_INCORRECT_ANNOTATIONS_GROUP
			);
		}

		if (count($annotationsGroupsPositions) === 0 && count($undefinedAnnotationsGroups) > 1) {
			$incorrectAnnotationsGroupsExist = true;

			$fix = $phpcsFile->addFixableError(
				'Incorrect annotations group.',
				$annotationsGroups[0][0]->getStartPointer(),
				self::CODE_INCORRECT_ANNOTATIONS_GROUP
			);
		}

		if (!$incorrectAnnotationsGroupsExist) {
			foreach ($undefinedAnnotationsGroups as $undefinedAnnotationsGroupPosition) {
				$annotationsGroupsPositions[$undefinedAnnotationsGroupPosition] = (count($annotationsGroupsPositions) > 0 ? max(
					$annotationsGroupsPositions
				) : 0) + 1;
			}
			ksort($annotationsGroupsPositions);

			$positionsMappedToGroups = array_keys($annotationsGroupsPositions);
			$tmp = array_values($annotationsGroupsPositions);
			asort($tmp);
			/** @var int[] $normalizedAnnotationsGroupsPositions */
			$normalizedAnnotationsGroupsPositions = array_combine(array_keys($positionsMappedToGroups), array_keys($tmp));

			foreach ($normalizedAnnotationsGroupsPositions as $normalizedAnnotationsGroupPosition => $sortedAnnotationsGroupPosition) {
				if ($normalizedAnnotationsGroupPosition === $sortedAnnotationsGroupPosition) {
					continue;
				}

				$fix = $phpcsFile->addFixableError(
					'Incorrect order of annotations groups.',
					$annotationsGroups[$positionsMappedToGroups[$normalizedAnnotationsGroupPosition]][0]->getStartPointer(),
					self::CODE_INCORRECT_ORDER_OF_ANNOTATIONS_GROUPS
				);
				break;
			}
		}

		foreach ($annotationsGroups as $annotationsGroupPosition => $annotationsGroup) {
			if (!array_key_exists($annotationsGroupPosition, $annotationsGroupsPositions)) {
				continue;
			}

			if (!array_key_exists($annotationsGroupsPositions[$annotationsGroupPosition], $sortedAnnotationsGroups)) {
				continue;
			}

			$sortedAnnotationsGroup = $sortedAnnotationsGroups[$annotationsGroupsPositions[$annotationsGroupPosition]];

			foreach ($annotationsGroup as $annotationPosition => $annotation) {
				if ($annotation === $sortedAnnotationsGroup[$annotationPosition]) {
					continue;
				}

				$fix = $phpcsFile->addFixableError(
					'Incorrent order of annotations in group.',
					$annotation->getStartPointer(),
					self::CODE_INCORRECT_ORDER_OF_ANNOTATIONS_IN_GROUP
				);
				break;
			}
		}

		if (!$fix) {
			return;
		}

		$firstAnnotation = $annotationsGroups[0][0];
		$lastAnnotationsGroup = $annotationsGroups[count($annotationsGroups) - 1];
		$lastAnnotation = $lastAnnotationsGroup[count($lastAnnotationsGroup) - 1];

		$indentation = IndentationHelper::getIndentation($phpcsFile, $docCommentOpenerPointer);

		$fixedAnnotations = '';
		$firstGroup = true;
		foreach ($sortedAnnotationsGroups as $sortedAnnotationsGroup) {
			if ($firstGroup) {
				$firstGroup = false;
			} else {
				for ($i = 0; $i < SniffSettingsHelper::normalizeInteger($this->linesCountBetweenAnnotationsGroups); $i++) {
					$fixedAnnotations .= sprintf('%s *%s', $indentation, $phpcsFile->eolChar);
				}
			}

			foreach ($sortedAnnotationsGroup as $sortedAnnotation) {
				$fixedAnnotations .= sprintf(
					'%s * %s%s',
					$indentation,
					TokenHelper::getContent($phpcsFile, $sortedAnnotation->getStartPointer(), $sortedAnnotation->getEndPointer()),
					$phpcsFile->eolChar
				);
			}
		}

		$endOfLineBeforeFirstAnnotation = TokenHelper::findPreviousContent(
			$phpcsFile,
			T_DOC_COMMENT_WHITESPACE,
			$phpcsFile->eolChar,
			$firstAnnotation->getStartPointer() - 1,
			$docCommentOpenerPointer
		);
		$endOfLineAfterLastAnnotation = TokenHelper::findNextContent(
			$phpcsFile,
			T_DOC_COMMENT_WHITESPACE,
			$phpcsFile->eolChar,
			$lastAnnotation->getEndPointer() + 1
		);

		$phpcsFile->fixer->beginChangeset();
		if ($endOfLineBeforeFirstAnnotation === null) {
			$phpcsFile->fixer->replaceToken($docCommentOpenerPointer, '/**' . $phpcsFile->eolChar . $fixedAnnotations);
			for ($i = $docCommentOpenerPointer + 1; $i <= $endOfLineAfterLastAnnotation; $i++) {
				$phpcsFile->fixer->replaceToken($i, '');
			}
		} else {
			$phpcsFile->fixer->replaceToken($endOfLineBeforeFirstAnnotation + 1, $fixedAnnotations);
			for ($i = $endOfLineBeforeFirstAnnotation + 2; $i <= $endOfLineAfterLastAnnotation; $i++) {
				$phpcsFile->fixer->replaceToken($i, '');
			}
		}
		$phpcsFile->fixer->endChangeset();
	}

	/**
	 * @param Annotation[] $annotations
	 * @return Annotation[][]
	 */
	private function sortAnnotationsToGroups(array $annotations): array
	{
		$expectedAnnotationsGroups = $this->getAnnotationsGroups();

		$sortedAnnotationsGroups = [];
		$annotationsNotInAnyGroup = [];
		foreach ($annotations as $annotation) {
			foreach ($expectedAnnotationsGroups as $annotationsGroupPosition => $annotationsGroup) {
				foreach ($annotationsGroup as $annotationName) {
					if ($this->isAnnotationMatched($annotation, $annotationName)) {
						$sortedAnnotationsGroups[$annotationsGroupPosition][] = $annotation;
						continue 3;
					}
				}
			}

			$annotationsNotInAnyGroup[] = $annotation;
		}

		ksort($sortedAnnotationsGroups);

		foreach (array_keys($sortedAnnotationsGroups) as $annotationsGroupPosition) {
			$expectedAnnotationsGroupOrder = array_flip($expectedAnnotationsGroups[$annotationsGroupPosition]);
			usort(
				$sortedAnnotationsGroups[$annotationsGroupPosition],
				function (Annotation $firstAnnotation, Annotation $secondAnnotation) use ($expectedAnnotationsGroupOrder): int {
					$getExpectedOrder = function (string $annotationName) use ($expectedAnnotationsGroupOrder): int {
						if (array_key_exists($annotationName, $expectedAnnotationsGroupOrder)) {
							return $expectedAnnotationsGroupOrder[$annotationName];
						}

						foreach ($expectedAnnotationsGroupOrder as $expectedAnnotationName => $expectedAnnotationOrder) {
							if ($this->isAnnotationNameInAnnotationNamespace($expectedAnnotationName, $annotationName)) {
								return $expectedAnnotationOrder;
							}
						}
					};

					$expectedOrder = $getExpectedOrder($firstAnnotation->getName()) <=> $getExpectedOrder($secondAnnotation->getName());

					return $expectedOrder !== 0
						? $expectedOrder
						: $firstAnnotation->getStartPointer() <=> $secondAnnotation->getStartPointer();
				}
			);
		}

		if (count($annotationsNotInAnyGroup) > 0) {
			$sortedAnnotationsGroups[] = $annotationsNotInAnyGroup;
		}

		return $sortedAnnotationsGroups;
	}

	private function isAnnotationNameInAnnotationNamespace(string $annotationNamespace, string $annotationName): bool
	{
		return in_array(substr($annotationNamespace, -1), ['\\', '-', ':'], true) && strpos($annotationName, $annotationNamespace) === 0;
	}

	private function isAnnotationMatched(Annotation $annotation, string $annotationName): bool
	{
		if ($annotation->getName() === $annotationName) {
			return true;
		}

		return $this->isAnnotationNameInAnnotationNamespace($annotationName, $annotation->getName());
	}

	private function checkLinesAfterLastContent(
		File $phpcsFile,
		int $docCommentOpenerPointer,
		int $docCommentCloserPointer,
		int $lastContentEndPointer
	): void
	{
		$whitespaceAfterLastContent = TokenHelper::getContent($phpcsFile, $lastContentEndPointer + 1, $docCommentCloserPointer);

		$requiredLinesCountAfterLastContent = SniffSettingsHelper::normalizeInteger($this->linesCountAfterLastContent);
		$linesCountAfterLastContent = max(substr_count($whitespaceAfterLastContent, $phpcsFile->eolChar) - 1, 0);
		if ($linesCountAfterLastContent === $requiredLinesCountAfterLastContent) {
			return;
		}

		$fix = $phpcsFile->addFixableError(
			sprintf('Expected %d lines after last content, found %d.', $requiredLinesCountAfterLastContent, $linesCountAfterLastContent),
			$lastContentEndPointer,
			self::CODE_INCORRECT_LINES_COUNT_AFTER_LAST_CONTENT
		);

		if (!$fix) {
			return;
		}

		$indentation = IndentationHelper::getIndentation($phpcsFile, $docCommentOpenerPointer);

		$phpcsFile->fixer->beginChangeset();

		$phpcsFile->fixer->addNewline($lastContentEndPointer);
		for ($i = $lastContentEndPointer + 1; $i < $docCommentCloserPointer; $i++) {
			$phpcsFile->fixer->replaceToken($i, '');
		}

		for ($i = 1; $i <= $requiredLinesCountAfterLastContent; $i++) {
			$phpcsFile->fixer->addContent($lastContentEndPointer, sprintf('%s *%s', $indentation, $phpcsFile->eolChar));
		}

		$phpcsFile->fixer->addContentBefore($docCommentCloserPointer, $indentation . ' ');

		$phpcsFile->fixer->endChangeset();
	}

	/**
	 * @return string[][]
	 */
	private function getAnnotationsGroups(): array
	{
		if ($this->normalizedAnnotationsGroups === null) {
			$this->normalizedAnnotationsGroups = [];
			foreach ($this->annotationsGroups as $annotationsGroup) {
				$this->normalizedAnnotationsGroups[] = SniffSettingsHelper::normalizeArray(explode(',', $annotationsGroup));
			}
		}

		return $this->normalizedAnnotationsGroups;
	}

}
