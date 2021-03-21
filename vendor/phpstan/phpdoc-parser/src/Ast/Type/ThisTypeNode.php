<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Ast\Type;

class ThisTypeNode implements TypeNode
{

	public function __toString(): string
	{
		return '$this';
	}

}
