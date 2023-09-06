<?php
/**
 * phpDocumentor
 *
 * PHP Version 5.3
 *
 * @author    Mike van Riel <mike.vanriel@naenius.com>
 * @copyright 2010-2011 Mike van Riel / Naenius (http://www.naenius.com)
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 * @link      http://phpdoc.org
 */

namespace Barryvdh\Reflection\DocBlock\Tag;

use Barryvdh\Reflection\DocBlock\Tag;
use Barryvdh\Reflection\DocBlock\Type\Collection;

/**
 * Reflection class for a @return tag in a Docblock.
 *
 * @author  Mike van Riel <mike.vanriel@naenius.com>
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 * @link    http://phpdoc.org
 */
class ReturnTag extends Tag
{
    /** @var string The raw type component. */
    protected $type = '';

    /** @var Collection The parsed type component. */
    protected $types = null;

    /**
     * {@inheritdoc}
     */
    public function getContent()
    {
        if (null === $this->content) {
            $this->content = "{$this->getType()} {$this->description}";
        }

        return $this->content;
    }

    /**
     * {@inheritdoc}
     */
    public function setContent($content)
    {
        parent::setContent($content);

        $parts = preg_split('/(?<!,)\s+/Su', $this->description, 2);

        // any output is considered a type
        $this->type = $parts[0];
        $this->types = null;

        $this->setDescription(isset($parts[1]) ? $parts[1] : '');

        $this->content = $content;
        return $this;
    }

    /**
     * Returns the unique types of the variable.
     *
     * @return string[]
     */
    public function getTypes()
    {
        return $this->getTypesCollection()->getArrayCopy();
    }

    /**
     * Returns the type section of the variable.
     *
     * @return string
     */
    public function getType()
    {
        return (string) $this->getTypesCollection();
    }

    /**
     * Set the type section of the variable
     *
     * @param string $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;
        $this->types = null;
        $this->content = null;
        return $this;
    }

    /**
     * Add a type to the type section of the variable
     *
     * @param string $type
     * @return $this
     */
    public function addType($type)
    {
        $this->type = $this->type . Collection::OPERATOR_OR . $type;
        $this->types = null;
        $this->content = null;
        return $this;
    }


    /**
     * Returns the type collection.
     *
     * @return void
     */
    protected function getTypesCollection()
    {
        if (null === $this->types) {
            $this->types = new Collection(
                array($this->type),
                $this->docblock ? $this->docblock->getContext() : null
            );
        }
        return $this->types;
    }
}
