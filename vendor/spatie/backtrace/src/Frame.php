<?php

namespace Spatie\Backtrace;

class Frame
{
    /** @var string */
    public $file;

    /** @var int */
    public $lineNumber;

    /** @var array|null */
    public $arguments = null;

    /** @var bool */
    public $applicationFrame;

    /** @var string|null */
    public $method;

    /** @var string|null */
    public $class;

    public function __construct(
        string $file,
        int $lineNumber,
        ?array $arguments,
        string $method = null,
        string $class = null,
        bool $isApplicationFrame = false
    ) {
        $this->file = $file;

        $this->lineNumber = $lineNumber;

        $this->arguments = $arguments;

        $this->method = $method;

        $this->class = $class;

        $this->applicationFrame = $isApplicationFrame;
    }

    public function getSnippet(int $lineCount): array
    {
        return (new CodeSnippet())
            ->surroundingLine($this->lineNumber)
            ->snippetLineCount($lineCount)
            ->get($this->file);
    }

    public function getSnippetAsString(int $lineCount): string
    {
        return (new CodeSnippet())
            ->surroundingLine($this->lineNumber)
            ->snippetLineCount($lineCount)
            ->getAsString($this->file);
    }

    public function getSnippetProperties(int $lineCount): array
    {
        $snippet = $this->getSnippet($lineCount);

        return array_map(function (int $lineNumber) use ($snippet) {
            return [
                'line_number' => $lineNumber,
                'text' => $snippet[$lineNumber],
            ];
        }, array_keys($snippet));
    }
}
