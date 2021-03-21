<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Ast\ConstExpr;

class ConstExprArrayNode implements ConstExprNode
{

	/** @var ConstExprArrayItemNode[] */
	public $items;

	/**
	 * @param ConstExprArrayItemNode[] $items
	 */
	public function __construct(array $items)
	{
		$this->items = $items;
	}


	public function __toString(): string
	{
		return '[' . implode(', ', $this->items) . ']';
	}

}
