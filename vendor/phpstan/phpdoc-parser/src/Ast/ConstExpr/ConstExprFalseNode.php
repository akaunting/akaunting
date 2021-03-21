<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Ast\ConstExpr;

class ConstExprFalseNode implements ConstExprNode
{

	public function __toString(): string
	{
		return 'false';
	}

}
