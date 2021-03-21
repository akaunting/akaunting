<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Namespaces;

use SlevomatCodingStandard\Helpers\ReferencedName;

class FullyQualifiedGlobalConstantsSniff
	extends AbstractFullyQualifiedGlobalReference
{

	protected function getNotFullyQualifiedMessage(): string
	{
		return 'Constant %s should be referenced via a fully qualified name.';
	}

	protected function isCaseSensitive(): bool
	{
		return true;
	}

	protected function isValidType(ReferencedName $name): bool
	{
		return $name->isConstant();
	}

}
