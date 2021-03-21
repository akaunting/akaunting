<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Files;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use SlevomatCodingStandard\Helpers\ClassHelper;
use SlevomatCodingStandard\Helpers\NamespaceHelper;
use SlevomatCodingStandard\Helpers\SniffSettingsHelper;
use SlevomatCodingStandard\Helpers\StringHelper;
use SlevomatCodingStandard\Helpers\TokenHelper;
use function count;
use function explode;
use function min;
use function sprintf;
use function str_replace;
use function strcasecmp;
use function strlen;
use function substr;
use function ucfirst;
use function uksort;
use const DIRECTORY_SEPARATOR;
use const T_CLASS;
use const T_INTERFACE;
use const T_STRING;
use const T_TRAIT;

class TypeNameMatchesFileNameSniff implements Sniff
{

	public const CODE_NO_MATCH_BETWEEN_TYPE_NAME_AND_FILE_NAME = 'NoMatchBetweenTypeNameAndFileName';

	/** @var array<string, string> */
	public $rootNamespaces = [];

	/** @var string[] */
	public $skipDirs = [];

	/** @var string[] */
	public $ignoredNamespaces = [];

	/** @var string[] */
	public $extensions = ['php'];

	/** @var array<string, string>|null */
	private $normalizedRootNamespaces;

	/** @var string[]|null */
	private $normalizedSkipDirs;

	/** @var string[]|null */
	private $normalizedIgnoredNamespaces;

	/** @var string[]|null */
	private $normalizedExtensions;

	/** @var FilepathNamespaceExtractor */
	private $namespaceExtractor;

	/**
	 * @return array<int, (int|string)>
	 */
	public function register(): array
	{
		return [
			T_CLASS,
			T_INTERFACE,
			T_TRAIT,
		];
	}

	/**
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
	 * @param File $phpcsFile
	 * @param int $typePointer
	 */
	public function process(File $phpcsFile, $typePointer): void
	{
		$tokens = $phpcsFile->getTokens();

		/** @var int $namePointer */
		$namePointer = TokenHelper::findNext($phpcsFile, T_STRING, $typePointer + 1);

		$typeName = NamespaceHelper::normalizeToCanonicalName(ClassHelper::getFullyQualifiedName($phpcsFile, $typePointer));

		foreach ($this->getIgnoredNamespaces() as $ignoredNamespace) {
			if (!StringHelper::startsWith($typeName, $ignoredNamespace . '\\')) {
				continue;
			}

			return;
		}

		$filename = str_replace('/', DIRECTORY_SEPARATOR, $phpcsFile->getFilename());
		$basePath = str_replace('/', DIRECTORY_SEPARATOR, $phpcsFile->config->basepath ?? '');
		if ($basePath !== '' && StringHelper::startsWith($filename, $basePath)) {
			$filename = substr($filename, strlen($basePath));
		}

		$expectedTypeName = $this->getNamespaceExtractor()->getTypeNameFromProjectPath($filename);
		if ($typeName === $expectedTypeName) {
			return;
		}

		$phpcsFile->addError(
			sprintf(
				'%s name %s does not match filepath %s.',
				ucfirst($tokens[$typePointer]['content']),
				$typeName,
				$phpcsFile->getFilename()
			),
			$namePointer,
			self::CODE_NO_MATCH_BETWEEN_TYPE_NAME_AND_FILE_NAME
		);
	}

	/**
	 * @return string[] path(string) => namespace
	 */
	private function getRootNamespaces(): array
	{
		if ($this->normalizedRootNamespaces === null) {
			/** @var array<string, string> $normalizedRootNamespaces */
			$normalizedRootNamespaces = SniffSettingsHelper::normalizeAssociativeArray($this->rootNamespaces);
			$this->normalizedRootNamespaces = $normalizedRootNamespaces;
			uksort($this->normalizedRootNamespaces, static function (string $a, string $b): int {
				$aParts = explode('/', str_replace('\\', '/', $a));
				$bParts = explode('/', str_replace('\\', '/', $b));

				$minPartsCount = min(count($aParts), count($bParts));
				for ($i = 0; $i < $minPartsCount; $i++) {
					$comparison = strcasecmp($bParts[$i], $aParts[$i]);
					if ($comparison === 0) {
						continue;
					}

					return $comparison;
				}

				return count($bParts) <=> count($aParts);
			});
		}

		return $this->normalizedRootNamespaces;
	}

	/**
	 * @return string[]
	 */
	private function getSkipDirs(): array
	{
		if ($this->normalizedSkipDirs === null) {
			$this->normalizedSkipDirs = SniffSettingsHelper::normalizeArray($this->skipDirs);
		}

		return $this->normalizedSkipDirs;
	}

	/**
	 * @return string[]
	 */
	private function getIgnoredNamespaces(): array
	{
		if ($this->normalizedIgnoredNamespaces === null) {
			$this->normalizedIgnoredNamespaces = SniffSettingsHelper::normalizeArray($this->ignoredNamespaces);
		}

		return $this->normalizedIgnoredNamespaces;
	}

	/**
	 * @return string[]
	 */
	private function getExtensions(): array
	{
		if ($this->normalizedExtensions === null) {
			$this->normalizedExtensions = SniffSettingsHelper::normalizeArray($this->extensions);
		}

		return $this->normalizedExtensions;
	}

	private function getNamespaceExtractor(): FilepathNamespaceExtractor
	{
		if ($this->namespaceExtractor === null) {
			$this->namespaceExtractor = new FilepathNamespaceExtractor(
				$this->getRootNamespaces(),
				$this->getSkipDirs(),
				$this->getExtensions()
			);
		}

		return $this->namespaceExtractor;
	}

}
