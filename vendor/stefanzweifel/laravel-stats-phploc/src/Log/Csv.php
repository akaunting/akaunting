<?php declare(strict_types=1);
/*
 * This file is part of PHPLOC.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace SebastianBergmann\PHPLOC\Log;

use const PHP_EOL;
use function array_values;
use function file_put_contents;
use function implode;
use InvalidArgumentException;

final class Csv
{
    private $colmap = [
        'directories'                 => 'Directories',
        'files'                       => 'Files',
        'loc'                         => 'Lines of Code (LOC)',
        'ccnByLloc'                   => 'Cyclomatic Complexity / Lines of Code',
        'cloc'                        => 'Comment Lines of Code (CLOC)',
        'ncloc'                       => 'Non-Comment Lines of Code (NCLOC)',
        'lloc'                        => 'Logical Lines of Code (LLOC)',
        'llocGlobal'                  => 'LLOC outside functions or classes',
        'namespaces'                  => 'Namespaces',
        'interfaces'                  => 'Interfaces',
        'traits'                      => 'Traits',
        'classes'                     => 'Classes',
        'abstractClasses'             => 'Abstract Classes',
        'concreteClasses'             => 'Concrete Classes',
        'finalClasses'                => 'Final Classes',
        'nonFinalClasses'             => 'Non-Final Classes',
        'llocClasses'                 => 'Classes Length (LLOC)',
        'methods'                     => 'Methods',
        'nonStaticMethods'            => 'Non-Static Methods',
        'staticMethods'               => 'Static Methods',
        'publicMethods'               => 'Public Methods',
        'nonPublicMethods'            => 'Non-Public Methods',
        'protectedMethods'            => 'Protected Methods',
        'privateMethods'              => 'Private Methods',
        'classCcnAvg'                 => 'Cyclomatic Complexity / Number of Classes' /* In Text output: 'Average Complexity per Class' */,
        'methodCcnAvg'                => 'Cyclomatic Complexity / Number of Methods',
        'functions'                   => 'Functions',
        'namedFunctions'              => 'Named Functions',
        'anonymousFunctions'          => 'Anonymous Functions',
        'llocFunctions'               => 'Functions Length (LLOC)',
        'llocByNof'                   => 'Average Function Length (LLOC)',
        'classLlocAvg'                => 'Average Class Length',
        'methodLlocAvg'               => 'Average Method Length',
        'averageMethodsPerClass'      => 'Average Methods per Class',
        'constants'                   => 'Constants',
        'globalConstants'             => 'Global Constants',
        'classConstants'              => 'Class Constants',
        'publicClassConstants'        => 'Public Class Constants',
        'nonPublicClassConstants'     => 'Non-Public Class Constants',
        'attributeAccesses'           => 'Attribute Accesses',
        'instanceAttributeAccesses'   => 'Non-Static Attribute Accesses',
        'staticAttributeAccesses'     => 'Static Attribute Accesses',
        'methodCalls'                 => 'Method Calls',
        'instanceMethodCalls'         => 'Non-Static Method Calls',
        'staticMethodCalls'           => 'Static Method Calls',
        'globalAccesses'              => 'Global Accesses',
        'globalVariableAccesses'      => 'Global Variable Accesses',
        'superGlobalVariableAccesses' => 'Super-Global Variable Accesses',
        'globalConstantAccesses'      => 'Global Constant Accesses',
        'testClasses'                 => 'Test Classes',
        'testMethods'                 => 'Test Methods',
    ];

    public function printResult(string $filename, array $count): void
    {
        file_put_contents(
            $filename,
            $this->getKeysLine($count) . $this->getValuesLine($count)
        );
    }

    private function getKeysLine(array $count): string
    {
        return implode(',', array_values($this->colmap)) . PHP_EOL;
    }

    /**
     * @throws InvalidArgumentException
     */
    private function getValuesLine(array $count): string
    {
        $values = [];

        foreach ($this->colmap as $key => $name) {
            if (isset($count[$key])) {
                $values[] = $count[$key];
            } else {
                throw new InvalidArgumentException('Attempted to print row with missing keys');
            }
        }

        return '"' . implode('","', $values) . '"' . PHP_EOL;
    }
}
