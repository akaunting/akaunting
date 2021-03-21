<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Namespaces;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use SlevomatCodingStandard\Helpers\NamespaceHelper;
use SlevomatCodingStandard\Helpers\ReferencedNameHelper;
use SlevomatCodingStandard\Helpers\SniffSettingsHelper;
use SlevomatCodingStandard\Helpers\TokenHelper;
use function array_map;
use function array_merge;
use function array_values;
use function constant;
use function count;
use function defined;
use function in_array;
use function sprintf;
use function ucfirst;
use const T_COMMA;
use const T_IMPLEMENTS;
use const T_NAMESPACE;
use const T_USE;
use const T_WHITESPACE;

class FullyQualifiedClassNameAfterKeywordSniff implements Sniff
{

	public const CODE_NON_FULLY_QUALIFIED = 'NonFullyQualified%s';

	/**
	 * Token types as a strings (e.g. "T_IMPLEMENTS")
	 *
	 * @var string[]
	 */
	public $keywordsToCheck = [];

	/** @var string[]|null */
	private $normalizedKeywordsToCheck;

	/**
	 * @return array<int, (int|string)>
	 */
	public function register(): array
	{
		if (count($this->getKeywordsToCheck()) === 0) {
			return [];
		}
		return array_values(array_map(static function (string $keyword) {
			if (!defined($keyword)) {
				throw new UndefinedKeywordTokenException($keyword);
			}
			return constant($keyword);
		}, $this->getKeywordsToCheck()));
	}

	/**
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
	 * @param File $phpcsFile
	 * @param int $keywordPointer
	 */
	public function process(File $phpcsFile, $keywordPointer): void
	{
		$tokens = $phpcsFile->getTokens();

		$nameTokenCodes = TokenHelper::getNameTokenCodes();

		/** @var int $nameStartPointer */
		$nameStartPointer = TokenHelper::findNextEffective($phpcsFile, $keywordPointer + 1);
		if (!in_array($tokens[$nameStartPointer]['code'], $nameTokenCodes, true)) {
			return;
		}

		$possibleCommaPointer = $this->checkReferencedName($phpcsFile, $keywordPointer, $nameStartPointer);

		if (!in_array($tokens[$keywordPointer]['code'], [T_IMPLEMENTS, T_USE], true)) {
			return;
		}

		while (true) {
			$possibleCommaPointer = TokenHelper::findNextExcluding(
				$phpcsFile,
				array_merge($nameTokenCodes, [T_WHITESPACE]),
				$possibleCommaPointer
			);
			if ($possibleCommaPointer !== null) {
				$possibleCommaToken = $tokens[$possibleCommaPointer];
				if ($possibleCommaToken['code'] === T_COMMA) {
					/** @var int $nameStartPointer */
					$nameStartPointer = TokenHelper::findNextEffective($phpcsFile, $possibleCommaPointer + 1);
					$possibleCommaPointer = $this->checkReferencedName($phpcsFile, $keywordPointer, $nameStartPointer);
					continue;
				}
			}

			break;
		}
	}

	public static function getErrorCode(string $keyword): string
	{
		return sprintf(self::CODE_NON_FULLY_QUALIFIED, ucfirst($keyword));
	}

	/**
	 * @return string[]
	 */
	private function getKeywordsToCheck(): array
	{
		if ($this->normalizedKeywordsToCheck === null) {
			$this->normalizedKeywordsToCheck = SniffSettingsHelper::normalizeArray($this->keywordsToCheck);
		}

		return $this->normalizedKeywordsToCheck;
	}

	private function checkReferencedName(File $phpcsFile, int $keywordPointer, int $nameStartPointer): int
	{
		$tokens = $phpcsFile->getTokens();

		if ($tokens[$keywordPointer]['code'] === T_USE) {
			$conditions = $tokens[$keywordPointer]['conditions'];

			if (count($conditions) === 0) {
				return $nameStartPointer + 1;
			}

			$lastCondition = array_values($conditions)[count($conditions) - 1];
			if ($lastCondition === T_NAMESPACE) {
				return $nameStartPointer + 1;
			}
		}

		$endPointer = ReferencedNameHelper::getReferencedNameEndPointer($phpcsFile, $nameStartPointer);
		if (!NamespaceHelper::isFullyQualifiedPointer($phpcsFile, $nameStartPointer)) {
			$name = ReferencedNameHelper::getReferenceName($phpcsFile, $nameStartPointer, $endPointer);
			$keyword = $tokens[$keywordPointer]['content'];

			$fix = $phpcsFile->addFixableError(sprintf(
				'Type %s in %s statement should be referenced via a fully qualified name.',
				$name,
				$keyword
			), $keywordPointer, self::getErrorCode($keyword));
			if ($fix) {

				$fullyQualifiedName = NamespaceHelper::resolveClassName($phpcsFile, $name, $nameStartPointer);

				$phpcsFile->fixer->beginChangeset();

				for ($i = $nameStartPointer; $i <= $endPointer; $i++) {
					$phpcsFile->fixer->replaceToken($i, '');
				}
				$phpcsFile->fixer->addContent($nameStartPointer, $fullyQualifiedName);

				$phpcsFile->fixer->endChangeset();
			}
		}

		return $endPointer;
	}

}
