<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Helpers;

/**
 * @internal
 */
class Comment
{

	/** @var int */
	private $pointer;

	/** @var string */
	private $content;

	public function __construct(int $pointer, string $content)
	{
		$this->pointer = $pointer;
		$this->content = $content;
	}

	public function getPointer(): int
	{
		return $this->pointer;
	}

	public function getContent(): string
	{
		return $this->content;
	}

}
