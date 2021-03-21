<?php

declare(strict_types=1);

namespace Doctrine\Common\Annotations;

/**
 *  A list of annotations that are implicitly ignored during the parsing process.
 *
 *  All names are case sensitive.
 */
final class ImplicitlyIgnoredAnnotationNames
{
    private const Reserved = [
        'Annotation' => true,
        'Attribute'  => true,
        'Attributes' => true,
        /* Can we enable this? 'Enum' => true, */
        'Required'   => true,
        'Target'     => true,
    ];

    private const WidelyUsedNonStandard = [
        'fix'      => true,
        'fixme'    => true,
        'override' => true,
    ];

    private const PhpDocumentor1 = [
        'abstract'   => true,
        'access'     => true,
        'code'       => true,
        'deprec'     => true,
        'endcode'    => true,
        'exception'  => true,
        'final'      => true,
        'ingroup'    => true,
        'inheritdoc' => true,
        'inheritDoc' => true,
        'magic'      => true,
        'name'       => true,
        'private'    => true,
        'static'     => true,
        'staticvar'  => true,
        'staticVar'  => true,
        'toc'        => true,
        'tutorial'   => true,
        'throw'      => true,
    ];

    private const PhpDocumentor2 = [
        'api'            => true,
        'author'         => true,
        'category'       => true,
        'copyright'      => true,
        'deprecated'     => true,
        'example'        => true,
        'filesource'     => true,
        'global'         => true,
        'ignore'         => true,
        /* Can we enable this? 'index' => true, */
        'internal'       => true,
        'license'        => true,
        'link'           => true,
        'method'         => true,
        'package'        => true,
        'param'          => true,
        'property'       => true,
        'property-read'  => true,
        'property-write' => true,
        'return'         => true,
        'see'            => true,
        'since'          => true,
        'source'         => true,
        'subpackage'     => true,
        'throws'         => true,
        'todo'           => true,
        'TODO'           => true,
        'usedby'         => true,
        'uses'           => true,
        'var'            => true,
        'version'        => true,
    ];

    private const PHPUnit = [
        'author'                         => true,
        'after'                          => true,
        'afterClass'                     => true,
        'backupGlobals'                  => true,
        'backupStaticAttributes'         => true,
        'before'                         => true,
        'beforeClass'                    => true,
        'codeCoverageIgnore'             => true,
        'codeCoverageIgnoreStart'        => true,
        'codeCoverageIgnoreEnd'          => true,
        'covers'                         => true,
        'coversDefaultClass'             => true,
        'coversNothing'                  => true,
        'dataProvider'                   => true,
        'depends'                        => true,
        'doesNotPerformAssertions'       => true,
        'expectedException'              => true,
        'expectedExceptionCode'          => true,
        'expectedExceptionMessage'       => true,
        'expectedExceptionMessageRegExp' => true,
        'group'                          => true,
        'large'                          => true,
        'medium'                         => true,
        'preserveGlobalState'            => true,
        'requires'                       => true,
        'runTestsInSeparateProcesses'    => true,
        'runInSeparateProcess'           => true,
        'small'                          => true,
        'test'                           => true,
        'testdox'                        => true,
        'testWith'                       => true,
        'ticket'                         => true,
        'uses'                           => true,
    ];

    private const PhpCheckStyle = ['SuppressWarnings' => true];

    private const PhpStorm = ['noinspection' => true];

    private const PEAR = ['package_version' => true];

    private const PlainUML = [
        'startuml' => true,
        'enduml'   => true,
    ];

    private const Symfony = ['experimental' => true];

    private const PhpCodeSniffer = [
        'codingStandardsIgnoreStart' => true,
        'codingStandardsIgnoreEnd'   => true,
    ];

    private const SlevomatCodingStandard = ['phpcsSuppress' => true];

    private const PhpStan = [
        'extends' => true,
        'implements' => true,
        'template' => true,
        'use' => true,
    ];

    private const Phan = ['suppress' => true];

    private const Rector = ['noRector' => true];

    public const LIST = self::Reserved
        + self::WidelyUsedNonStandard
        + self::PhpDocumentor1
        + self::PhpDocumentor2
        + self::PHPUnit
        + self::PhpCheckStyle
        + self::PhpStorm
        + self::PEAR
        + self::PlainUML
        + self::Symfony
        + self::SlevomatCodingStandard
        + self::PhpCodeSniffer
        + self::PhpStan
        + self::Phan
        + self::Rector;

    private function __construct()
    {
    }
}
