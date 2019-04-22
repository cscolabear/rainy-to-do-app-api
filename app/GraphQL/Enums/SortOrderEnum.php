<?php

namespace App\GraphQL\Enums;

use Rebing\GraphQL\Support\Type as GraphQLType;

class SortOrderEnum extends GraphQLType
{
    protected $enumObject = true;

    protected $attributes = [
        'name' => 'SortOrderEnum',
        'description' => 'order BY clause',
        'values' => [
            'ASC' => 'ASC',
            'DESC' => 'DESC',
        ],
    ];
}
