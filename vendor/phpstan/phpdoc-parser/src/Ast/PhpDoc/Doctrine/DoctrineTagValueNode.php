<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Ast\PhpDoc\Doctrine;

use PHPStan\PhpDocParser\Ast\NodeAttributes;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagValueNode;
use function trim;

class DoctrineTagValueNode implements PhpDocTagValueNode
{

	use NodeAttributes;

	/** @var DoctrineAnnotation */
	public $annotation;

	/** @var string (may be empty) */
	public $description;


	public function __construct(
		DoctrineAnnotation $annotation,
		string $description
	)
	{
		$this->annotation = $annotation;
		$this->description = $description;
	}


	public function __toString(): string
	{
		return trim("{$this->annotation} {$this->description}");
	}

}
