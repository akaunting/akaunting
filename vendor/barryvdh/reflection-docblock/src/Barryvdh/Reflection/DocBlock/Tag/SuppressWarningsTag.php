<?php
/**
 * phpDocumentor
 *
 * PHP Version 5.3
 *
 * @author    Andrew Smith <espadav8@gmail.com>
 * @copyright 2010-2011 Mike van Riel / Naenius (http://www.naenius.com)
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 * @link      http://phpdoc.org
 */

namespace Barryvdh\Reflection\DocBlock\Tag;

use Barryvdh\Reflection\DocBlock\Tag;

/**
 * Reflection class for a @SuppressWarnings tag in a Docblock.
 *
 * @author  Andrew Smith <espadav8@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 * @link    http://phpdoc.org
 */
class SuppressWarningsTag extends Tag
{
    public function __toString()
    {
        return "@{$this->getName()}{$this->getContent()}";
    }
}
