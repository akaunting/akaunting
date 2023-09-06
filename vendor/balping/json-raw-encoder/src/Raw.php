<?php

/*

Json Raw Encoder
Encode arrays to json with raw JS objects (eg. callbacks) in them
Copyright (C) 2018  Balázs Dura-Kovács

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.

*/

namespace Balping\JsonRaw;

class Raw implements \JsonSerializable {
	/**
	 * Unique identifier. Gets replaced with raw value
	 * after using built-in json_encode
	 * @var string
	 */
	protected $id;

	/**
	 * Raw value. This is passed to json without any modification
	 * (i.e. without escaping, etc.)
	 * @var string
	 */
	protected $value;

	public function __construct(string $value){
		$this->value = $value;
		$this->id = bin2hex(random_bytes(20));
	}

	public function getId(){
		return $this->id;
	}

	public function getValue(){
		return $this->value;
	}

	public function jsonSerialize() : string {
		return $this->getId();
	}
}
