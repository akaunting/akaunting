<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Helpers;

use PHP_CodeSniffer\Files\File;
use SlevomatCodingStandard\Helpers\Annotation\Annotation;
use function array_reduce;
use function sprintf;
use function strpos;

class SuppressHelper
{

	public const ANNOTATION = '@phpcsSuppress';

	public static function isSniffSuppressed(File $phpcsFile, int $pointer, string $suppressName): bool
	{
		return array_reduce(
			AnnotationHelper::getAnnotationsByName($phpcsFile, $pointer, self::ANNOTATION),
			static function (bool $carry, Annotation $annotation) use ($suppressName): bool {
				if (
					$annotation->getContent() === $suppressName
					|| strpos($suppressName, sprintf('%s.', $annotation->getContent())) === 0
				) {
					$carry = true;
				}
				return $carry;
			},
			false
		);
	}

}
