<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Helpers\Annotation;

use function sprintf;

/**
 * @internal
 */
class GenericAnnotation extends Annotation
{

	/** @var string|null */
	private $parameters;

	public function __construct(string $name, int $startPointer, int $endPointer, ?string $parameters, ?string $content)
	{
		parent::__construct($name, $startPointer, $endPointer, $content);

		$this->parameters = $parameters;
	}

	public function getParameters(): ?string
	{
		return $this->parameters;
	}

	public function isInvalid(): bool
	{
		return false;
	}

	public function export(): string
	{
		$exported = $this->name;

		if ($this->parameters !== null) {
			$exported .= sprintf('(%s)', $this->parameters);
		}

		if ($this->content !== null) {
			$exported .= sprintf(' %s', $this->content);
		}

		return $exported;
	}

}
