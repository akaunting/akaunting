<?php

namespace Enlightn\Enlightn\CodeCorrection;

use Illuminate\Support\Str;
use PhpParser\Node;

trait ConstructsConfigurationAST
{
    /**
     * Only supports arrays or strings.
     *
     * @param $value
     * @return \PhpParser\Node\Expr\Array_|\PhpParser\Node\Expr\ClassConstFetch|\PhpParser\Node\Scalar\String_
     */
    public function getConfiguration($value)
    {
        if (is_string($value)) {
            if (Str::contains($value, '\\') && class_exists($value)) {
                return new Node\Expr\ClassConstFetch(new Node\Name($value), new Node\Identifier('class'));
            }

            return new Node\Scalar\String_($value);
        } elseif (is_array($value)) {
            $items = [];
            foreach ($value as $arrayKey => $arrayValue) {
                if (is_string($arrayKey)) {
                    // Assuming associative array.
                    $items[] = new Node\Expr\ArrayItem($this->getConfiguration($arrayValue), $this->getConfiguration($arrayKey));
                } else {
                    // Assuming sequential array.
                    $items[] = new Node\Expr\ArrayItem($this->getConfiguration($arrayValue), null);
                }
            }

            return new Node\Expr\Array_($items);
        }
    }
}
