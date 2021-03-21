<?php

namespace Enlightn\Enlightn\Analyzers;

use Illuminate\Support\Str;
use JsonSerializable;
use RuntimeException;

class Trace implements JsonSerializable
{
    /**
     * @var int
     */
    public $lineNumber;

    /**
     * @var string|null
     */
    public $details;

    /**
     * @var string|null
     */
    public $path;

    /**
     * @var array
     */
    public $codeSnippet = [];

    /**
     * @var int
     */
    private $snippetLineCount = 30;

    public function __construct($path, $lineNumber, $details = null)
    {
        $this->path = $path;
        $this->lineNumber = $lineNumber;
        $this->details = $details;
    }

    /**
     * @return array
     */
    public function codeSnippet()
    {
        if (! file_exists($path = $this->absolutePath())) {
            return [];
        }

        if (! empty($this->codeSnippet)) {
            // Return cached value if already computed.
            return $this->codeSnippet;
        }

        try {
            $file = new File($path);

            [$startLineNumber, $endLineNumber] = $this->getBounds($file->numberOfLines());

            $codeSnippet = [];

            $line = $file->getLine($startLineNumber);

            $currentLineNumber = $startLineNumber;

            while ($currentLineNumber <= $endLineNumber) {
                $codeSnippet[$currentLineNumber] = rtrim(substr($line, 0, 250));

                $line = $file->getNextLine();
                $currentLineNumber++;
            }

            $this->codeSnippet = $codeSnippet;

            return $this->codeSnippet;
        } catch (RuntimeException $exception) {
            return [];
        }
    }

    /**
     * @return string|null
     */
    public function relativePath()
    {
        return trim(Str::contains($this->path, base_path()) ? Str::after($this->path, base_path()) : $this->path, '/');
    }

    /**
     * @return string|null
     */
    public function absolutePath()
    {
        if (! file_exists($this->path)) {
            return base_path(trim($this->path, '/'));
        }

        return $this->path;
    }

    /**
     * @param int $snippetLineCount
     * @return $this
     */
    public function setSnippetLineCount(int $snippetLineCount)
    {
        $this->snippetLineCount = $snippetLineCount;

        return $this;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'path' => $this->relativePath(),
            'lineNumber' => $this->lineNumber,
            'details' => $this->details,
            'codeSnippet' => $this->codeSnippet(),
        ];
    }

    /**
     * @param int $totalLines
     * @return array
     */
    private function getBounds(int $totalLines)
    {
        $startLine = max($this->lineNumber - floor($this->snippetLineCount / 2), 1);

        $endLine = $startLine + ($this->snippetLineCount - 1);

        if ($endLine > $totalLines) {
            $endLine = $totalLines;
            $startLine = max($endLine - ($this->snippetLineCount - 1), 1);
        }

        return [$startLine, $endLine];
    }
}
