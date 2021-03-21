<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Ast\ConstExpr;

class ConstExprArrayItemNode implements ConstExprNode
{

	/** @var ConstExprNode|null */
	public $key;

	/** @var ConstExprNode */
	public $value;

	public function __construct(?ConstExprNode $key, ConstExprNode $value)
	{
		$this->key = $key;
		$this->value = $value;
	}


	public function __toString(): string
	{
		if ($this->key !== null) {
			return "{$this->key} => {$this->value}";

		}

		return "{$this->value}";
	}

}
