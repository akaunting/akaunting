<?php

namespace Spatie\FlareClient\FlareMiddleware;

use ArrayObject;
use Closure;
use Spatie\FlareClient\Report;

class AddDocumentationLinks implements FlareMiddleware
{
    protected ArrayObject $documentationLinkResolvers;

    public function __construct(ArrayObject $documentationLinkResolvers)
    {
        $this->documentationLinkResolvers = $documentationLinkResolvers;
    }

    public function handle(Report $report, Closure $next)
    {
        if (! $throwable = $report->getThrowable()) {
            return $next($report);
        }

        $links = $this->getLinks($throwable);

        if (count($links)) {
            $report->addDocumentationLinks($links);
        }

        return $next($report);
    }

    /** @return array<int, string> */
    protected function getLinks(\Throwable $throwable): array
    {
        $allLinks = [];

        foreach ($this->documentationLinkResolvers as $resolver) {
            $resolvedLinks = $resolver($throwable);

            if (is_null($resolvedLinks)) {
                continue;
            }

            if (is_string($resolvedLinks)) {
                $resolvedLinks = [$resolvedLinks];
            }

            foreach ($resolvedLinks as $link) {
                $allLinks[] = $link;
            }
        }

        return array_values(array_unique($allLinks));
    }
}
