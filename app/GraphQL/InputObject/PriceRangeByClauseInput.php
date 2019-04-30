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
        'description' => '價格區間, >= [gte], [lte] <= ',
    ];

    public function fields()
    {
        return [
            'gte' => [
                'name' => 'gte',
                'description' => '大於等於',
                'type' => Type::int(),
                'rules' => ['integer']
            ],
            'lte' => [
                'name' => 'lte',
                'description' => '小於等於',
                'type' => Type::int(),
                'rules' => ['integer']
            ],
        ];
    }
}
