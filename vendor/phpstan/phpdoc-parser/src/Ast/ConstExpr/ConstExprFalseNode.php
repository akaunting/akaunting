<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Ast\ConstExpr;

use PHPStan\PhpDocParser\Ast\NodeAttributes;

class ConstExprFalseNode implements ConstExprNode
{

	use NodeAttributes;

	public function __toString(): string
	{
		return 'false';
	}

}
