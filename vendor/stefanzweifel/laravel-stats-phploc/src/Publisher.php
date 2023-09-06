<?php declare(strict_types=1);
/*
 * This file is part of PHPLOC.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace SebastianBergmann\PHPLOC;

use function array_intersect;
use function array_sum;
use function count;
use function max;
use function min;

class Publisher
{
    private $counts;

    public function __construct(array $counts)
    {
        $this->counts = $counts;
    }

    public function getDirectories()
    {
        return $this->getCount('directories') - 1;
    }

    public function getFiles()
    {
        return $this->getValue('files');
    }

    public function getLines()
    {
        return $this->getValue('lines');
    }

    public function getCommentLines()
    {
        return $this->getValue('comment lines');
    }

    public function getNonCommentLines()
    {
        return $this->getLines() - $this->getCommentLines();
    }

    public function getLogicalLines()
    {
        return $this->getValue('logical lines');
    }

    public function getClassLines()
    {
        return $this->getSum('class lines');
    }

    public function getAverageClassLength()
    {
        return $this->getAverage('class lines');
    }

    public function getMinimumClassLength()
    {
        return $this->getMinimum('class lines');
    }

    public function getMaximumClassLength()
    {
        return $this->getMaximum('class lines');
    }

    public function getAverageMethodLength()
    {
        return $this->getAverage('method lines');
    }

    public function getMinimumMethodLength()
    {
        return $this->getMinimum('method lines');
    }

    public function getMaximumMethodLength()
    {
        return $this->getMaximum('method lines');
    }

    public function getAverageMethodsPerClass()
    {
        return $this->getAverage('methods per class');
    }

    public function getMinimumMethodsPerClass()
    {
        return $this->getMinimum('methods per class');
    }

    public function getMaximumMethodsPerClass()
    {
        return $this->getMaximum('methods per class');
    }

    public function getFunctionLines()
    {
        return $this->getValue('function lines');
    }

    public function getAverageFunctionLength()
    {
        return $this->divide($this->getFunctionLines(), $this->getFunctions());
    }

    public function getNotInClassesOrFunctions()
    {
        return $this->getLogicalLines() - $this->getClassLines() - $this->getFunctionLines();
    }

    public function getComplexity()
    {
        return $this->getValue('complexity');
    }

    public function getMethodComplexity()
    {
        return $this->getValue('total method complexity');
    }

    public function getAverageComplexityPerLogicalLine()
    {
        return $this->divide($this->getComplexity(), $this->getLogicalLines());
    }

    public function getAverageComplexityPerClass()
    {
        return $this->getAverage('class complexity');
    }

    public function getMinimumClassComplexity()
    {
        return $this->getMinimum('class complexity');
    }

    public function getMaximumClassComplexity()
    {
        return $this->getMaximum('class complexity');
    }

    public function getAverageComplexityPerMethod()
    {
        return $this->getAverage('method complexity');
    }

    public function getMinimumMethodComplexity()
    {
        return $this->getMinimum('method complexity');
    }

    public function getMaximumMethodComplexity()
    {
        return $this->getMaximum('method complexity');
    }

    public function getGlobalAccesses()
    {
        return $this->getGlobalConstantAccesses() + $this->getGlobalVariableAccesses() + $this->getSuperGlobalVariableAccesses();
    }

    public function getGlobalConstantAccesses()
    {
        return count(array_intersect($this->getValue('possible constant accesses', []), $this->getValue('constant', [])));
    }

    public function getGlobalVariableAccesses()
    {
        return $this->getValue('global variable accesses');
    }

    public function getSuperGlobalVariableAccesses()
    {
        return $this->getValue('super global variable accesses');
    }

    public function getAttributeAccesses()
    {
        return $this->getNonStaticAttributeAccesses() + $this->getStaticAttributeAccesses();
    }

    public function getNonStaticAttributeAccesses()
    {
        return $this->getValue('non-static attribute accesses');
    }

    public function getStaticAttributeAccesses()
    {
        return $this->getValue('static attribute accesses');
    }

    public function getMethodCalls()
    {
        return $this->getNonStaticMethodCalls() + $this->getStaticMethodCalls();
    }

    public function getNonStaticMethodCalls()
    {
        return $this->getValue('non-static method calls');
    }

    public function getStaticMethodCalls()
    {
        return $this->getValue('static method calls');
    }

    public function getNamespaces()
    {
        return $this->getCount('namespaces');
    }

    public function getInterfaces()
    {
        return $this->getValue('interfaces');
    }

    public function getTraits()
    {
        return $this->getValue('traits');
    }

    public function getClasses()
    {
        return $this->getAbstractClasses() + $this->getConcreteClasses();
    }

    public function getAbstractClasses()
    {
        return $this->getValue('abstract classes');
    }

    public function getConcreteClasses()
    {
        return $this->getFinalClasses() + $this->getNonFinalClasses();
    }

    public function getFinalClasses()
    {
        return $this->getValue('final classes');
    }

    public function getNonFinalClasses()
    {
        return $this->getValue('non-final classes');
    }

    public function getMethods()
    {
        return $this->getNonStaticMethods() + $this->getStaticMethods();
    }

    public function getNonStaticMethods()
    {
        return $this->getValue('non-static methods');
    }

    public function getStaticMethods()
    {
        return $this->getValue('static methods');
    }

    public function getPublicMethods()
    {
        return $this->getValue('public methods');
    }

    public function getNonPublicMethods()
    {
        return $this->getProtectedMethods() + $this->getPrivateMethods();
    }

    public function getProtectedMethods()
    {
        return $this->getValue('protected methods');
    }

    public function getPrivateMethods()
    {
        return $this->getValue('private methods');
    }

    public function getFunctions()
    {
        return $this->getNamedFunctions() + $this->getAnonymousFunctions();
    }

    public function getNamedFunctions()
    {
        return $this->getValue('named functions');
    }

    public function getAnonymousFunctions()
    {
        return $this->getValue('anonymous functions');
    }

    public function getConstants()
    {
        return $this->getGlobalConstants() + $this->getClassConstants();
    }

    public function getGlobalConstants()
    {
        return $this->getValue('global constants');
    }

    public function getPublicClassConstants()
    {
        return $this->getValue('public class constants');
    }

    public function getNonPublicClassConstants()
    {
        return $this->getValue('non-public class constants');
    }

    public function getClassConstants()
    {
        return $this->getPublicClassConstants() + $this->getNonPublicClassConstants();
    }

    public function getTestClasses()
    {
        return $this->getValue('test classes');
    }

    public function getTestMethods()
    {
        return $this->getValue('test methods');
    }

    public function toArray()
    {
        return [
            'files'                       => $this->getFiles(),
            'loc'                         => $this->getLines(),
            'lloc'                        => $this->getLogicalLines(),
            'llocClasses'                 => $this->getClassLines(),
            'llocFunctions'               => $this->getFunctionLines(),
            'llocGlobal'                  => $this->getNotInClassesOrFunctions(),
            'cloc'                        => $this->getCommentLines(),
            'ccn'                         => $this->getComplexity(),
            'ccnMethods'                  => $this->getMethodComplexity(),
            'interfaces'                  => $this->getInterfaces(),
            'traits'                      => $this->getTraits(),
            'classes'                     => $this->getClasses(),
            'abstractClasses'             => $this->getAbstractClasses(),
            'concreteClasses'             => $this->getConcreteClasses(),
            'finalClasses'                => $this->getFinalClasses(),
            'nonFinalClasses'             => $this->getNonFinalClasses(),
            'functions'                   => $this->getFunctions(),
            'namedFunctions'              => $this->getNamedFunctions(),
            'anonymousFunctions'          => $this->getAnonymousFunctions(),
            'methods'                     => $this->getMethods(),
            'publicMethods'               => $this->getPublicMethods(),
            'nonPublicMethods'            => $this->getNonPublicMethods(),
            'protectedMethods'            => $this->getProtectedMethods(),
            'privateMethods'              => $this->getPrivateMethods(),
            'nonStaticMethods'            => $this->getNonStaticMethods(),
            'staticMethods'               => $this->getStaticMethods(),
            'constants'                   => $this->getConstants(),
            'classConstants'              => $this->getClassConstants(),
            'publicClassConstants'        => $this->getPublicClassConstants(),
            'nonPublicClassConstants'     => $this->getNonPublicClassConstants(),
            'globalConstants'             => $this->getGlobalConstants(),
            'testClasses'                 => $this->getTestClasses(),
            'testMethods'                 => $this->getTestMethods(),
            'ccnByLloc'                   => $this->getAverageComplexityPerLogicalLine(),
            'llocByNof'                   => $this->getAverageFunctionLength(),
            'methodCalls'                 => $this->getMethodCalls(),
            'staticMethodCalls'           => $this->getStaticMethodCalls(),
            'instanceMethodCalls'         => $this->getNonStaticMethodCalls(),
            'attributeAccesses'           => $this->getAttributeAccesses(),
            'staticAttributeAccesses'     => $this->getStaticAttributeAccesses(),
            'instanceAttributeAccesses'   => $this->getNonStaticAttributeAccesses(),
            'globalAccesses'              => $this->getGlobalAccesses(),
            'globalVariableAccesses'      => $this->getGlobalVariableAccesses(),
            'superGlobalVariableAccesses' => $this->getSuperGlobalVariableAccesses(),
            'globalConstantAccesses'      => $this->getGlobalConstantAccesses(),
            'directories'                 => $this->getDirectories(),
            'classCcnMin'                 => $this->getMinimumClassComplexity(),
            'classCcnAvg'                 => $this->getAverageComplexityPerClass(),
            'classCcnMax'                 => $this->getMaximumClassComplexity(),
            'classLlocMin'                => $this->getMinimumClassLength(),
            'classLlocAvg'                => $this->getAverageClassLength(),
            'classLlocMax'                => $this->getMaximumClassLength(),
            'methodCcnMin'                => $this->getMinimumMethodComplexity(),
            'methodCcnAvg'                => $this->getAverageComplexityPerMethod(),
            'methodCcnMax'                => $this->getMaximumMethodComplexity(),
            'methodLlocMin'               => $this->getMinimumMethodLength(),
            'methodLlocAvg'               => $this->getAverageMethodLength(),
            'methodLlocMax'               => $this->getMaximumMethodLength(),
            'averageMethodsPerClass'      => $this->getAverageMethodsPerClass(),
            'minimumMethodsPerClass'      => $this->getMinimumMethodsPerClass(),
            'maximumMethodsPerClass'      => $this->getMaximumMethodsPerClass(),
            'namespaces'                  => $this->getNamespaces(),
            'ncloc'                       => $this->getNonCommentLines(),
        ];
    }

    private function getAverage($key)
    {
        return $this->divide($this->getSum($key), $this->getCount($key));
    }

    private function getCount($key)
    {
        return isset($this->counts[$key]) ? count($this->counts[$key]) : 0;
    }

    private function getSum($key)
    {
        return isset($this->counts[$key]) ? array_sum($this->counts[$key]) : 0;
    }

    private function getMaximum($key)
    {
        return isset($this->counts[$key]) ? max($this->counts[$key]) : 0;
    }

    private function getMinimum($key)
    {
        return isset($this->counts[$key]) ? min($this->counts[$key]) : 0;
    }

    private function getValue($key, $default = 0)
    {
        return $this->counts[$key] ?? $default;
    }

    private function divide($x, $y)
    {
        return $y != 0 ? $x / $y : 0;
    }
}
