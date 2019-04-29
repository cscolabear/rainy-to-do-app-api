<?php

namespace App\GraphQL\InputObject;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class PriceRangeByClauseInput extends GraphQLType
{
    protected $inputObject = true;

    protected $attributes = [
        'name' => 'PriceRangeByClauseInput',
    ];

    public function fields()
    {
        return [
            'gte' => [
                'name' => 'gte',
                'type' => Type::int(),
                'rules' => ['integer']
            ],
            'lte' => [
                'name' => 'lte',
                'type' => Type::int(),
                'rules' => ['integer']
            ],
        ];
    }
}
