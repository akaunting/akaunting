<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Ast\Type;

class ArrayShapeNode implements TypeNode
{

	/** @var ArrayShapeItemNode[] */
	public $items;

	public function __construct(array $items)
	{
		$this->items = $items;
	}


	public function __toString(): string
	{
		return 'array{' . implode(', ', $this->items) . '}';
	}

}
