<?php

namespace Spatie\Ignition\ErrorPage;

use Spatie\FlareClient\Report;
use Spatie\FlareClient\Truncation\ReportTrimmer;
use Spatie\Ignition\Config\IgnitionConfig;
use Spatie\Ignition\Contracts\Solution;
use Spatie\Ignition\Solutions\SolutionTransformer;
use Throwable;

class ErrorPageViewModel
{
    /**
     * @param \Throwable|null $throwable
     * @param \Spatie\Ignition\Config\IgnitionConfig $ignitionConfig
     * @param \Spatie\FlareClient\Report $report
     * @param array<int, Solution> $solutions
     * @param string|null $solutionTransformerClass
     */
    public function __construct(
        protected ?Throwable $throwable,
        protected IgnitionConfig $ignitionConfig,
        protected Report $report,
        protected array $solutions,
        protected ?string $solutionTransformerClass = null,
        protected string $customHtmlHead = '',
        protected string $customHtmlBody = ''
    ) {
        $this->solutionTransformerClass ??= SolutionTransformer::class;
    }

    public function throwableString(): string
    {
        if (! $this->throwable) {
            return '';
        }

        $throwableString = sprintf(
            "%s: %s in file %s on line %d\n\n%s\n",
            get_class($this->throwable),
            $this->throwable->getMessage(),
            $this->throwable->getFile(),
            $this->throwable->getLine(),
            $this->report->getThrowable()?->getTraceAsString()
        );

        return htmlspecialchars($throwableString);
    }

    public function title(): string
    {
        return htmlspecialchars($this->report->getMessage());
    }

    /**
     * @return array<string, mixed>
     */
    public function config(): array
    {
        return $this->ignitionConfig->toArray();
    }

    public function theme(): string
    {
        return $this->config()['theme'] ?? 'auto';
    }

    /**
     * @return array<int, mixed>
     */
    public function solutions(): array
    {
        return array_map(function (Solution $solution) {
            /** @var class-string $transformerClass */
            $transformerClass = $this->solutionTransformerClass;

            /** @var SolutionTransformer $transformer */
            $transformer = new $transformerClass($solution);

            return ($transformer)->toArray();
        }, $this->solutions);
    }

    /**
     * @return array<string, mixed>
     */
    public function report(): array
    {
        return $this->report->toArray();
    }

    public function jsonEncode(mixed $data): string
    {
        $jsonOptions = JSON_PARTIAL_OUTPUT_ON_ERROR | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT;

        return (string) json_encode($data, $jsonOptions);
    }

    public function getAssetContents(string $asset): string
    {
        $assetPath = __DIR__."/../../resources/compiled/{$asset}";

        return (string) file_get_contents($assetPath);
    }

    /**
     * @return array<int|string, mixed>
     */
    public function shareableReport(): array
    {
        return (new ReportTrimmer())->trim($this->report());
    }

    public function updateConfigEndpoint(): string
    {
        // TODO: Should be based on Ignition config
        return '/_ignition/update-config';
    }

    public function customHtmlHead(): string
    {
        return $this->customHtmlHead;
    }

    public function customHtmlBody(): string
    {
        return $this->customHtmlBody;
    }
}
