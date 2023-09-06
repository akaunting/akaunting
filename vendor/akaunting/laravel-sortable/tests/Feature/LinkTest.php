<?php

namespace Akaunting\Sortable\Tests\Feature;

use Akaunting\Sortable\Support\SortableLink;
use Akaunting\Sortable\Tests\TestCase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\HtmlString;

class LinkTest extends TestCase
{
    public function testQueryStringParameterWithBooleanStaysInLink(): void
    {
        $parameters = ['key' => 0, 'another-key' => null, 'another-one' => 1];
        Request::replace($parameters);

        $link     = SortableLink::render(['column']);
        $expected = http_build_query($parameters);

        $this->assertStringContainsString($expected, $link);
    }

    public function testQueryStringCanHoldArray(): void
    {
        $parameters = ['key' => ['part1', 'part2'], 'another-one' => 1];
        Request::replace($parameters);

        $link     = SortableLink::render(['column']);
        $expected = http_build_query($parameters);

        $this->assertStringContainsString($expected, $link);
    }

    public function testInjectTitleInQueryStrings(): void
    {
        Config::set('sortable.inject_title_as', 'title');
        SortableLink::render(['column', 'ColumnTitle']);

        $expected = ['title' => 'ColumnTitle'];

        $this->assertEquals($expected, Request::all());
    }

    public function testInjectTitleInQueryStringsIsOff(): void
    {
        Config::set('sortable.inject_title_as', null);
        SortableLink::render(['column', 'ColumnTitle']);

        $this->assertEquals([], Request::all());
    }

    public function testGeneratingAnchorAttributes(): void
    {
        $link = SortableLink::render(['column', 'ColumnTitle', ['a' => 'b'], ['c' => 'd']]);

        $icon = config('sortable.icons.default');

        $expected = '<a href="http://localhost?a=b&sort=column&direction=asc" c="d">ColumnTitle</a>' . SortableLink::getIconHtml($icon);

        $this->assertSame($expected, $link);
    }

    public function testGeneratingTitleWithoutFormattingFunction(): void
    {
        Config::set('sortable.formatting_function', null);
        $link = SortableLink::render(['column']);

        $icon = config('sortable.icons.default');

        $expected = '<a href="http://localhost?sort=column&direction=asc">column</a>' . SortableLink::getIconHtml($icon);

        $this->assertSame($expected, $link);
    }

    public function testGeneratingTitle(): void
    {
        Config::set('sortable.formatting_function', 'ucfirst');
        Config::set('sortable.format_custom_titles', true);
        $link = SortableLink::render(['column']);

        $icon = config('sortable.icons.default');

        $expected = '<a href="http://localhost?sort=column&direction=asc">Column</a>' . SortableLink::getIconHtml($icon);

        $this->assertSame($expected, $link);
    }

    public function testCustomTitle(): void
    {
        Config::set('sortable.formatting_function', 'ucfirst');
        Config::set('sortable.format_custom_titles', true);
        $link = SortableLink::render(['column', 'ColumnTitle']);

        $icon = config('sortable.icons.default');

        $expected = '<a href="http://localhost?sort=column&direction=asc">ColumnTitle</a>' . SortableLink::getIconHtml($icon);

        $this->assertSame($expected, $link);
    }

    public function testCustomTitleWithoutFormatting(): void
    {
        Config::set('sortable.formatting_function', 'ucfirst');
        Config::set('sortable.format_custom_titles', false);
        $link = SortableLink::render(['column', 'ColumnTitle']);

        $icon = config('sortable.icons.default');

        $expected = '<a href="http://localhost?sort=column&direction=asc">ColumnTitle</a>' . SortableLink::getIconHtml($icon);

        $this->assertSame($expected, $link);
    }

    public function testCustomTitleWithHTML(): void
    {
        Config::set('sortable.formatting_function', 'ucfirst');
        Config::set('sortable.format_custom_titles', true);
        $link = SortableLink::render(['column', new HtmlString('<em>ColumnTitle</em>')]);

        $icon = config('sortable.icons.default');

        $expected = '<a href="http://localhost?sort=column&direction=asc"><em>ColumnTitle</em></a>' . SortableLink::getIconHtml($icon);

        $this->assertSame($expected, $link);
    }

    public function testCustomHrefAttribute(): void
    {
        $link = SortableLink::render(['column', 'ColumnTitle', ['a' => 'b'], ['c' => 'd', 'href' => 'http://localhost/custom-path']]);

        $icon = config('sortable.icons.default');

        $expected = '<a href="http://localhost/custom-path?a=b&sort=column&direction=asc" c="d">ColumnTitle</a>' . SortableLink::getIconHtml($icon);

        $this->assertSame($expected, $link);
    }

    public function testParseParameters(): void
    {
        $parameters  = ['column'];
        $resultArray = SortableLink::parseParameters($parameters);
        $expected    = ['column', 'column', null, [], []];
        $this->assertEquals($expected, $resultArray);

        $parameters  = ['column', 'ColumnTitle'];
        $resultArray = SortableLink::parseParameters($parameters);
        $expected    = ['column', 'column', 'ColumnTitle', [], []];
        $this->assertEquals($expected, $resultArray);

        $parameters  = ['column', 'ColumnTitle', ['world' => 'matrix']];
        $resultArray = SortableLink::parseParameters($parameters);
        $expected    = ['column', 'column', 'ColumnTitle', ['world' => 'matrix'], []];
        $this->assertEquals($expected, $resultArray);

        $parameters  = ['column', 'ColumnTitle', ['world' => 'matrix'], ['white' => 'rabbit']];
        $resultArray = SortableLink::parseParameters($parameters);
        $expected    = ['column', 'column', 'ColumnTitle', ['world' => 'matrix'], ['white' => 'rabbit']];
        $this->assertEquals($expected, $resultArray);

        $parameters  = ['relation.column'];
        $resultArray = SortableLink::parseParameters($parameters);
        $expected    = ['column', 'relation.column', null, [], []];
        $this->assertEquals($expected, $resultArray);

        $parameters  = ['relation.column', 'ColumnTitle'];
        $resultArray = SortableLink::parseParameters($parameters);
        $expected    = ['column', 'relation.column', 'ColumnTitle', [], []];
        $this->assertEquals($expected, $resultArray);

        $parameters  = ['relation.column', 'ColumnTitle', ['world' => 'matrix']];
        $resultArray = SortableLink::parseParameters($parameters);
        $expected    = ['column', 'relation.column', 'ColumnTitle', ['world' => 'matrix'], []];
        $this->assertEquals($expected, $resultArray);

        $parameters  = ['relation.column', 'ColumnTitle', ['world' => 'matrix'], ['red' => 'pill']];
        $resultArray = SortableLink::parseParameters($parameters);
        $expected    = ['column', 'relation.column', 'ColumnTitle', ['world' => 'matrix'], ['red' => 'pill']];
        $this->assertEquals($expected, $resultArray);
    }

    public function testGetOneToOneSort(): void
    {
        $sortParameter = 'relation-name.column';
        $resultArray   = SortableLink::explodeSortParameter($sortParameter);
        $expected      = ['relation-name', 'column'];
        $this->assertEquals($expected, $resultArray);

        $sortParameter = 'column';
        $resultArray   = SortableLink::explodeSortParameter($sortParameter);
        $expected      = [];
        $this->assertEquals($expected, $resultArray);
    }

    public function testGetOneToOneSortThrowsException(): void
    {
        $this->expectException('\Exception');
        $this->expectExceptionCode(0);
        $sortParameter = 'relation-name..column';
        SortableLink::explodeSortParameter($sortParameter);
    }
}
