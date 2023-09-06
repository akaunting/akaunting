<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Ast\Type;

use PHPStan\PhpDocParser\Ast\NodeAttributes;

class NullableTypeNode implements TypeNode
{

	use NodeAttributes;

	/** @var TypeNode */
	public $type;

	public function __construct(TypeNode $type)
	{
		$this->type = $type;
	}


	public function __toString(): string
	{
		return '?' . $this->type;
	}

}
