<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Ast\Type;

use PHPStan\PhpDocParser\Ast\NodeAttributes;

class ThisTypeNode implements TypeNode
{

	use NodeAttributes;

	public function __toString(): string
	{
		return '$this';
	}

}
