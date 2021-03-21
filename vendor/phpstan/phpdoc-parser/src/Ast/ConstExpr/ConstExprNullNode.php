<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Ast\ConstExpr;

class ConstExprNullNode implements ConstExprNode
{

	public function __toString(): string
	{
		return 'null';
	}

}
