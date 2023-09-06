<?php declare(strict_types=1);

namespace Wnx\LaravelStats\Outputs;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Wnx\LaravelStats\Project;
use Illuminate\Console\OutputStyle;
use Symfony\Component\Console\Helper\Table;
use Wnx\LaravelStats\ValueObjects\Component;
use Symfony\Component\Console\Helper\TableCell;
use Wnx\LaravelStats\Statistics\NumberOfRoutes;
use Symfony\Component\Console\Helper\TableStyle;
use Wnx\LaravelStats\ValueObjects\ClassifiedClass;
use Symfony\Component\Console\Helper\TableSeparator;

class AsciiTableOutput
{
    /**
     * Console output.
     *
     * @var \Illuminate\Console\OutputStyle
     */
    protected $output;

    /**
     * @var bool
     */
    protected $isVerbose = false;

    protected $project;

    /**
     * Create new instance of JsonOutput.
     */
    public function __construct(OutputStyle $output)
    {
        $this->output = $output;
    }

    public function render(Project $project, bool $isVerbose = false, array $filterByComponentName = [])
    {
        $this->isVerbose = $isVerbose;
        $this->project = $project;

        $groupedByComponent = $project->classifiedClassesGroupedAndFilteredByComponentNames($filterByComponentName);

        $table = new Table($this->output);
        $this->rightAlignNumbers($table);

        $table
            ->setHeaders(['Name', 'Classes', 'Methods', 'Methods/Class', 'LoC', 'LLoC', 'LLoC/Method']);

        // Render "Core" components
        $this->renderComponents($table, $groupedByComponent->filter(function ($_, $key) {
            return $key !== 'Other' && ! Str::contains($key, 'Test');
        }));

        // Render Test components
        $this->renderComponents($table, $groupedByComponent->filter(function ($_, $key) {
            return Str::contains($key, 'Test');
        }));

        // Render "Other" component
        $this->renderComponents($table, $groupedByComponent->filter(function ($_, $key) {
            return $key == 'Other';
        }));

        $table->addRow(new TableSeparator);
        $this->addTotalRow($table);
        $this->addMetaRow($table);

        $table->render();
    }

    private function renderComponents(Table $table, Collection $groupedByComponent)
    {
        foreach ($groupedByComponent as $componentName => $classifiedClasses) {
            $component = new Component($componentName, $classifiedClasses);

            $this->addComponentTableRow($table, $component);

            // If the verbose option has been passed, also display each
            // classified Class in it's own row
            if ($this->isVerbose) {
                foreach ($classifiedClasses as $classifiedClass) {
                    $this->addClassifiedClassTableRow($table, $classifiedClass);
                }
                $table->addRow(new TableSeparator);
            }
        }
    }

    private function addComponentTableRow(Table $table, Component $component): void
    {
        $table->addRow([
            'name' => $component->name,
            'number_of_classes' => $component->getNumberOfClasses(),
            'number_of_methods' => $component->getNumberOfMethods(),
            'methods_per_class' => $component->getNumberOfMethodsPerClass(),
            'loc' => $component->getLinesOfCode(),
            'lloc' => $component->getLogicalLinesOfCode(),
            'lloc_per_method' => $component->getLogicalLinesOfCodePerMethod(),
        ]);
    }

    private function addClassifiedClassTableRow(Table $table, ClassifiedClass $classifiedClass)
    {
        $table->addRow([
            new TableCell(
                '- '.$classifiedClass->reflectionClass->getName(),
                ['colspan' => 2]
            ),
            $classifiedClass->getNumberOfMethods(),
            $classifiedClass->getNumberOfMethods(),
            $classifiedClass->getLines(),
            $classifiedClass->getLogicalLinesOfCode(),
            $classifiedClass->getLogicalLinesOfCodePerMethod(),
        ]);
    }

    private function addTotalRow(Table $table)
    {
        $table->addRow([
            'name' => 'Total',
            'number_of_classes' => $this->project->statistic()->getNumberOfClasses(),
            'number_of_methods' => $this->project->statistic()->getNumberOfMethods(),
            'methods_per_class' => $this->project->statistic()->getNumberOfMethodsPerClass(),
            'loc' => $this->project->statistic()->getLinesOfCode(),
            'lloc' => $this->project->statistic()->getLogicalLinesOfCode(),
            'lloc_per_method' => $this->project->statistic()->getLogicalLinesOfCodePerMethod(),
        ]);
    }

    private function addMetaRow(Table $table)
    {
        $table->setFooterTitle(implode(' • ', [
            "Code LLoC: {$this->project->statistic()->getLogicalLinesOfCodeForApplicationCode()}",
            "Test LLoC: {$this->project->statistic()->getLogicalLinesOfCodeForTestCode()}",
            'Code/Test Ratio: 1:'.$this->project->statistic()->getApplicationCodeToTestCodeRatio(),
            'Routes: '.app(NumberOfRoutes::class)->get(),
        ]));
    }

    private function rightAlignNumbers(Table $table)
    {
        for ($i = 1; $i <= 6; $i++) {
            $table->setColumnStyle($i, (new TableStyle)->setPadType(STR_PAD_LEFT));
        }
    }
}
