<?php

namespace Lorisleiva\LaravelSearchString\Concerns;

use Lorisleiva\LaravelSearchString\SearchStringManager;
use Lorisleiva\LaravelSearchString\Visitors\AttachRulesVisitor;
use Lorisleiva\LaravelSearchString\Visitors\BuildColumnsVisitor;
use Lorisleiva\LaravelSearchString\Visitors\BuildKeywordsVisitor;
use Lorisleiva\LaravelSearchString\Visitors\IdentifyRelationshipsFromRulesVisitor;
use Lorisleiva\LaravelSearchString\Visitors\OptimizeAstVisitor;
use Lorisleiva\LaravelSearchString\Visitors\RemoveKeywordsVisitor;
use Lorisleiva\LaravelSearchString\Visitors\RemoveNotSymbolVisitor;
use Lorisleiva\LaravelSearchString\Visitors\ValidateRulesVisitor;

trait SearchString
{
    public function getSearchStringManager()
    {
        $managerClass = config('search-string.manager', SearchStringManager::class);
        return new $managerClass($this);
    }

    public function getSearchStringOptions()
    {
        return [
            'columns' => $this->searchStringColumns ?? [],
            'keywords' => $this->searchStringKeywords ?? [],
        ];
    }

    public function getSearchStringVisitors($manager, $builder)
    {
        return [
            new AttachRulesVisitor($manager),
            new IdentifyRelationshipsFromRulesVisitor(),
            new ValidateRulesVisitor(),
            new RemoveNotSymbolVisitor(),
            new BuildKeywordsVisitor($manager, $builder),
            new RemoveKeywordsVisitor(),
            new OptimizeAstVisitor(),
            new BuildColumnsVisitor($manager, $builder),
        ];
    }

    public function scopeUsingSearchString($query, $string)
    {
        $this->getSearchStringManager()->updateBuilder($query, $string);
    }
}
