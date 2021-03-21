<?php

namespace Doctrine\Common\Annotations;

use ReflectionClass;
use ReflectionFunction;
use SplFileObject;

use function is_file;
use function method_exists;
use function preg_quote;
use function preg_replace;

/**
 * Parses a file for namespaces/use/class declarations.
 */
final class PhpParser
{
    /**
     * Parses a class.
     *
     * @deprecated use parseUseStatements instead
     *
     * @param ReflectionClass $class A <code>ReflectionClass</code> object.
     *
     * @return array<string, class-string> A list with use statements in the form (Alias => FQN).
     */
    public function parseClass(ReflectionClass $class)
    {
        return $this->parseUseStatements($class);
    }

    /**
     * Parse a class or function for use statements.
     *
     * @param ReflectionClass|ReflectionFunction $reflection
     *
     * @psalm-return array<string, string> a list with use statements in the form (Alias => FQN).
     */
    public function parseUseStatements($reflection): array
    {
        if (method_exists($reflection, 'getUseStatements')) {
            return $reflection->getUseStatements();
        }

        $filename = $reflection->getFileName();

        if ($filename === false) {
            return [];
        }

        $content = $this->getFileContent($filename, $reflection->getStartLine());

        if ($content === null) {
            return [];
        }

        $namespace = preg_quote($reflection->getNamespaceName());
        $content   = preg_replace('/^.*?(\bnamespace\s+' . $namespace . '\s*[;{].*)$/s', '\\1', $content);
        $tokenizer = new TokenParser('<?php ' . $content);

        return $tokenizer->parseUseStatements($reflection->getNamespaceName());
    }

    /**
     * Gets the content of the file right up to the given line number.
     *
     * @param string $filename   The name of the file to load.
     * @param int    $lineNumber The number of lines to read from file.
     *
     * @return string|null The content of the file or null if the file does not exist.
     */
    private function getFileContent($filename, $lineNumber)
    {
        if (! is_file($filename)) {
            return null;
        }

        $content = '';
        $lineCnt = 0;
        $file    = new SplFileObject($filename);
        while (! $file->eof()) {
            if ($lineCnt++ === $lineNumber) {
                break;
            }

            $content .= $file->fgets();
        }

        return $content;
    }
}
