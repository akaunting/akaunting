<?php

namespace Tests\Unit\Listeners;

use App\Abstracts\Listeners\Report;
use PHPUnit\Framework\TestCase;

/**
 * Covers App\Abstracts\Listeners\Report::filterRowsBySearchString(): the rows a contact, account or category
 * chip keeps, with "not" turning the chosen ids into the ones left out.
 */
class FilterRowsBySearchStringTest extends TestCase
{
    protected const ROWS = [1 => 'Alpha', 2 => 'Beta', 3 => 'Gamma'];

    protected function filter(string $search, string $key = 'category_id'): array
    {
        $listener = new class extends Report {};

        return $listener->filterRowsBySearchString(static::ROWS, $key, $search);
    }

    public function testItKeepsEveryRowWithoutTheKey(): void
    {
        $this->assertSame(static::ROWS, $this->filter('basis:cash contact_id:2'));
    }

    public function testItKeepsTheChosenRows(): void
    {
        $this->assertSame([1 => 'Alpha', 3 => 'Gamma'], $this->filter('category_id:1,3'));
    }

    public function testItLeavesOutTheChosenRowsAfterNot(): void
    {
        $this->assertSame([2 => 'Beta', 3 => 'Gamma'], $this->filter('not category_id:1'));
        $this->assertSame([2 => 'Beta'], $this->filter('basis:cash not category_id:1,3'));
    }

    public function testItReadsOnlyItsOwnKey(): void
    {
        $this->assertSame([2 => 'Beta'], $this->filter('not category_id:1 contact_id:2', 'contact_id'));
        $this->assertSame([2 => 'Beta', 3 => 'Gamma'], $this->filter('not category_id:1 contact_id:2'));
    }

    public function testItKeepsEveryRowForARange(): void
    {
        $this->assertSame(static::ROWS, $this->filter('category_id>=2'));
    }
}
