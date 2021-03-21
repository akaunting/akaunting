<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Ast\Type;

class IdentifierTypeNode implements TypeNode
{

	/** @var string */
	public $name;

	public function __construct(string $name)
	{
		$this->name = $name;
	}


	public function __toString(): string
	{
		return $this->name;
	}

}
