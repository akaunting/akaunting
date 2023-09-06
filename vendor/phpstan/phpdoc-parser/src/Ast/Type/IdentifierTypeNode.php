<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Ast\Type;

use PHPStan\PhpDocParser\Ast\NodeAttributes;

class IdentifierTypeNode implements TypeNode
{

	use NodeAttributes;

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
