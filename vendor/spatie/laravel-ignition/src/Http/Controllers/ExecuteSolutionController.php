<?php

namespace Spatie\LaravelIgnition\Http\Controllers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Spatie\Ignition\Contracts\SolutionProviderRepository;
use Spatie\LaravelIgnition\Exceptions\CannotExecuteSolutionForNonLocalIp;
use Spatie\LaravelIgnition\Http\Requests\ExecuteSolutionRequest;
use Spatie\LaravelIgnition\Support\RunnableSolutionsGuard;

class ExecuteSolutionController
{
    use ValidatesRequests;

    public function __invoke(
        ExecuteSolutionRequest $request,
        SolutionProviderRepository $solutionProviderRepository
    ) {
        $this
            ->ensureRunnableSolutionsEnabled()
            ->ensureLocalRequest();

        $solution = $request->getRunnableSolution();

        $solution->run($request->get('parameters', []));

        return response()->noContent();
    }

    public function ensureRunnableSolutionsEnabled(): self
    {
        // Should already be checked in middleware but we want to be 100% certain.
        abort_unless(RunnableSolutionsGuard::check(), 400);

        return $this;
    }

    public function ensureLocalRequest(): self
    {
        $ipIsPublic = filter_var(
            request()->ip(),
            FILTER_VALIDATE_IP,
            FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
        );

        if ($ipIsPublic) {
            throw CannotExecuteSolutionForNonLocalIp::make();
        }

        return $this;
    }
}
