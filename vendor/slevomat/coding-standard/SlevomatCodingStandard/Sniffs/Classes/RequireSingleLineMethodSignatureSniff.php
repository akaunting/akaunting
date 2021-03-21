<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Classes;

use Exception;
use PHP_CodeSniffer\Files\File;
use SlevomatCodingStandard\Helpers\FunctionHelper;
use SlevomatCodingStandard\Helpers\SniffSettingsHelper;
use function count;
use function preg_match;
use function sprintf;
use function strlen;

class RequireSingleLineMethodSignatureSniff extends AbstractMethodSignature
{

	public const CODE_REQUIRED_SINGLE_LINE_SIGNATURE = 'RequiredSingleLineSignature';

	/** @var int */
	public $maxLineLength = 120;

	/** @var string[] */
	public $includedMethodPatterns = [];

	/** @var string[]|null */
	public $includedMethodNormalizedPatterns;

	/** @var string[] */
	public $excludedMethodPatterns = [];

	/** @var string[]|null */
	public $excludedMethodNormalizedPatterns;

	/**
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
	 * @param File $phpcsFile
	 * @param int $methodPointer
	 */
	public function process(File $phpcsFile, $methodPointer): void
	{
		if (!FunctionHelper::isMethod($phpcsFile, $methodPointer)) {
			return;
		}

		$tokens = $phpcsFile->getTokens();

		[$signatureStartPointer, $signatureEndPointer] = $this->getSignatureStartAndEndPointers($phpcsFile, $methodPointer);

		if ($tokens[$signatureStartPointer]['line'] === $tokens[$signatureEndPointer]['line']) {
			return;
		}

		$signature = $this->getSignature($phpcsFile, $signatureStartPointer, $signatureEndPointer);
		$signatureWithoutTabIndentation = $this->getSignatureWithoutTabs($phpcsFile, $signature);
		$methodName = FunctionHelper::getName($phpcsFile, $methodPointer);

		if (
			count($this->includedMethodPatterns) !== 0
			&& !$this->isMethodNameInPatterns($methodName, $this->getIncludedMethodNormalizedPatterns())
		) {
			return;
		}

		if (
			count($this->excludedMethodPatterns) !== 0
			&& $this->isMethodNameInPatterns($methodName, $this->getExcludedMethodNormalizedPatterns())
		) {
			return;
		}

		$maxLineLength = SniffSettingsHelper::normalizeInteger($this->maxLineLength);
		if ($maxLineLength !== 0 && strlen($signatureWithoutTabIndentation) > $maxLineLength) {
			return;
		}

		$error = sprintf('Signature of method "%s" should be placed on a single line.', $methodName);
		$fix = $phpcsFile->addFixableError($error, $methodPointer, self::CODE_REQUIRED_SINGLE_LINE_SIGNATURE);
		if (!$fix) {
			return;
		}

		$phpcsFile->fixer->beginChangeset();

		$phpcsFile->fixer->replaceToken($signatureStartPointer, $signature);

		for ($i = $signatureStartPointer + 1; $i <= $signatureEndPointer; $i++) {
			$phpcsFile->fixer->replaceToken($i, '');
		}

		$phpcsFile->fixer->endChangeset();
	}

	/**
	 * @param string $methodName
	 * @param string[] $normalizedPatterns
	 * @return bool
	 */
	private function isMethodNameInPatterns(string $methodName, array $normalizedPatterns): bool
	{
		foreach ($normalizedPatterns as $pattern) {
			if (!SniffSettingsHelper::isValidRegularExpression($pattern)) {
				throw new Exception(sprintf('%s is not valid PCRE pattern.', $pattern));
			}

			if (preg_match($pattern, $methodName) !== 0) {
				return true;
			}
		}

		return false;
	}

	/**
	 * @return string[]
	 */
	private function getIncludedMethodNormalizedPatterns(): array
	{
		if ($this->includedMethodNormalizedPatterns === null) {
			$this->includedMethodNormalizedPatterns = SniffSettingsHelper::normalizeArray($this->includedMethodPatterns);
		}
		return $this->includedMethodNormalizedPatterns;
	}

	/**
	 * @return string[]
	 */
	private function getExcludedMethodNormalizedPatterns(): array
	{
		if ($this->excludedMethodNormalizedPatterns === null) {
			$this->excludedMethodNormalizedPatterns = SniffSettingsHelper::normalizeArray($this->excludedMethodPatterns);
		}
		return $this->excludedMethodNormalizedPatterns;
	}

}
