<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\ControlStructures;

use Exception;
use function sprintf;

class UnsupportedTokenException extends Exception
{

	public function __construct(string $tokenCode)
	{
		parent::__construct(sprintf('Unsupported token "%s".', $tokenCode));
	}

}
