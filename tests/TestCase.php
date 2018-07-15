<?php

namespace Tests;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, DatabaseMigrations;

	protected function setUp()
	{
		parent::setUp();
		Artisan::call('db:seed', ['--class' => '\Database\Seeds\TestCompany', '--force' => true]);
		Artisan::call('company:seed',['company' => 1]);
	}
}
