Custom Annotation Classes
=========================

If you want to define your own annotations, you just have to group them
in a namespace and register this namespace in the ``AnnotationRegistry``.
Annotation classes have to contain a class-level docblock with the text
``@Annotation``:

.. code-block:: php

    namespace MyCompany\Annotations;

    /** @Annotation */
    class Bar
    {
        // some code
    }

Inject annotation values
------------------------

The annotation parser checks if the annotation constructor has arguments,
if so then it will pass the value array, otherwise it will try to inject
values into public properties directly:


.. code-block:: php

    namespace MyCompany\Annotations;

    /**
     * @Annotation
     *
     * Some Annotation using a constructor
     */
    class Bar
    {
        private $foo;

        public function __construct(array $values)
        {
            $this->foo = $values['foo'];
        }
    }

    /**
     * @Annotation
     *
     * Some Annotation without a constructor
     */
    class Foo
    {
        public $bar;
    }

Optional: Constructors with Named Parameters
--------------------------------------------

Starting with Annotations v1.11 a new annotation instantiation strategy
is available that aims at compatibility of Annotation classes with the PHP 8
attribute feature. You need to declare a constructor with regular parameter 
names that match the named arguments in the annotation syntax.

To enable this feature, you can tag your annotation class with 
``@NamedArgumentConstructor`` (available from v1.12) or implement the
``Doctrine\Common\Annotations\NamedArgumentConstructorAnnotation`` interface
(available from v1.11 and deprecated as of v1.12).
When using the ``@NamedArgumentConstructor`` tag, the first argument of the
constructor is considered as the default one.


Usage with the ``@NamedArgumentContrustor`` tag

.. code-block:: php

    namespace MyCompany\Annotations;

    /** 
     * @Annotation 
     * @NamedArgumentConstructor
     */
    class Bar implements NamedArgumentConstructorAnnotation
    {
        private $foo;

        public function __construct(string $foo)
        {
            $this->foo = $foo;
        }
    }

    /** Usable with @Bar(foo="baz") */
    /** Usable with @Bar("baz") */

In combination with PHP 8's constructor property promotion feature
you can simplify this to:

.. code-block:: php

    namespace MyCompany\Annotations;

    /** 
     * @Annotation 
     * @NamedArgumentConstructor
     */
    class Bar implements NamedArgumentConstructorAnnotation
    {
        public function __construct(private string $foo) {}
    }


Usage with the 
``Doctrine\Common\Annotations\NamedArgumentConstructorAnnotation``
interface (v1.11, deprecated as of v1.12):
.. code-block:: php

    namespace MyCompany\Annotations;

    use Doctrine\Common\Annotations\NamedArgumentConstructorAnnotation;

    /** @Annotation */
    class Bar implements NamedArgumentConstructorAnnotation
    {
        private $foo;

        public function __construct(private string $foo) {}
    }

    /** Usable with @Bar(foo="baz") */

Annotation Target
-----------------

``@Target`` indicates the kinds of class elements to which an annotation
type is applicable. Then you could define one or more targets:

-  ``CLASS`` Allowed in class docblocks
-  ``PROPERTY`` Allowed in property docblocks
-  ``METHOD`` Allowed in the method docblocks
-  ``FUNCTION`` Allowed in function dockblocks
-  ``ALL`` Allowed in class, property, method and function docblocks
-  ``ANNOTATION`` Allowed inside other annotations

If the annotations is not allowed in the current context, an
``AnnotationException`` is thrown.

.. code-block:: php

    namespace MyCompany\Annotations;

    /**
     * @Annotation
     * @Target({"METHOD","PROPERTY"})
     */
    class Bar
    {
        // some code
    }

    /**
     * @Annotation
     * @Target("CLASS")
     */
    class Foo
    {
        // some code
    }

Attribute types
---------------

The annotation parser checks the given parameters using the phpdoc
annotation ``@var``, The data type could be validated using the ``@var``
annotation on the annotation properties or using the ``@Attributes`` and
``@Attribute`` annotations.

If the data type does not match you get an ``AnnotationException``

.. code-block:: php

    namespace MyCompany\Annotations;

    /**
     * @Annotation
     * @Target({"METHOD","PROPERTY"})
     */
    class Bar
    {
        /** @var mixed */
        public $mixed;

        /** @var boolean */
        public $boolean;

        /** @var bool */
        public $bool;

        /** @var float */
        public $float;

        /** @var string */
        public $string;

        /** @var integer */
        public $integer;

        /** @var array */
        public $array;

        /** @var SomeAnnotationClass */
        public $annotation;

        /** @var array<integer> */
        public $arrayOfIntegers;

        /** @var array<SomeAnnotationClass> */
        public $arrayOfAnnotations;
    }

    /**
     * @Annotation
     * @Target({"METHOD","PROPERTY"})
     * @Attributes({
     *   @Attribute("stringProperty", type = "string"),
     *   @Attribute("annotProperty",  type = "SomeAnnotationClass"),
     * })
     */
    class Foo
    {
        public function __construct(array $values)
        {
            $this->stringProperty = $values['stringProperty'];
            $this->annotProperty = $values['annotProperty'];
        }

        // some code
    }

Annotation Required
-------------------

``@Required`` indicates that the field must be specified when the
annotation is used. If it is not used you get an ``AnnotationException``
stating that this value can not be null.

Declaring a required field:

.. code-block:: php

    /**
     * @Annotation
     * @Target("ALL")
     */
    class Foo
    {
        /** @Required */
        public $requiredField;
    }

Usage:

.. code-block:: php

    /** @Foo(requiredField="value") */
    public $direction;                  // Valid

     /** @Foo */
    public $direction;                  // Required field missing, throws an AnnotationException


Enumerated values
-----------------

- An annotation property marked with ``@Enum`` is a field that accepts a
  fixed set of scalar values.
- You should use ``@Enum`` fields any time you need to represent fixed
  values.
- The annotation parser checks the given value and throws an
  ``AnnotationException`` if the value does not match.


Declaring an enumerated property:

.. code-block:: php

    /**
     * @Annotation
     * @Target("ALL")
     */
    class Direction
    {
        /**
         * @Enum({"NORTH", "SOUTH", "EAST", "WEST"})
         */
        public $value;
    }

Annotation usage:

.. code-block:: php

    /** @Direction("NORTH") */
    public $direction;                  // Valid value

     /** @Direction("NORTHEAST") */
    public $direction;                  // Invalid value, throws an AnnotationException


Constants
---------

The use of constants and class constants is available on the annotations
parser.

The following usages are allowed:

.. code-block:: php

    namespace MyCompany\Entity;

    use MyCompany\Annotations\Foo;
    use MyCompany\Annotations\Bar;
    use MyCompany\Entity\SomeClass;

    /**
     * @Foo(PHP_EOL)
     * @Bar(Bar::FOO)
     * @Foo({SomeClass::FOO, SomeClass::BAR})
     * @Bar({SomeClass::FOO_KEY = SomeClass::BAR_VALUE})
     */
    class User
    {
    }


Be careful with constants and the cache !

.. note::

    The cached reader will not re-evaluate each time an annotation is
    loaded from cache. When a constant is changed the cache must be
    cleaned.


Usage
-----

Using the library API is simple. Using the annotations described in the
previous section, you can now annotate other classes with your
annotations:

.. code-block:: php

    namespace MyCompany\Entity;

    use MyCompany\Annotations\Foo;
    use MyCompany\Annotations\Bar;

    /**
     * @Foo(bar="foo")
     * @Bar(foo="bar")
     */
    class User
    {
    }

Now we can write a script to get the annotations above:

.. code-block:: php

    $reflClass = new ReflectionClass('MyCompany\Entity\User');
    $classAnnotations = $reader->getClassAnnotations($reflClass);

    foreach ($classAnnotations AS $annot) {
        if ($annot instanceof \MyCompany\Annotations\Foo) {
            echo $annot->bar; // prints "foo";
        } else if ($annot instanceof \MyCompany\Annotations\Bar) {
            echo $annot->foo; // prints "bar";
        }
    }

You have a complete API for retrieving annotation class instances from a
class, property or method docblock:


Reader API
~~~~~~~~~~

Access all annotations of a class
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

.. code-block:: php

    public function getClassAnnotations(\ReflectionClass $class);

Access one annotation of a class
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

.. code-block:: php

    public function getClassAnnotation(\ReflectionClass $class, $annotationName);

Access all annotations of a method
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

.. code-block:: php

    public function getMethodAnnotations(\ReflectionMethod $method);

Access one annotation of a method
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

.. code-block:: php

    public function getMethodAnnotation(\ReflectionMethod $method, $annotationName);

Access all annotations of a property
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

.. code-block:: php

    public function getPropertyAnnotations(\ReflectionProperty $property);

Access one annotation of a property
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

.. code-block:: php

    public function getPropertyAnnotation(\ReflectionProperty $property, $annotationName);

Access all annotations of a function
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

.. code-block:: php

    public function getFunctionAnnotations(\ReflectionFunction $property);

Access one annotation of a function
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

.. code-block:: php

    public function getFunctionAnnotation(\ReflectionFunction $property, $annotationName);
