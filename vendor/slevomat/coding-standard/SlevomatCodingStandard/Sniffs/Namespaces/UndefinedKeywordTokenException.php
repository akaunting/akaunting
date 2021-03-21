<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Namespaces;

use Exception;
use Throwable;
use function sprintf;

class UndefinedKeywordTokenException extends Exception
{

	/** @var string */
	private $keyword;

	public function __construct(string $keyword, ?Throwable $previous = null)
	{
		parent::__construct(sprintf(
			'Value for keyword token not found, constant "%s" is not defined',
			$keyword
		), 0, $previous);

		$this->keyword = $keyword;
	}

	public function getKeyword(): string
	{
		return $this->keyword;
	}

}
