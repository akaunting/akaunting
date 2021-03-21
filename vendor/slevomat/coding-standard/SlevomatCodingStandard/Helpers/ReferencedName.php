<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Helpers;

/**
 * @internal
 */
class ReferencedName
{

	public const TYPE_DEFAULT = 'default';
	public const TYPE_FUNCTION = 'function';
	public const TYPE_CONSTANT = 'constant';

	/** @var string */
	private $nameAsReferencedInFile;

	/** @var int */
	private $startPointer;

	/** @var int */
	private $endPointer;

	/** @var string */
	private $type;

	public function __construct(string $nameAsReferencedInFile, int $startPointer, int $endPointer, string $type)
	{
		$this->nameAsReferencedInFile = $nameAsReferencedInFile;
		$this->startPointer = $startPointer;
		$this->endPointer = $endPointer;
		$this->type = $type;
	}

	public function getNameAsReferencedInFile(): string
	{
		return $this->nameAsReferencedInFile;
	}

	public function getStartPointer(): int
	{
		return $this->startPointer;
	}

	public function getType(): string
	{
		return $this->type;
	}

	public function getEndPointer(): int
	{
		return $this->endPointer;
	}

	public function isClass(): bool
	{
		return $this->type === self::TYPE_DEFAULT;
	}

	public function isConstant(): bool
	{
		return $this->type === self::TYPE_CONSTANT;
	}

	public function isFunction(): bool
	{
		return $this->type === self::TYPE_FUNCTION;
	}

	public function hasSameUseStatementType(UseStatement $useStatement): bool
	{
		return $this->getType() === $useStatement->getType();
	}

}
