<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductsQueryTest extends TestCase
{
    public $count = 3;

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

    public function getQueryString(): string
    {
        return sprintf('
            query {
                products (
                    count: %d
                    page: 1
                    source_id: 1
                    category_ids: [
                        2
                        3
                        4
                    ]
                    orderBy: {
                        field: "price"
                        order: DESC
                    }
                ) {
                    data {
                        id
                        source_id
                        category_id
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
        ', $this->count);
    }

    /**
     * test product query and pagination
     * @return void
     */
    public function testProducts()
    {
        $response = $this->graphql($this->getQueryString());
        $response->assertStatus(200)
            ->assertJsonCount($this->count, 'data.products.data.*')
            ->assertJsonFragment(['current_page' => 1])
            ->assertJsonFragment(['per_page' => $this->count])
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
            ])->assertJson([
                'data' => [
                    'products' => [
                        'data' => [
                            [
                                'source_id' => 1,
                                'category_id' => 4,
                            ],
                        ],
                    ],
                ],
            ]);
    }
}
