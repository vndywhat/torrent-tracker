<?php

namespace Tests;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use DatabaseMigrations;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        if (!file_exists('.env.testing')) {
            die('You should have a .env.testing file!');
        }

        parent::__construct($name, $data, $dataName);
    }

    protected function setUp(): void
    {
        parent::setUp();

        Artisan::call('passport:install');
    }
}
