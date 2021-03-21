<?php

namespace Lorisleiva\LaravelSearchString\Compiler;

use Hoa\Compiler\Llk\TreeNode;
use Hoa\Visitor\Element;
use Hoa\Visitor\Visit;
use Illuminate\Support\Collection;
use Lorisleiva\LaravelSearchString\AST\AndSymbol;
use Lorisleiva\LaravelSearchString\AST\ListSymbol;
use Lorisleiva\LaravelSearchString\AST\NotSymbol;
use Lorisleiva\LaravelSearchString\AST\OrSymbol;
use Lorisleiva\LaravelSearchString\AST\QuerySymbol;
use Lorisleiva\LaravelSearchString\AST\RelationshipSymbol;
use Lorisleiva\LaravelSearchString\AST\SoloSymbol;
use Lorisleiva\LaravelSearchString\AST\Symbol;
use Lorisleiva\LaravelSearchString\Exceptions\InvalidSearchStringException;

class HoaConverterVisitor implements Visit
{
    public function visit(Element $element, &$handle = null, $eldnah = null)
    {
        /** @var TreeNode $element */
        switch ($element->getId()) {
            case '#OrNode':
                return new OrSymbol($this->parseChildren($element));
            case '#AndNode':
                return new AndSymbol($this->parseChildren($element));
            case '#NotNode':
                return new NotSymbol($this->parseChildren($element)->get(0));
            case '#NestedRelationshipNode':
                return $this->parseNestedRelationshipNode($element);
            case '#RelationshipNode':
                return $this->parseRelationshipNode($element);
            case '#SoloNode':
                return $this->parseSoloNode($element);
            case '#QueryNode':
                return $this->parseQueryNode($element);
            case '#ListNode':
                return $this->parseListNode($element);
            case '#ScalarList':
                return $this->parseScalarList($element);
            case '#NestedTerms':
                return $this->parseChildren($element);
            case 'token':
                return $this->parseToken($element);
        }
    }

    protected function parseNestedRelationshipNode(TreeNode $element): RelationshipSymbol
    {
        $children = $this->parseChildren($element);
        $terms = Collection::wrap($children->get(0));
        $head = $terms->shift();
        $expression = $this->parseRelationshipExpression($terms, $children->get(1));
        $expectedArgs = $children->has(2) ? [$children->get(2), $children->get(3)]: [];

        return new RelationshipSymbol($head, $expression, ...$expectedArgs);
    }

    protected function parseRelationshipNode(TreeNode $element): RelationshipSymbol
    {
        $children = $this->parseChildren($element);
        $terms = Collection::wrap($children->get(0));
        $head = $terms->shift();
        $expression = $this->parseRelationshipExpression($terms, [$children->get(1), $children->get(2)]);

        return new RelationshipSymbol($head, $expression);
    }

    protected function parseRelationshipExpression(Collection $terms, $expression): Symbol
    {
        if ($expression instanceof Symbol && $terms->isEmpty()) {
            return $expression;
        }

        if (is_array($expression) && $terms->count() === 1) {
            return $expression[0]
                ? new QuerySymbol($terms->first(), $expression[0], $expression[1])
                : new SoloSymbol($terms->first());
        }

        $head = $terms->shift();
        $expression = $this->parseRelationshipExpression($terms, $expression);
        return new RelationshipSymbol($head, $expression);
    }

    protected function parseSoloNode(TreeNode $element): SoloSymbol
    {
        return new SoloSymbol(
            $this->parseChildren($element)->get(0, '')
        );
    }

    protected function parseQueryNode(TreeNode $element): QuerySymbol
    {
        if (($children = $this->parseChildren($element))->count() < 2) {
            throw InvalidSearchStringException::fromVisitor('QueryNode expects at least two children.');
        }

        return new QuerySymbol(
            $children->get(0),
            $children->get(1),
            $children->get(2, '')
        );
    }

    protected function parseListNode(TreeNode $element): ListSymbol
    {
        if (($children = $this->parseChildren($element))->count() !== 2) {
            throw InvalidSearchStringException::fromVisitor('ListNode expects two children.');
        }

        return new ListSymbol(
            $children->get(0),
            $children->get(1)
        );
    }

    protected function parseScalarList(TreeNode $element): array
    {
        return $this->parseChildren($element)->toArray();
    }

    protected function parseToken(TreeNode $element)
    {
        switch ($element->getValueToken()) {
            case 'T_ASSIGNMENT':
                return '=';
            case 'T_NULL':
                return null;
            default:
                return $element->getValueValue();
        }
    }

    protected function parseChildren(TreeNode $element): Collection
    {
        if  (! $children = $element->getChildren()) {
            return collect();
        }

        return collect($children)->map(function (TreeNode $child) {
            return $child->accept($this);
        });
    }
}
