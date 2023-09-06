<?php

namespace Spatie\Ignition\Solutions\SolutionProviders;

use Illuminate\Support\Str;
use ParseError;
use Spatie\Ignition\Contracts\BaseSolution;
use Spatie\Ignition\Contracts\HasSolutionsForThrowable;
use Throwable;

class MergeConflictSolutionProvider implements HasSolutionsForThrowable
{
    public function canSolve(Throwable $throwable): bool
    {
        if (! ($throwable instanceof ParseError)) {
            return false;
        }

        if (! $this->hasMergeConflictExceptionMessage($throwable)) {
            return false;
        }

        $file = (string)file_get_contents($throwable->getFile());

        if (! str_contains($file, '=======')) {
            return false;
        }
        if (! str_contains($file, '>>>>>>>')) {
            return false;
        }

        return true;
    }

    public function getSolutions(Throwable $throwable): array
    {
        $file = (string)file_get_contents($throwable->getFile());
        preg_match('/\>\>\>\>\>\>\> (.*?)\n/', $file, $matches);
        $source = $matches[1];

        $target = $this->getCurrentBranch(basename($throwable->getFile()));

        return [
            BaseSolution::create("Merge conflict from branch '$source' into $target")
                ->setSolutionDescription('You have a Git merge conflict. To undo your merge do `git reset --hard HEAD`'),
        ];
    }

    protected function getCurrentBranch(string $directory): string
    {
        $branch = "'".trim((string)shell_exec("cd {$directory}; git branch | grep \\* | cut -d ' ' -f2"))."'";

        if ($branch === "''") {
            $branch = 'current branch';
        }

        return $branch;
    }

    protected function hasMergeConflictExceptionMessage(Throwable $throwable): bool
    {
        // For PHP 7.x and below
        if (Str::startsWith($throwable->getMessage(), 'syntax error, unexpected \'<<\'')) {
            return true;
        }

        // For PHP 8+
        if (Str::startsWith($throwable->getMessage(), 'syntax error, unexpected token "<<"')) {
            return true;
        }

        return false;
    }
}
