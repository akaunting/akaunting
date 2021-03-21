<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Classes;

use Exception;
use function implode;
use function sprintf;

class MissingClassGroupsException extends Exception
{

	/** @param string[] $groups */
	public function __construct(array $groups)
	{
		parent::__construct(
			sprintf(
				'You need configure all class groups. These groups are missing from your configuration: %s.',
				implode(', ', $groups)
			)
		);
	}

}
