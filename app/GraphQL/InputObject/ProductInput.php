<?php

namespace App\GraphQL\InputObject;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class ProductInput extends GraphQLType
{
    protected $inputObject = true;

    protected $attributes = [
        'name' => 'ProductInput',
    ];

    public function fields()
    {
        return[
            'source' => [
                'name' => 'source', 'type' => Type::nonNull(Type::string()),
                'rules' => ['required', 'between:4,12'],
            ],
            'prefix_url' => [
                'name' => 'prefix_url', 'type' => Type::nonNull(Type::string()),
                'rules' => ['required', 'between:4,255'],
            ],
            'category' => [
                'name' => 'category', 'type' => Type::nonNull(Type::string()),
                'rules' => ['required', 'between:4,12'],
            ],
            // 'source_id' => [
            //     'name' => 'source_id', 'type' => Type::nonNull(Type::int()),
            //     'rules' => ['required'],
            // ],
            // 'category_id' => [
            //     'name' => 'category_id', 'type' => Type::nonNull(Type::int()),
            //     'rules' => ['required'],
            // ],
            'title' => [
                'name' => 'title', 'type' => Type::nonNull(Type::string()),
                'rules' => ['required', 'between:5,250'],
            ],
            'description' => [
                'name' => 'description', 'type' => Type::string(),
                'rules' =>['between:5,250'],
            ],
            'link' => [
                'name' => 'link', 'type' => Type::nonNull(Type::string()),
                'rules' => ['required', 'max:20'],
            ],
            'img' => [
                'name' => 'img', 'type' => Type::nonNull(Type::string()),
                'rules' => ['required', 'max:255'],
            ],
            'price' => [
                'name' => 'price', 'type' => Type::nonNull(Type::string()),
                'rules' => ['required'],
            ],
        ];
    }
}
