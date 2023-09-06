<?php

namespace Lorisleiva\LaravelSearchString\Tests;

use Lorisleiva\LaravelSearchString\Options\Rule;

class RuleTest extends TestCase
{
    /** @test */
    public function it_keep_rules_that_are_defined_has_regex_patterns()
    {
        $this->assertEquals("[/^foobar$/]", $this->parseRule('/^foobar$/'));
        $this->assertEquals("[~^foobar$~]", $this->parseRule('~^foobar$~'));
        $this->assertEquals("[/^(published|live)$/]", $this->parseRule('/^(published|live)$/'));
    }

    /** @test */
    public function it_wraps_non_regex_patterns_into_regex_delimiters()
    {
        $this->assertEquals("[/^foobar$/]", $this->parseRule('foobar'));
    }

    /** @test */
    public function it_preg_quote_non_regex_patterns()
    {
        $this->assertEquals('[/^\/ke\(y$/]', $this->parseRule('/ke(y'));
        $this->assertEquals('[/^\^\\\\d\$$/]', $this->parseRule('^\d$'));
        $this->assertEquals('[/^\.\*\\\w\(value$/]', $this->parseRule('.*\w(value'));
    }

    /** @test */
    public function it_provides_fallback_values_when_patterns_are_missing()
    {
        $this->assertEquals('[/^fallback_column$/]', $this->parseRule(null, 'fallback_column'));
        $this->assertEquals('[/^fallback_column$/]', $this->parseRule([], 'fallback_column'));
    }

    /** @test */
    public function it_parses_string_rules_as_the_key_of_the_rule()
    {
        $this->assertEquals("[/^foobar$/]", $this->parseRule('foobar'));
        $this->assertEquals("[/^\w{1,10}\?/]", $this->parseRule('/^\w{1,10}\?/'));
    }

    public function parseRule($rule, $column = 'column')
    {
        return (string) new class($column, $rule) extends Rule {};
    }
}
