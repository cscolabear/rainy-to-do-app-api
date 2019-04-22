<?php

namespace App\GraphQL\Query;

use App\Models\Product;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use GraphQL\Type\Definition\ResolveInfo;
use Rebing\GraphQL\Support\SelectFields;
use Rebing\GraphQL\Support\Facades\GraphQL;

class ProductsQuery extends Query
{
    protected $attributes = [
        'name' => 'ProductsQuery',
        'description' => 'A query'
    ];

    public function type()
    {
        return GraphQL::paginate('ProductType');
    }

    public function args()
    {
        return [
            'id' => ['name' => 'id', 'type' => Type::int()],
            'source_id' => ['name' => 'source_id', 'type' => Type::int()],
            'category_id' => ['name' => 'category_id', 'type' => Type::int()],

            'title' => ['name' => 'title', 'type' => Type::String()],
            'price' => ['name' => 'price', 'type' => Type::int()],

            'limit' => ['name' => 'limit', 'type' => Type::int()],
            'page' => ['name' => 'page', 'type' => Type::int()],

            'orderBy' => ['name' => 'orderBy', 'type' => Type::int()],
        ];
    }

    public function resolve($root, $args, SelectFields $fields, ResolveInfo $info)
    {
        $select = $fields->getSelect();
        $with = $fields->getRelations();
        $page = $args['page'] ?? 1;
        $limit = $args['limit'] ?? 5;

        $where = function ($query) use ($args) {
            if (isset($args['id'])) {
                $query->where('id', $args['id']);
            }

            // if (isset($args['email'])) {
            //     $query->where('email', $args['email']);
            // }
        };

        return Product::with(array_keys($with))
            ->where($where)
            ->select($select)
            ->paginate($limit, ['*'], 'page', $page);
        // ->get();
    }
}
