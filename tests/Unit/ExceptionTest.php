<?php

namespace Tests\Unit;

use Mockery;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExceptionTest extends TestCase
{
    /**
     * @return void
     */
    public function testException()
    {
        $this->get('/404')
            ->assertStatus(404);
    }

}
