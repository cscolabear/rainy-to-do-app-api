<?php

namespace App\GraphQL\Type;

use App\Models\Product;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class ProductType extends GraphQLType
{
    protected $attributes = [
        'name' => 'ProductType',
        'description' => 'A type',
        'model' => Product::class,
    ];

    public function fields()
    {
        return[
            'id' => [
                'type' => Type::nonNull(Type::int()),
            ],
            'source_id' => [
                'type' => Type::nonNull(Type::int()),
            ],
            'category_id' => [
                'type' => Type::nonNull(Type::int()),
            ],


            'title' => [
                'type' => Type::nonNull(Type::string()),
            ],
            'description' => [
                'type' => Type::nonNull(Type::string()),
            ],
            'link' => [
                'type' => Type::nonNull(Type::string()),
            ],
            'img' => [
                'type' => Type::nonNull(Type::string()),
            ],

            'price' => [
                'type' => Type::nonNull(Type::int()),
            ],

            'created_at' => [
                'type' => Type::nonNull(Type::String()),
            ],

            'source' => [
                'type' => GraphQL::type('SourceType'),
            ],
            'category' => [
                'type' => GraphQL::type('CategoryType'),
            ],

            'order' => [
                'type' => GraphQL::type('SortOrderEnum'),
            ]
        ];
    }
}
