<?php

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\HtmlString;
use Kyslik\ColumnSortable\SortableLink;

/**
 * Class SortableLinkTest
 */
class SortableLinkTest extends \Orchestra\Testbench\TestCase
{

    public function testQueryStringParameterWithBooleanStaysInLink()
    {
        $parameters = ['key' => 0, 'another-key' => null, 'another-one' => 1];
        Request::replace($parameters);

        $link     = SortableLink::render(['column']);
        $expected = http_build_query($parameters);

        $this->assertStringContainsString($expected, $link);
    }


    public function testQueryStringCanHoldArray()
    {
        $parameters = ['key' => ['part1', 'part2'], 'another-one' => 1];
        Request::replace($parameters);

        $link     = SortableLink::render(['column']);
        $expected = http_build_query($parameters);

        $this->assertStringContainsString($expected, $link);
    }


    public function testInjectTitleInQueryStrings()
    {
        Config::set('columnsortable.inject_title_as', 'title');
        SortableLink::render(['column', 'ColumnTitle']);

        $expected = ['title' => 'ColumnTitle'];
        $this->assertEquals($expected, Request::all());
    }


    public function testInjectTitleInQueryStringsIsOff()
    {
        Config::set('columnsortable.inject_title_as', null);
        SortableLink::render(['column', 'ColumnTitle']);

        $this->assertEquals([], Request::all());
    }

    public function testGeneratingAnchorAttributes()
    {
        $link = SortableLink::render(['column', 'ColumnTitle', ['a' => 'b'], ['c' => 'd']]);

        $this->assertSame('<a href="http://localhost?a=b&sort=column&direction=asc" c="d">ColumnTitle</a><i class=""></i>', $link);
    }

    public function testGeneratingTitleWithoutFormattingFunction()
    {
        Config::set('columnsortable.formatting_function', null);
        $link = SortableLink::render(['column']);

        $this->assertSame('<a href="http://localhost?sort=column&direction=asc">column</a><i class=""></i>', $link);
    }

    public function testGeneratingTitle()
    {
        Config::set('columnsortable.formatting_function', 'ucfirst');
        Config::set('columnsortable.format_custom_titles', true);
        $link = SortableLink::render(['column']);

        $this->assertSame('<a href="http://localhost?sort=column&direction=asc">Column</a><i class=""></i>', $link);
    }


    public function testCustomTitle()
    {
        Config::set('columnsortable.formatting_function', 'ucfirst');
        Config::set('columnsortable.format_custom_titles', true);
        $link = SortableLink::render(['column', 'columnTitle']);

        $this->assertSame('<a href="http://localhost?sort=column&direction=asc">ColumnTitle</a><i class=""></i>', $link);
    }


    public function testCustomTitleWithoutFormatting()
    {
        Config::set('columnsortable.formatting_function', 'ucfirst');
        Config::set('columnsortable.format_custom_titles', false);
        $link = SortableLink::render(['column', 'columnTitle']);

        $this->assertSame('<a href="http://localhost?sort=column&direction=asc">columnTitle</a><i class=""></i>', $link);
    }


    public function testCustomTitleWithHTML()
    {
        Config::set('columnsortable.formatting_function', 'ucfirst');
        Config::set('columnsortable.format_custom_titles', true);
        $link = SortableLink::render(['column', new HtmlString('<em>columnTitle</em>')]);

        $this->assertSame('<a href="http://localhost?sort=column&direction=asc"><em>columnTitle</em></a><i class=""></i>', $link);
    }


    public function testParseParameters()
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


    public function testGetOneToOneSort()
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


    public function testGetOneToOneSortThrowsException()
    {
        $this->expectException('\Exception');
        $this->expectExceptionCode(0);
        $sortParameter = 'relation-name..column';
        SortableLink::explodeSortParameter($sortParameter);
    }
}
