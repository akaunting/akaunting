<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Nette\Schema;

use Nette;


final class Context
{
	use Nette\SmartObject;

	/** @var bool */
	public $skipDefaults = false;

	/** @var string[] */
	public $path = [];

	/** @var bool */
	public $isKey = false;

	/** @var Message[] */
	public $errors = [];

	/** @var Message[] */
	public $warnings = [];

	/** @var array[] */
	public $dynamics = [];


	public function addError(string $message, string $code, array $variables = []): Message
	{
		$variables['isKey'] = $this->isKey;
		return $this->errors[] = new Message($message, $code, $this->path, $variables);
	}


	public function addWarning(string $message, string $code, array $variables = []): Message
	{
		return $this->warnings[] = new Message($message, $code, $this->path, $variables);
	}
}
