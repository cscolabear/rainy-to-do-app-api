<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductsQueryTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->initDatabase();
    }

    public function tearDown()
    {
        $this->resetDatabase();
    }

    /**
     * @return void
     */
    public function testBase()
    {
        $response = $this->graphql('');
        $response->assertStatus(200);
    }

    /**
     * test product query and pagination
     * @return void
     */
    public function testProducts()
    {
        $count = 2;

        $query = sprintf( '
            query {
                products (
                    count: %d
                    page: 2
                    orderBy: {
                        field: "price"
                        order: DESC
                    }
                ) {
                    data {
                        id
                        source_id
                        title
                        description
                        price
                        link
                        img
                        created_at
                    }
                    total
                    per_page
                    current_page
                    last_page
                }
            }
        ', $count);

        $response = $this->graphql($query);
        $response->assertStatus(200)
            ->assertJsonCount($count, 'data.products.data.*')
            ->assertJsonFragment(['last_page' => round(5/$count)])
            ->assertJsonFragment(['total' => 5]) // from seed
            ->assertJsonStructure([
                'data' => [
                    'products' => [
                        'data' => [
                            '*' => [
                                'id',
                                'source_id',
                                'title',
                                'description',
                                'price',
                                'link',
                                'img',
                                'created_at',
                            ],
                        ],
                        'total',
                        'per_page',
                        'current_page',
                        'last_page',
                    ],
                ],
            ]);
    }
}
