<?php

namespace Doctrine\Common\Annotations;

use Exception;

use function get_class;
use function gettype;
use function implode;
use function is_object;
use function sprintf;

/**
 * Description of AnnotationException
 */
class AnnotationException extends Exception
{
    /**
     * Creates a new AnnotationException describing a Syntax error.
     *
     * @param string $message Exception message
     *
     * @return AnnotationException
     */
    public static function syntaxError($message)
    {
        return new self('[Syntax Error] ' . $message);
    }

    /**
     * Creates a new AnnotationException describing a Semantical error.
     *
     * @param string $message Exception message
     *
     * @return AnnotationException
     */
    public static function semanticalError($message)
    {
        return new self('[Semantical Error] ' . $message);
    }

    /**
     * Creates a new AnnotationException describing an error which occurred during
     * the creation of the annotation.
     *
     * @param string $message
     *
     * @return AnnotationException
     */
    public static function creationError($message)
    {
        return new self('[Creation Error] ' . $message);
    }

    /**
     * Creates a new AnnotationException describing a type error.
     *
     * @param string $message
     *
     * @return AnnotationException
     */
    public static function typeError($message)
    {
        return new self('[Type Error] ' . $message);
    }

    /**
     * Creates a new AnnotationException describing a constant semantical error.
     *
     * @param string $identifier
     * @param string $context
     *
     * @return AnnotationException
     */
    public static function semanticalErrorConstants($identifier, $context = null)
    {
        return self::semanticalError(sprintf(
            "Couldn't find constant %s%s.",
            $identifier,
            $context ? ', ' . $context : ''
        ));
    }

    /**
     * Creates a new AnnotationException describing an type error of an attribute.
     *
     * @param string $attributeName
     * @param string $annotationName
     * @param string $context
     * @param string $expected
     * @param mixed  $actual
     *
     * @return AnnotationException
     */
    public static function attributeTypeError($attributeName, $annotationName, $context, $expected, $actual)
    {
        return self::typeError(sprintf(
            'Attribute "%s" of @%s declared on %s expects %s, but got %s.',
            $attributeName,
            $annotationName,
            $context,
            $expected,
            is_object($actual) ? 'an instance of ' . get_class($actual) : gettype($actual)
        ));
    }

    /**
     * Creates a new AnnotationException describing an required error of an attribute.
     *
     * @param string $attributeName
     * @param string $annotationName
     * @param string $context
     * @param string $expected
     *
     * @return AnnotationException
     */
    public static function requiredError($attributeName, $annotationName, $context, $expected)
    {
        return self::typeError(sprintf(
            'Attribute "%s" of @%s declared on %s expects %s. This value should not be null.',
            $attributeName,
            $annotationName,
            $context,
            $expected
        ));
    }

    /**
     * Creates a new AnnotationException describing a invalid enummerator.
     *
     * @param string $attributeName
     * @param string $annotationName
     * @param string $context
     * @param mixed  $given
     *
     * @return AnnotationException
     *
     * @phpstan-param list<string>        $available
     */
    public static function enumeratorError($attributeName, $annotationName, $context, $available, $given)
    {
        return new self(sprintf(
            '[Enum Error] Attribute "%s" of @%s declared on %s accepts only [%s], but got %s.',
            $attributeName,
            $annotationName,
            $context,
            implode(', ', $available),
            is_object($given) ? get_class($given) : $given
        ));
    }

    /**
     * @return AnnotationException
     */
    public static function optimizerPlusSaveComments()
    {
        return new self(
            'You have to enable opcache.save_comments=1 or zend_optimizerplus.save_comments=1.'
        );
    }

    /**
     * @return AnnotationException
     */
    public static function optimizerPlusLoadComments()
    {
        return new self(
            'You have to enable opcache.load_comments=1 or zend_optimizerplus.load_comments=1.'
        );
    }
}
