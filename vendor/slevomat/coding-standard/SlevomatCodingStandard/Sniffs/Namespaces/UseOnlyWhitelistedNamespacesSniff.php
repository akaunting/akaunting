<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Namespaces;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use SlevomatCodingStandard\Helpers\NamespaceHelper;
use SlevomatCodingStandard\Helpers\SniffSettingsHelper;
use SlevomatCodingStandard\Helpers\UseStatementHelper;
use function sprintf;
use const T_USE;

class UseOnlyWhitelistedNamespacesSniff implements Sniff
{

	public const CODE_NON_FULLY_QUALIFIED = 'NonFullyQualified';

	/** @var bool */
	public $allowUseFromRootNamespace = false;

	/** @var string[] */
	public $namespacesRequiredToUse = [];

	/** @var string[]|null */
	private $normalizedNamespacesRequiredToUse;

	/**
	 * @return array<int, (int|string)>
	 */
	public function register(): array
	{
		return [
			T_USE,
		];
	}

	/**
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
	 * @param File $phpcsFile
	 * @param int $usePointer
	 */
	public function process(File $phpcsFile, $usePointer): void
	{
		if (
			UseStatementHelper::isAnonymousFunctionUse($phpcsFile, $usePointer)
			|| UseStatementHelper::isTraitUse($phpcsFile, $usePointer)
		) {
			return;
		}

		$className = UseStatementHelper::getFullyQualifiedTypeNameFromUse($phpcsFile, $usePointer);
		if ($this->allowUseFromRootNamespace && !NamespaceHelper::isQualifiedName($className)) {
			return;
		}

		foreach ($this->getNamespacesRequiredToUse() as $namespace) {
			if (!NamespaceHelper::isTypeInNamespace($className, $namespace)) {
				continue;
			}

			return;
		}

		$phpcsFile->addError(sprintf(
			'Type %s should not be used, but referenced via a fully qualified name.',
			$className
		), $usePointer, self::CODE_NON_FULLY_QUALIFIED);
	}

	/**
	 * @return string[]
	 */
	private function getNamespacesRequiredToUse(): array
	{
		if ($this->normalizedNamespacesRequiredToUse === null) {
			$this->normalizedNamespacesRequiredToUse = SniffSettingsHelper::normalizeArray($this->namespacesRequiredToUse);
		}

		return $this->normalizedNamespacesRequiredToUse;
	}

}
