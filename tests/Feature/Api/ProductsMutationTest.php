<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Category;

class ProductsMutationTest extends TestCase
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
     * insert 2 product and 1 new category
     * @return void
     */
    public function testBulkInsertProducts()
    {
        $new_category = 'outdoor'; // not exist at database
        $this->assertEquals(
            false,
            Category::where('name', $new_category)->exists()
        );

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

        $this->assertEquals(
            true,
            Category::where('name', $new_category)->exists()
        );

        $response->assertStatus(200)
            ->assertJsonFragment([
                'data' => [
                    'insertProducts' => [
                        'affected_rows' => 2,
                    ],
                ]
            ]);
    }
}
