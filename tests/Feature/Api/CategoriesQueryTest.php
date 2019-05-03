<?php

namespace Tests\Feature\Api;

use CategorySeeder;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoriesQueryTest extends TestCase
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

    public function getQueryString($source_id = null): string
    {
        $parameter_string = $source_id
            ? sprintf('(source_id: %d)', $source_id)
            : '';

        return sprintf('
            query {
                categories %s{
                    id
                    name
                }
            }
        ', $parameter_string);
    }

    public function queryStringProvider()
    {
        $response_structure =[
            'data' => [
                'categories' => [
                    '*' => [
                        'id',
                        'name',
                    ],
                ],
            ],
        ];

        return [

            // all categories
            [
                $this->getQueryString(),
                $response_structure,
                ['data' => ['categories' => CategorySeeder::DATA]]
            ],

            // categories of source_id = 1
            [
                $this->getQueryString($source_id = 1),
                $response_structure,
                ['data' => ['categories' => [
                    [
                        'id' => 1,
                        'name' => '愛上戶外'
                    ],
                    [
                        'id' => 4,
                        'name' => '藝文手作'
                    ]
                ]]]
            ],

            // empty categories of source id = 2
            [
                $this->getQueryString(2),
                $response_structure,
                ['data' => ['categories' => []]]
            ]
        ];
    }

    /**
     * @dataProvider queryStringProvider
     * @return void
     */
    public function testQueryCategories(
        $query_string,
        $response_structure,
        $response_json
    )
    {
        $response = $this->graphql($query_string);
        $response->assertStatus(200)
            ->assertJsonStructure($response_structure)
            ->assertJson($response_json);
    }

}
