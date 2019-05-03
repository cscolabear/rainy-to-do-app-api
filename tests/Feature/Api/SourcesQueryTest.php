<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SourcesQuery extends TestCase
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
            ? sprintf('(id: %d)', $source_id)
            : '';

        return sprintf( '
            query {
                sources %s {
                    id
                    name
                    prefix_url
                }
            }
        ', $parameter_string);
    }

    /**
     * @return void
     */
    public function testQueryAllSources()
    {
        $response = $this->graphql($this->getQueryString());
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'sources' => [
                        '*' => [
                            'id',
                            'name',
                            'prefix_url',
                        ],
                    ],
                ],
            ]);
    }

    /**
     * @return void
     */
    public function testQuerySourceByID()
    {
        $response = $this->graphql($this->getQueryString(1));
        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                'sources' => [
                        [
                            'id' => 1,
                            'name' => 'niceday',
                            'prefix_url' => '//play.niceday.tw',

                        ],
                    ],
                ],
            ]);
    }

    /**
     * @return void
     */
    public function testQueryNoCategories()
    {
        $response = $this->graphql($this->getQueryString(2));
        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'sources' => [],
                ],
            ]);
    }
}
