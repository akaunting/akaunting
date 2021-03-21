<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Ast\PhpDoc;

class GenericTagValueNode implements PhpDocTagValueNode
{

	/** @var string (may be empty) */
	public $value;

	public function __construct(string $value)
	{
		$this->value = $value;
	}


	public function __toString(): string
	{
		return $this->value;
	}

}
