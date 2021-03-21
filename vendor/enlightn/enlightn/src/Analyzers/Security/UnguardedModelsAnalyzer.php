<?php

namespace Enlightn\Enlightn\Analyzers\Security;

use Enlightn\Enlightn\Analyzers\Concerns\InspectsCode;
use Enlightn\Enlightn\Inspection\Inspector;
use Enlightn\Enlightn\Inspection\QueryBuilder;
use Illuminate\Database\Eloquent\Model;

class UnguardedModelsAnalyzer extends SecurityAnalyzer
{
    use InspectsCode;

    /**
     * The title describing the analyzer.
     *
     * @var string|null
     */
    public $title = 'Your application does not un-guard models.';

    /**
     * The severity of the analyzer.
     *
     * @var string|null
     */
    public $severity = self::SEVERITY_MAJOR;

    /**
     * The time to fix in minutes.
     *
     * @var int|null
     */
    public $timeToFix = 30;

    /**
     * Get the error message describing the analyzer insights.
     *
     * @return string
     */
    public function errorMessage()
    {
        return "Your application un-guards models, which guards against mass assignment vulnerabilities. "
            ."The Laravel Framework includes this protection by default and it is advisable not to override "
            ."this check. While properly validating requests can mitigate the risk, guarding models by "
            ."default makes your code more readable towards mass assignment vulnerabilities. For instance, "
            ."an alternative to un-guarding models, is using the forceFill method on the model. While typing "
            ."or reviewing this code, it will be much more obvious to developers to validate the request "
            ."before force-filling the model.";
    }

    /**
     * Execute the analyzer.
     *
     * @param \Enlightn\Enlightn\Inspection\Inspector $inspector
     * @return void
     */
    public function handle(Inspector $inspector)
    {
        $builder = (new QueryBuilder())->doesntHaveStaticCall(Model::class, 'unguard');

        $this->inspectCode($inspector, $builder);
    }
}
