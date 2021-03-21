<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Ast\PhpDoc;

use PHPStan\PhpDocParser\Ast\Node;

class PhpDocNode implements Node
{

	/** @var PhpDocChildNode[] */
	public $children;

	/**
	 * @param PhpDocChildNode[] $children
	 */
	public function __construct(array $children)
	{
		$this->children = $children;
	}


	/**
	 * @return PhpDocTagNode[]
	 */
	public function getTags(): array
	{
		return array_filter($this->children, static function (PhpDocChildNode $child): bool {
			return $child instanceof PhpDocTagNode;
		});
	}


	/**
	 * @param  string $tagName
	 * @return PhpDocTagNode[]
	 */
	public function getTagsByName(string $tagName): array
	{
		return array_filter($this->getTags(), static function (PhpDocTagNode $tag) use ($tagName): bool {
			return $tag->name === $tagName;
		});
	}


	/**
	 * @return VarTagValueNode[]
	 */
	public function getVarTagValues(string $tagName = '@var'): array
	{
		return array_column(
			array_filter($this->getTagsByName($tagName), static function (PhpDocTagNode $tag): bool {
				return $tag->value instanceof VarTagValueNode;
			}),
			'value'
		);
	}


	/**
	 * @return ParamTagValueNode[]
	 */
	public function getParamTagValues(string $tagName = '@param'): array
	{
		return array_column(
			array_filter($this->getTagsByName($tagName), static function (PhpDocTagNode $tag): bool {
				return $tag->value instanceof ParamTagValueNode;
			}),
			'value'
		);
	}


	/**
	 * @return TemplateTagValueNode[]
	 */
	public function getTemplateTagValues(string $tagName = '@template'): array
	{
		return array_column(
			array_filter($this->getTagsByName($tagName), static function (PhpDocTagNode $tag): bool {
				return $tag->value instanceof TemplateTagValueNode;
			}),
			'value'
		);
	}


	/**
	 * @return ExtendsTagValueNode[]
	 */
	public function getExtendsTagValues(string $tagName = '@extends'): array
	{
		return array_column(
			array_filter($this->getTagsByName($tagName), static function (PhpDocTagNode $tag): bool {
				return $tag->value instanceof ExtendsTagValueNode;
			}),
			'value'
		);
	}


	/**
	 * @return ImplementsTagValueNode[]
	 */
	public function getImplementsTagValues(string $tagName = '@implements'): array
	{
		return array_column(
			array_filter($this->getTagsByName($tagName), static function (PhpDocTagNode $tag): bool {
				return $tag->value instanceof ImplementsTagValueNode;
			}),
			'value'
		);
	}


	/**
	 * @return UsesTagValueNode[]
	 */
	public function getUsesTagValues(string $tagName = '@use'): array
	{
		return array_column(
			array_filter($this->getTagsByName($tagName), static function (PhpDocTagNode $tag): bool {
				return $tag->value instanceof UsesTagValueNode;
			}),
			'value'
		);
	}


	/**
	 * @return ReturnTagValueNode[]
	 */
	public function getReturnTagValues(string $tagName = '@return'): array
	{
		return array_column(
			array_filter($this->getTagsByName($tagName), static function (PhpDocTagNode $tag): bool {
				return $tag->value instanceof ReturnTagValueNode;
			}),
			'value'
		);
	}


	/**
	 * @return ThrowsTagValueNode[]
	 */
	public function getThrowsTagValues(string $tagName = '@throws'): array
	{
		return array_column(
			array_filter($this->getTagsByName($tagName), static function (PhpDocTagNode $tag): bool {
				return $tag->value instanceof ThrowsTagValueNode;
			}),
			'value'
		);
	}


	/**
	 * @return MixinTagValueNode[]
	 */
	public function getMixinTagValues(string $tagName = '@mixin'): array
	{
		return array_column(
			array_filter($this->getTagsByName($tagName), static function (PhpDocTagNode $tag): bool {
				return $tag->value instanceof MixinTagValueNode;
			}),
			'value'
		);
	}


	/**
	 * @return \PHPStan\PhpDocParser\Ast\PhpDoc\DeprecatedTagValueNode[]
	 */
	public function getDeprecatedTagValues(): array
	{
		return array_column(
			array_filter($this->getTagsByName('@deprecated'), static function (PhpDocTagNode $tag): bool {
				return $tag->value instanceof DeprecatedTagValueNode;
			}),
			'value'
		);
	}


	/**
	 * @return PropertyTagValueNode[]
	 */
	public function getPropertyTagValues(string $tagName = '@property'): array
	{
		return array_column(
			array_filter($this->getTagsByName($tagName), static function (PhpDocTagNode $tag): bool {
				return $tag->value instanceof PropertyTagValueNode;
			}),
			'value'
		);
	}


	/**
	 * @return PropertyTagValueNode[]
	 */
	public function getPropertyReadTagValues(string $tagName = '@property-read'): array
	{
		return array_column(
			array_filter($this->getTagsByName($tagName), static function (PhpDocTagNode $tag): bool {
				return $tag->value instanceof PropertyTagValueNode;
			}),
			'value'
		);
	}


	/**
	 * @return PropertyTagValueNode[]
	 */
	public function getPropertyWriteTagValues(string $tagName = '@property-write'): array
	{
		return array_column(
			array_filter($this->getTagsByName($tagName), static function (PhpDocTagNode $tag): bool {
				return $tag->value instanceof PropertyTagValueNode;
			}),
			'value'
		);
	}


	/**
	 * @return MethodTagValueNode[]
	 */
	public function getMethodTagValues(string $tagName = '@method'): array
	{
		return array_column(
			array_filter($this->getTagsByName($tagName), static function (PhpDocTagNode $tag): bool {
				return $tag->value instanceof MethodTagValueNode;
			}),
			'value'
		);
	}


	public function __toString(): string
	{
		return "/**\n * " . implode("\n * ", $this->children) . '*/';
	}

}
