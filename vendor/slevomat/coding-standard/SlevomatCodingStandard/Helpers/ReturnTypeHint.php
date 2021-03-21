<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Helpers;

/**
 * @internal
 */
class ReturnTypeHint
{

	/** @var string */
	private $typeHint;

	/** @var bool */
	private $nullable;

	/** @var int */
	private $startPointer;

	/** @var int */
	private $endPointer;

	public function __construct(string $typeHint, bool $nullable, int $startPointer, int $endPointer)
	{
		$this->typeHint = $typeHint;
		$this->nullable = $nullable;
		$this->startPointer = $startPointer;
		$this->endPointer = $endPointer;
	}

	public function getTypeHint(): string
	{
		return $this->typeHint;
	}

	public function isNullable(): bool
	{
		return $this->nullable;
	}

	public function getStartPointer(): int
	{
		return $this->startPointer;
	}

	public function getEndPointer(): int
	{
		return $this->endPointer;
	}

}
