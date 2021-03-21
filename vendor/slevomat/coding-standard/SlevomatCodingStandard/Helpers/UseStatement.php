<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Helpers;

use function sprintf;
use function strtolower;

/**
 * @internal
 */
class UseStatement
{

	public const TYPE_DEFAULT = ReferencedName::TYPE_DEFAULT;
	public const TYPE_FUNCTION = ReferencedName::TYPE_FUNCTION;
	public const TYPE_CONSTANT = ReferencedName::TYPE_CONSTANT;

	/** @var string */
	private $nameAsReferencedInFile;

	/** @var string */
	private $normalizedNameAsReferencedInFile;

	/** @var string */
	private $fullyQualifiedTypeName;

	/** @var int */
	private $usePointer;

	/** @var string */
	private $type;

	/** @var string|null */
	private $alias;

	public function __construct(
		string $nameAsReferencedInFile,
		string $fullyQualifiedClassName,
		int $usePointer,
		string $type,
		?string $alias
	)
	{
		$this->nameAsReferencedInFile = $nameAsReferencedInFile;
		$this->normalizedNameAsReferencedInFile = self::normalizedNameAsReferencedInFile($type, $nameAsReferencedInFile);
		$this->fullyQualifiedTypeName = $fullyQualifiedClassName;
		$this->usePointer = $usePointer;
		$this->type = $type;
		$this->alias = $alias;
	}

	public function getNameAsReferencedInFile(): string
	{
		return $this->nameAsReferencedInFile;
	}

	public function getCanonicalNameAsReferencedInFile(): string
	{
		return $this->normalizedNameAsReferencedInFile;
	}

	public function getFullyQualifiedTypeName(): string
	{
		return $this->fullyQualifiedTypeName;
	}

	public function getPointer(): int
	{
		return $this->usePointer;
	}

	public function getType(): string
	{
		return $this->type;
	}

	public function getAlias(): ?string
	{
		return $this->alias;
	}

	public function isConstant(): bool
	{
		return $this->type === self::TYPE_CONSTANT;
	}

	public function isFunction(): bool
	{
		return $this->type === self::TYPE_FUNCTION;
	}

	public function hasSameType(self $that): bool
	{
		return $this->type === $that->type;
	}

	public static function getUniqueId(string $type, string $name): string
	{
		$normalizedName = self::normalizedNameAsReferencedInFile($type, $name);

		if ($type === self::TYPE_DEFAULT) {
			return $normalizedName;
		}

		return sprintf('%s %s', $type, $normalizedName);
	}

	public static function normalizedNameAsReferencedInFile(string $type, string $name): string
	{
		if ($type === self::TYPE_CONSTANT) {
			return $name;
		}

		return strtolower($name);
	}

	public static function getTypeName(string $type): ?string
	{
		if ($type === self::TYPE_CONSTANT) {
			return 'const';
		}

		if ($type === self::TYPE_FUNCTION) {
			return 'function';
		}

		return null;
	}

}
