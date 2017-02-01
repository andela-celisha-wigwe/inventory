<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';

    public function setUp() {
        parent::setUP();

        \Artisan::call('migrate');

        $this->truncateAll();

        $this->baseAPIUrl = $this->baseUrl . "/api/v1";
    }

    protected function truncateAll() {
        collect(['inventories'])->each(function ($table) {
            \DB::table($table)->truncate();
        });
    }
}
