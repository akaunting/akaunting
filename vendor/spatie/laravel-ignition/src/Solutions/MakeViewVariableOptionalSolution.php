<?php

namespace Spatie\LaravelIgnition\Solutions;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Str;
use Spatie\Ignition\Contracts\RunnableSolution;

class MakeViewVariableOptionalSolution implements RunnableSolution
{
    protected ?string $variableName;

    protected ?string $viewFile;

    public function __construct(string $variableName = null, string $viewFile = null)
    {
        $this->variableName = $variableName;

        $this->viewFile = $viewFile;
    }

    public function getSolutionTitle(): string
    {
        return "$$this->variableName is undefined";
    }

    public function getDocumentationLinks(): array
    {
        return [];
    }

    public function getSolutionActionDescription(): string
    {
        $output = [
            'Make the variable optional in the blade template.',
            "Replace `{{ $$this->variableName }}` with `{{ $$this->variableName ?? '' }}`",
        ];

        return implode(PHP_EOL, $output);
    }

    public function getRunButtonText(): string
    {
        return 'Make variable optional';
    }

    public function getSolutionDescription(): string
    {
        return '';
    }

    public function getRunParameters(): array
    {
        return [
            'variableName' => $this->variableName,
            'viewFile' => $this->viewFile,
        ];
    }

    /**
     * @param array<string, mixed>  $parameters
     *
     * @return bool
     */
    public function isRunnable(array $parameters = []): bool
    {
        return $this->makeOptional($this->getRunParameters()) !== false;
    }

    /**
     * @param array<string, string> $parameters
     *
     * @return void
     */
    public function run(array $parameters = []): void
    {
        $output = $this->makeOptional($parameters);
        if ($output !== false) {
            file_put_contents($parameters['viewFile'], $output);
        }
    }

    protected function isSafePath(string $path): bool
    {
        if (! Str::startsWith($path, ['/', './'])) {
            return false;
        }
        if (! Str::endsWith($path, '.blade.php')) {
            return false;
        }

        return true;
    }

    /**
     * @param array<string, string> $parameters
     *
     * @return bool|string
     */
    public function makeOptional(array $parameters = []): bool|string
    {
        if (! $this->isSafePath($parameters['viewFile'])) {
            return false;
        }

        $originalContents = (string)file_get_contents($parameters['viewFile']);
        $newContents = str_replace('$'.$parameters['variableName'], '$'.$parameters['variableName']." ?? ''", $originalContents);

        $originalTokens = token_get_all(Blade::compileString($originalContents));
        $newTokens = token_get_all(Blade::compileString($newContents));

        $expectedTokens = $this->generateExpectedTokens($originalTokens, $parameters['variableName']);

        if ($expectedTokens !== $newTokens) {
            return false;
        }

        return $newContents;
    }

    /**
     * @param array<int, mixed> $originalTokens
     * @param string $variableName
     *
     * @return array<int, mixed>
     */
    protected function generateExpectedTokens(array $originalTokens, string $variableName): array
    {
        $expectedTokens = [];
        foreach ($originalTokens as $token) {
            $expectedTokens[] = $token;
            if ($token[0] === T_VARIABLE && $token[1] === '$'.$variableName) {
                $expectedTokens[] = [T_WHITESPACE, ' ', $token[2]];
                $expectedTokens[] = [T_COALESCE, '??', $token[2]];
                $expectedTokens[] = [T_WHITESPACE, ' ', $token[2]];
                $expectedTokens[] = [T_CONSTANT_ENCAPSED_STRING, "''", $token[2]];
            }
        }

        return $expectedTokens;
    }
}
