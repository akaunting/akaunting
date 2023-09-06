<?php

namespace Bugsnag\Callbacks;

use Bugsnag\Report;
use Bugsnag\Request\ResolverInterface;

class RequestContext
{
    /**
     * The request resolver instance.
     *
     * @var \Bugsnag\Request\ResolverInterface
     */
    protected $resolver;

    /**
     * Create a new request context callback instance.
     *
     * @param \Bugsnag\Request\ResolverInterface $resolver the request resolver instance
     *
     * @return void
     */
    public function __construct(ResolverInterface $resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * Execute the request context callback.
     *
     * @param \Bugsnag\Report $report the bugsnag report instance
     *
     * @return void
     */
    public function __invoke(Report $report)
    {
        if ($context = $this->resolver->resolve()->getContext()) {
            $report->setContext($context);
        }
    }
}
