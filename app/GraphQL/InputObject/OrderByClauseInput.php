<?php

namespace App\GraphQL\InputObject;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class OrderByClauseInput extends GraphQLType
{
    protected $inputObject = true;

    protected $attributes = [
        'name' => 'OrderByClause',
        'description' => '指定排序規則'
    ];

    public function fields()
    {
        return [
            'field' => [
                'name' => 'field',
                'description' => '指定排序欄位, e.g. "price"',
                'type' => Type::string(),

                // You can define Laravel Validation here
                'rules' => ['required']
            ],
            'order' => [
                'name' => 'order',
                'description' => 'order BY clause ASC or DESC',
                'type' => GraphQL::type('SortOrderEnum'),

                // You can define Laravel Validation here
                'rules' => ['in:ASC,DESC']
            ],
        ];
    }
}
