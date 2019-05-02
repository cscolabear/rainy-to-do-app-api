<?php

namespace Tests\Unit;

use Mockery;
use Tests\TestCase;
use Tests\Feature\Api\ProductsQueryTest;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductsServiceTest extends TestCase
{
    public $service_mock;

    public function setUp()
    {
        parent::setUp();

        $this->initDatabase();

        $this->service_mock = Mockery::mock('App\Services\ProductsService');
        $this->app->instance('App\Services\ProductsService', $this->service_mock);
    }

    public function tearDown()
    {
        $this->resetDatabase();
        Mockery::close();
    }

    /**
     * @return void
     */
    public function testProductsServiceResolve()
    {
        $this->service_mock
            ->shouldReceive('resolve')
            ->once();

        $query_string = (new ProductsQueryTest)->getQueryString();

        $response = $this->graphql($query_string);
        $response->assertStatus(200);
    }

}
