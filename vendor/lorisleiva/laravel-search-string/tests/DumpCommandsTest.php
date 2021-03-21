<?php

namespace Lorisleiva\LaravelSearchString\Tests;

use Illuminate\Support\Facades\Artisan;

class DumpCommandsTest extends TestCase
{
    /** @test */
    public function it_dumps_the_ast()
    {
        $this->assertEquals(
            <<<EOL
            AND
            >   name = A
            >   price > 10
            EOL,
            $this->ast('name: A price > 10')
        );

        $this->assertEquals(
            <<<EOL
            EXISTS [comments]
            >   EXISTS [author]
            >   >   name = John
            EOL,
            $this->ast('comments.author.name = John')
        );
    }

    /** @test */
    public function it_dumps_the_sql_query()
    {
        $this->assertEquals(
            'select * from `products` where (`products`.`name` = A and `products`.`price` > 10)',
            $this->sql('name: A price > 10')
        );

        $this->assertEquals(
            'select * from `products` where exists (select * from `comments` where `products`.`id` = `comments`.`product_id` and exists (select * from `users` where `comments`.`user_id` = `users`.`id` and `users`.`name` = John))',
            $this->sql('comments.author.name = John')
        );

        $this->assertEquals(
            'select * from `products` where ((`products`.`name` like %A% or `products`.`description` like %A%) or (`products`.`name` like %B% or `products`.`description` like %B%))',
            $this->sql('A or B')
        );
    }

    public function ast(string $query)
    {
        return $this->query('ast', $query);
    }

    public function sql(string $query)
    {
        return $this->query('sql', $query);
    }

    public function result(string $query)
    {
        return $this->query('get', $query);
    }

    public function query(string $type, string $query)
    {
        Artisan::call(sprintf('search-string:%s /Lorisleiva/LaravelSearchString/Tests/Stubs/Product "%s"', $type, $query));

        return trim(Artisan::output(), "\n");
    }
}
