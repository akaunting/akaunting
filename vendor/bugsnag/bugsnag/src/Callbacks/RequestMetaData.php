<?php

namespace Bugsnag\Callbacks;

use Bugsnag\Report;
use Bugsnag\Request\ResolverInterface;

class RequestMetaData
{
    /**
     * The request resolver instance.
     *
     * @var \Bugsnag\Request\ResolverInterface
     */
    protected $resolver;

    /**
     * Create a new request meta data callback instance.
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
     * Execute the request meta data callback.
     *
     * @param \Bugsnag\Report $report the bugsnag report instance
     *
     * @return void
     */
    public function __invoke(Report $report)
    {
        if ($data = $this->resolver->resolve()->getMetaData()) {
            $report->setMetaData($data);
        }
    }
}
