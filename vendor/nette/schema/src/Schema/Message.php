<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Nette\Schema;

use Nette;


final class Message
{
	use Nette\SmartObject;

	/** variables: {value: mixed, expected: string} */
	public const TypeMismatch = 'schema.typeMismatch';

	/** variables: {value: mixed, expected: string} */
	public const ValueOutOfRange = 'schema.valueOutOfRange';

	/** variables: {value: mixed, length: int, expected: string} */
	public const LengthOutOfRange = 'schema.lengthOutOfRange';

	/** variables: {value: string, pattern: string} */
	public const PatternMismatch = 'schema.patternMismatch';

	/** variables: {value: mixed, assertion: string} */
	public const FailedAssertion = 'schema.failedAssertion';

	/** no variables */
	public const MissingItem = 'schema.missingItem';

	/** variables: {hint: string} */
	public const UnexpectedItem = 'schema.unexpectedItem';

	/** no variables */
	public const Deprecated = 'schema.deprecated';

	/** Deprecated */
	public const TYPE_MISMATCH = self::TypeMismatch;
	public const VALUE_OUT_OF_RANGE = self::ValueOutOfRange;
	public const LENGTH_OUT_OF_RANGE = self::LengthOutOfRange;
	public const PATTERN_MISMATCH = self::PatternMismatch;
	public const FAILED_ASSERTION = self::FailedAssertion;
	public const MISSING_ITEM = self::MissingItem;
	public const UNEXPECTED_ITEM = self::UnexpectedItem;
	public const DEPRECATED = self::Deprecated;

	/** @var string */
	public $message;

	/** @var string */
	public $code;

	/** @var string[] */
	public $path;

	/** @var string[] */
	public $variables;


	public function __construct(string $message, string $code, array $path, array $variables = [])
	{
		$this->message = $message;
		$this->code = $code;
		$this->path = $path;
		$this->variables = $variables;
	}


	public function toString(): string
	{
		$vars = $this->variables;
		$vars['label'] = empty($vars['isKey']) ? 'item' : 'key of item';
		$vars['path'] = $this->path
			? "'" . implode("\u{a0}›\u{a0}", $this->path) . "'"
			: null;
		$vars['value'] = Helpers::formatValue($vars['value'] ?? null);

		return preg_replace_callback('~( ?)%(\w+)%~', function ($m) use ($vars) {
			[, $space, $key] = $m;
			return $vars[$key] === null ? '' : $space . $vars[$key];
		}, $this->message);
	}
}
