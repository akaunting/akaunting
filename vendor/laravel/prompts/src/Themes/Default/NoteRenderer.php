<?php

namespace Laravel\Prompts\Themes\Default;

use Laravel\Prompts\Note;

class NoteRenderer extends Renderer
{
    /**
     * Render the note.
     */
    public function __invoke(Note $note): string
    {
        $lines = collect(explode(PHP_EOL, $note->message));

        switch ($note->type) {
            case 'intro':
            case 'outro':
                $lines = $lines->map(fn ($line) => " {$line} ");
                $longest = $lines->map(fn ($line) => strlen($line))->max();

                $lines
                    ->each(function ($line) use ($longest) {
                        $line = str_pad($line, $longest, ' ');
                        $this->line(" {$this->bgCyan($this->black($line))}");
                    });

                return $this;

            case 'warning':
                $lines->each(fn ($line) => $this->line($this->yellow(" {$line}")));

                return $this;

            case 'error':
                $lines->each(fn ($line) => $this->line($this->red(" {$line}")));

                return $this;

            case 'alert':
                $lines->each(fn ($line) => $this->line(" {$this->bgRed($this->white(" {$line} "))}"));

                return $this;

            case 'info':
                $lines->each(fn ($line) => $this->line($this->green(" {$line}")));

                return $this;

            default:
                $lines->each(fn ($line) => $this->line(" {$line}"));

                return $this;
        }
    }
}
