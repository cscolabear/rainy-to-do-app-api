<?php

namespace Tests\Feature;

use Mockery;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InsertProductsServiceTest extends TestCase
{
    public $service_mock;

    public function setUp()
    {
        parent::setUp();

        $this->initDatabase();

        $this->service_mock = Mockery::mock('App\Services\InsertProductsService');
        $this->app->instance('App\Services\InsertProductsService', $this->service_mock);
    }

    public function tearDown()
    {
        $this->resetDatabase();
        Mockery::close();
    }

    /**
     * @return void
     */
    public function testProductsServiceBulkInsert()
    {
        $this->service_mock
            ->shouldReceive('bulkInsert')
            ->once();

        $new_category = 'outdoor'; // not exist at database

        $query = sprintf('
              mutation {
                insertProducts (
                    data: [
                        {
                            source: "niceday "
                            prefix_url: "//play.niceday.tw"
                            category: "愛上戶外"
                            title: "title 123"
                            description: "desc 123"
                            link: "cola.io/1234"
                            img: "//cola.io/abc.jpg"
                            price: "$ 1,234 起"
                        },
                        {
                            source: "niceday"
                            prefix_url: "//play.niceday.tw"
                            category: "%s"
                            title: "title 987"
                            description: "desc 987"
                            link: "cola.io/9876"
                            img: "//cola.io/def.jpg"
                            price: "9000"
                        }
                    ]
                ) {
                    affected_rows
                }
            }
        ', $new_category);

        $response = $this->graphql($query);
        $response->assertStatus(200);
    }

}
