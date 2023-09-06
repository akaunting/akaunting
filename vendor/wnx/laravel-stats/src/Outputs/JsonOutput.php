<?php declare(strict_types=1);

namespace Wnx\LaravelStats\Outputs;

use Wnx\LaravelStats\Project;
use Illuminate\Support\Collection;
use Wnx\LaravelStats\ValueObjects\Component;
use Wnx\LaravelStats\Statistics\NumberOfRoutes;
use Wnx\LaravelStats\ValueObjects\ClassifiedClass;

class JsonOutput
{
    public function render(Project $project, bool $isVerbose = false, array $filterByComponentName = [])
    {
        $jsonStructure = [
            'components' => [],
            'total' => $this->getTotalArray($project),
            'meta' => $this->getMetaArray($project),
        ];

        $groupedByComponent = $project->classifiedClassesGroupedAndFilteredByComponentNames($filterByComponentName);

        foreach ($groupedByComponent as $componentName => $classifiedClasses) {
            $singleComponent = $this->getStatisticsArrayComponent($componentName, $classifiedClasses);

            if ($isVerbose) {
                $arrayOfClasses = [];

                foreach ($classifiedClasses as $classifiedClass) {
                    $arrayOfClasses[] = $this->getStatisticsArrayForSingleClass($classifiedClass);
                }

                $singleComponent['classes'] = $arrayOfClasses;
            }

            $jsonStructure['components'][] = $singleComponent;
        }

        return $jsonStructure;
    }

    private function getTotalArray(Project $project): array
    {
        return [
            'number_of_classes' => $project->statistic()->getNumberOfClasses(),
            'number_of_methods' => $project->statistic()->getNumberOfMethods(),
            'methods_per_class' => $project->statistic()->getNumberOfMethodsPerClass(),
            'loc' => $project->statistic()->getLinesOfCode(),
            'lloc' => $project->statistic()->getLogicalLinesOfCode(),
            'lloc_per_method' => $project->statistic()->getLogicalLinesOfCodePerMethod(),
        ];
    }

    private function getMetaArray(Project $project): array
    {
        return [
            'code_lloc' => $project->statistic()->getLogicalLinesOfCodeForApplicationCode(),
            'test_lloc' => $project->statistic()->getLogicalLinesOfCodeForTestCode(),
            'code_to_test_ratio' => $project->statistic()->getApplicationCodeToTestCodeRatio(),
            'number_of_routes' => app(NumberOfRoutes::class)->get(),
        ];
    }

    private function getStatisticsArrayComponent(string $componentName, Collection $classifiedClasses): array
    {
        $component = new Component($componentName, $classifiedClasses);

        return [
            'name' => $component->name,
            'number_of_classes' => $component->getNumberOfClasses(),
            'number_of_methods' => $component->getNumberOfMethods(),
            'methods_per_class' => $component->getNumberOfMethodsPerClass(),
            'loc' => $component->getLinesOfCode(),
            'lloc' => $component->getLogicalLinesOfCode(),
            'lloc_per_method' => $component->getLogicalLinesOfCodePerMethod(),
        ];
    }

    private function getStatisticsArrayForSingleClass(ClassifiedClass $classifiedClass): array
    {
        return [
            'name' => $classifiedClass->reflectionClass->getName(),
            'methods' => $classifiedClass->getNumberOfMethods(),
            'methods_per_class' => $classifiedClass->getNumberOfMethods(),
            'loc' => $classifiedClass->getLines(),
            'lloc' => $classifiedClass->getLogicalLinesOfCode(),
            'lloc_per_method' => $classifiedClass->getLogicalLinesOfCodePerMethod(),
        ];
    }
}
