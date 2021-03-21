<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Ast\Type;

class ArrayTypeNode implements TypeNode
{

	/** @var TypeNode */
	public $type;

	public function __construct(TypeNode $type)
	{
		$this->type = $type;
	}


	public function __toString(): string
	{
		return $this->type . '[]';
	}

}
