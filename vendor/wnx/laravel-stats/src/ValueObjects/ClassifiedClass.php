<?php declare(strict_types=1);

namespace Wnx\LaravelStats\ValueObjects;

use Wnx\LaravelStats\ReflectionClass;
use SebastianBergmann\PHPLOC\Analyser;
use Wnx\LaravelStats\Contracts\Classifier;

class ClassifiedClass
{
    /**
     * @var \Wnx\LaravelStats\ReflectionClass
     */
    public $reflectionClass;

    /**
     * Classifier Instance related to the Reflection Class.
     *
     * @var \Wnx\LaravelStats\Contracts\Classifier
     */
    public $classifier;

    /**
     * @var int
     */
    private $numberOfMethods;

    /**
     * @var int
     */
    private $numberOfPublicMethods;

    /**
     * @var int
     */
    private $numberOfNonPublicMethods;

    /**
     * @var int
     */
    private $linesOfCode;

    /**
     * @var int
     */
    private $logicalLinesOfCode;

    /**
     * @var float
     */
    private $logicalLinesOfCodePerMethod;

    public function __construct(ReflectionClass $reflectionClass, Classifier $classifier)
    {
        $this->reflectionClass = $reflectionClass;
        $this->classifier = $classifier;
    }

    /**
     * Return the total number of Methods declared in all declared classes.
     */
    public function getNumberOfMethods(): int
    {
        if ($this->numberOfMethods === null) {
            $this->numberOfMethods = $this->reflectionClass->getDefinedMethods()->count();
        }

        return $this->numberOfMethods;
    }

    public function getNumberOfPublicMethods(): int
    {
        if ($this->numberOfPublicMethods === null) {
            $this->numberOfPublicMethods = $this->reflectionClass->getDefinedMethods()
                ->filter(function (\ReflectionMethod $method) {
                    return $method->isPublic();
                })->count();
        }

        return $this->numberOfPublicMethods;
    }

    public function getNumberOfNonPublicMethods(): int
    {
        if ($this->numberOfNonPublicMethods === null) {
            $this->numberOfNonPublicMethods = $this->reflectionClass->getDefinedMethods()
                ->filter(function (\ReflectionMethod $method) {
                    return ! $method->isPublic();
                })->count();
        }

        return $this->numberOfNonPublicMethods;
    }

    /**
     * Return the total number of lines.
     */
    public function getLines(): int
    {
        if ($this->linesOfCode === null) {
            $this->linesOfCode = app(Analyser::class)
                ->countFiles([$this->reflectionClass->getFileName()], false)['loc'];
        }

        return $this->linesOfCode;
    }

    /**
     * Return the total number of lines of code.
     */
    public function getLogicalLinesOfCode(): float
    {
        if ($this->logicalLinesOfCode === null) {
            $this->logicalLinesOfCode = app(Analyser::class)
                ->countFiles([$this->reflectionClass->getFileName()], false)['lloc'];
        }

        return $this->logicalLinesOfCode;
    }

    /**
     * Return the average number of lines of code per method.
     */
    public function getLogicalLinesOfCodePerMethod(): float
    {
        if ($this->logicalLinesOfCodePerMethod === null) {
            if ($this->getNumberOfMethods() === 0) {
                $this->logicalLinesOfCodePerMethod = $this->logicalLinesOfCodePerMethod = 0;
            } else {
                $this->logicalLinesOfCodePerMethod = round($this->getLogicalLinesOfCode() / $this->getNumberOfMethods(), 2);
            }
        }

        return $this->logicalLinesOfCodePerMethod;
    }
}
