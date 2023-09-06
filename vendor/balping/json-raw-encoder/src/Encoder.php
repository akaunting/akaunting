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

class Encoder {
	/**
	 * Encode array containing Raw objects to JSON 
	 *
	 * @see json_encode
	 * 
	 * @param mixed $value
	 * @param int $options
	 * @param int $depth [optional]
	 * @return string|false
	 */
	static function encode($value, ...$args){
		$rawObjects = [];

		// find raw object items in the input array
		array_walk_recursive($value, function($item) use (&$rawObjects){
			if(is_object($item) && is_a($item, Raw::class)){
				$rawObjects[] = &$item;
			}
		});

		$encoded = json_encode($value, ...$args);

		return Replacer::replace($encoded, $rawObjects);
	}
}
