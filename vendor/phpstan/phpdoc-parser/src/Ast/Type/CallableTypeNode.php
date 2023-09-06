<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Ast\Type;

use PHPStan\PhpDocParser\Ast\NodeAttributes;
use function implode;

class CallableTypeNode implements TypeNode
{

	use NodeAttributes;

	/** @var IdentifierTypeNode */
	public $identifier;

	/** @var CallableTypeParameterNode[] */
	public $parameters;

	/** @var TypeNode */
	public $returnType;

	/**
	 * @param CallableTypeParameterNode[] $parameters
	 */
	public function __construct(IdentifierTypeNode $identifier, array $parameters, TypeNode $returnType)
	{
		$this->identifier = $identifier;
		$this->parameters = $parameters;
		$this->returnType = $returnType;
	}


	public function __toString(): string
	{
		$returnType = $this->returnType;
		if ($returnType instanceof self) {
			$returnType = "({$returnType})";
		}
		$parameters = implode(', ', $this->parameters);
		return "{$this->identifier}({$parameters}): {$returnType}";
	}

}
