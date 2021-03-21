<?php

namespace Enlightn\Enlightn\Analyzers\Concerns;

use Enlightn\Enlightn\Inspection\Inspector;
use Enlightn\Enlightn\Inspection\QueryBuilder;

trait InspectsCode
{
    /**
     * Inspect the code, record the errors in the inspector and determine if the code passes the analysis.
     *
     * @param \Enlightn\Enlightn\Inspection\Inspector $inspector
     * @param \Enlightn\Enlightn\Inspection\QueryBuilder $builder
     * @return bool
     */
    protected function passesCodeInspection(Inspector $inspector, QueryBuilder $builder)
    {
        $inspector->inspect($builder);

        return $inspector->passed();
    }

    /**
     * Inspect the code and record error traces if the inspection fails.
     *
     * @param \Enlightn\Enlightn\Inspection\Inspector $inspector
     * @param \Enlightn\Enlightn\Inspection\QueryBuilder $builder
     */
    protected function inspectCode(Inspector $inspector, QueryBuilder $builder)
    {
        if (! $this->passesCodeInspection($inspector, $builder)) {
            collect($inspector->getLastErrors())->each(function ($trace) {
                $this->pushTrace($trace);
            });

            // Although adding traces would also mark it as failed, but there may be no traces
            // at all, yet should still be failed.
            if (empty($inspector->getLastErrors())) {
                $this->markFailed();
            }
        }
    }
}
