<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Namespaces;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use SlevomatCodingStandard\Helpers\NamespaceHelper;
use SlevomatCodingStandard\Helpers\ReferencedNameHelper;
use SlevomatCodingStandard\Helpers\SniffSettingsHelper;
use SlevomatCodingStandard\Helpers\StringHelper;
use SlevomatCodingStandard\Helpers\TokenHelper;
use SlevomatCodingStandard\Helpers\UseStatement;
use SlevomatCodingStandard\Helpers\UseStatementHelper;
use Throwable;
use function array_reverse;
use function in_array;
use function sprintf;
use const T_OPEN_TAG;

class FullyQualifiedExceptionsSniff implements Sniff
{

	public const CODE_NON_FULLY_QUALIFIED_EXCEPTION = 'NonFullyQualifiedException';

	/** @var string[] */
	public $specialExceptionNames = [];

	/** @var string[] */
	public $ignoredNames = [];

	/** @var string[]|null */
	private $normalizedSpecialExceptionNames;

	/** @var string[]|null */
	private $normalizedIgnoredNames;

	/**
	 * @return array<int, (int|string)>
	 */
	public function register(): array
	{
		return [
			T_OPEN_TAG,
		];
	}

	/**
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
	 * @param File $phpcsFile
	 * @param int $openTagPointer
	 */
	public function process(File $phpcsFile, $openTagPointer): void
	{
		if (TokenHelper::findPrevious($phpcsFile, T_OPEN_TAG, $openTagPointer - 1) !== null) {
			return;
		}

		$namespacePointers = array_reverse(NamespaceHelper::getAllNamespacesPointers($phpcsFile));

		$referencedNames = ReferencedNameHelper::getAllReferencedNames($phpcsFile, $openTagPointer);
		foreach ($referencedNames as $referencedName) {
			$pointer = $referencedName->getStartPointer();
			$name = $referencedName->getNameAsReferencedInFile();
			$uniqueId = UseStatement::getUniqueId($referencedName->getType(), $name);
			$useStatements = UseStatementHelper::getUseStatementsForPointer($phpcsFile, $pointer);

			if (
				isset($useStatements[$uniqueId])
				&& $referencedName->hasSameUseStatementType($useStatements[$uniqueId])
			) {
				$useStatement = $useStatements[$uniqueId];
				if (
					in_array($useStatement->getFullyQualifiedTypeName(), $this->getIgnoredNames(), true)
					|| (
						!StringHelper::endsWith($useStatement->getFullyQualifiedTypeName(), 'Exception')
						&& $useStatement->getFullyQualifiedTypeName() !== Throwable::class
						&& (!StringHelper::endsWith($useStatement->getFullyQualifiedTypeName(), 'Error') || NamespaceHelper::hasNamespace(
							$useStatement->getFullyQualifiedTypeName()
						))
						&& !in_array($useStatement->getFullyQualifiedTypeName(), $this->getSpecialExceptionNames(), true)
					)
				) {
					continue;
				}
			} else {
				$fileNamespacePointer = null;
				if ($namespacePointers !== []) {
					foreach ($namespacePointers as $namespacePointer) {
						if ($namespacePointer < $pointer) {
							$fileNamespacePointer = $namespacePointer;
							break;
						}
					}
				}

				$fileNamespace = $fileNamespacePointer !== null
					? NamespaceHelper::getName($phpcsFile, $fileNamespacePointer)
					: null;
				$canonicalName = $name;
				if (!NamespaceHelper::isFullyQualifiedName($name) && $fileNamespace !== null) {
					$canonicalName = sprintf('%s%s%s', $fileNamespace, NamespaceHelper::NAMESPACE_SEPARATOR, $name);
				}
				if (
					in_array($canonicalName, $this->getIgnoredNames(), true)
					|| (
						!StringHelper::endsWith($name, 'Exception')
						&& $name !== Throwable::class
						&& (!StringHelper::endsWith($canonicalName, 'Error') || NamespaceHelper::hasNamespace($canonicalName))
						&& !in_array($canonicalName, $this->getSpecialExceptionNames(), true)
					)
				) {
					continue;
				}
			}

			if (NamespaceHelper::isFullyQualifiedName($name)) {
				continue;
			}

			$fix = $phpcsFile->addFixableError(sprintf(
				'Exception %s should be referenced via a fully qualified name.',
				$name
			), $pointer, self::CODE_NON_FULLY_QUALIFIED_EXCEPTION);
			if (!$fix) {
				continue;
			}

			$fullyQualifiedName = NamespaceHelper::resolveClassName($phpcsFile, $name, $pointer);

			$phpcsFile->fixer->beginChangeset();

			for ($i = $referencedName->getStartPointer(); $i <= $referencedName->getEndPointer(); $i++) {
				$phpcsFile->fixer->replaceToken($i, '');
			}
			$phpcsFile->fixer->addContent($referencedName->getStartPointer(), $fullyQualifiedName);

			$phpcsFile->fixer->endChangeset();
		}
	}

	/**
	 * @return string[]
	 */
	private function getSpecialExceptionNames(): array
	{
		if ($this->normalizedSpecialExceptionNames === null) {
			$this->normalizedSpecialExceptionNames = SniffSettingsHelper::normalizeArray($this->specialExceptionNames);
		}

		return $this->normalizedSpecialExceptionNames;
	}

	/**
	 * @return string[]
	 */
	private function getIgnoredNames(): array
	{
		if ($this->normalizedIgnoredNames === null) {
			$this->normalizedIgnoredNames = SniffSettingsHelper::normalizeArray($this->ignoredNames);
		}

		return $this->normalizedIgnoredNames;
	}

}
