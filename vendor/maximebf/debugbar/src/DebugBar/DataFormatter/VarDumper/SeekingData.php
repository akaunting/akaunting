<?php

namespace DebugBar\DataFormatter\VarDumper;

use Symfony\Component\VarDumper\Cloner\Cursor;
use Symfony\Component\VarDumper\Cloner\Data;
use Symfony\Component\VarDumper\Cloner\DumperInterface;
use Symfony\Component\VarDumper\Cloner\Stub;

/**
 * This class backports the seek() function from Symfony 3.2 to older versions - up to v2.6.  The
 * class should not be used with newer Symfony versions that provide the seek function, as it relies
 * on a lot of undocumented functionality.
 */
class SeekingData extends Data
{
    // Because the class copies/pastes the seek() implementation from Symfony 3.2, we reproduce its
    // copyright here; this class is subject to the following additional copyright:

    /*
     * Copyright (c) 2014-2017 Fabien Potencier
     *
     * Permission is hereby granted, free of charge, to any person obtaining a copy
     * of this software and associated documentation files (the "Software"), to deal
     * in the Software without restriction, including without limitation the rights
     * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
     * copies of the Software, and to permit persons to whom the Software is furnished
     * to do so, subject to the following conditions:
     *
     * The above copyright notice and this permission notice shall be included in all
     * copies or substantial portions of the Software.
     *
     * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
     * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
     * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
     * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
     * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
     * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
     * THE SOFTWARE.
     */
    private $position = 0;
    private $key = 0;

    /**
     * Seeks to a specific key in nested data structures.
     *
     * @param string|int $key The key to seek to
     *
     * @return self|null A clone of $this of null if the key is not set
     */
    public function seek($key)
    {
        $thisData = $this->getRawData();
        $item = $thisData[$this->position][$this->key];

        if (!$item instanceof Stub || !$item->position) {
            return;
        }
        $keys = array($key);

        switch ($item->type) {
            case Stub::TYPE_OBJECT:
                $keys[] = "\0+\0".$key;
                $keys[] = "\0*\0".$key;
                $keys[] = "\0~\0".$key;
                $keys[] = "\0$item->class\0$key";
            case Stub::TYPE_ARRAY:
            case Stub::TYPE_RESOURCE:
                break;
            default:
                return;
        }

        $data = null;
        $children = $thisData[$item->position];

        foreach ($keys as $key) {
            if (isset($children[$key]) || array_key_exists($key, $children)) {
                $data = clone $this;
                $data->key = $key;
                $data->position = $item->position;
                break;
            }
        }

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function dump(DumperInterface $dumper)
    {
        // Override the base class dump to use the position and key
        $refs = array(0);
        $class = new \ReflectionClass($this);
        $dumpItem = $class->getMethod('dumpItem');
        $dumpItem->setAccessible(true);
        $data = $this->getRawData();
        $args = array($dumper, new Cursor(), &$refs, $data[$this->position][$this->key]);
        $dumpItem->invokeArgs($this, $args);
    }
}
