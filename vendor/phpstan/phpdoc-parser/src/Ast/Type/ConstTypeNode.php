<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Ast\Type;

use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprNode;

class ConstTypeNode implements TypeNode
{

	/** @var ConstExprNode */
	public $constExpr;

	public function __construct(ConstExprNode $constExpr)
	{
		$this->constExpr = $constExpr;
	}

	public function __toString(): string
	{
		return $this->constExpr->__toString();
	}

}
