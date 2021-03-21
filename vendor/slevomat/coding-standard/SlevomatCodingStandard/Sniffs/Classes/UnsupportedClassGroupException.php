<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Classes;

use Exception;
use function sprintf;

class UnsupportedClassGroupException extends Exception
{

	public function __construct(string $group)
	{
		parent::__construct(sprintf('Unsupported class group "%s".', $group));
	}

}
