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
            'priceRange' => ['name' => 'priceRange', 'type' => GraphQL::type('PriceRangeByClauseInput')],

            'count' => ['name' => 'count', 'type' => Type::int()],
            'page' => ['name' => 'page', 'type' => Type::int()],

            'orderBy' => ['name' => 'orderBy', 'type' => GraphQL::type('OrderByClauseInput')],
        ];
    }

    public function resolve($root, $args, SelectFields $fields, ResolveInfo $info)
    {
        $select = $fields->getSelect();
        $with = $fields->getRelations();
        $page = $args['page'] ?? 1;
        $count = $args['count'] ?? 5;
        $order_args = $args['orderBy'] ?? [];

        $where = function ($query) use ($args) {
            if (isset($args['id'])) {
                $query->where('id', $args['id']);
            }

            if (isset($args['priceRange'])) {
                if (isset($args['priceRange']['gte']) && isset($args['priceRange']['lte'])) {
                    $query->whereBetween('price', [$args['priceRange']['gte'], $args['priceRange']['lte']]);
                } elseif (isset($args['priceRange']['gte'])) {
                    $query->where('price', '>=', $args['priceRange']['gte']);
                } elseif (isset($args['priceRange']['lte'])) {
                    $query->where('price', '<=', $args['priceRange']['lte']);
                }
            }
        };

        return Product::with(array_keys($with))
            ->where($where)
            ->select($select)
            ->when(! empty($order_args), function($query) use ($order_args) {
                return $query->orderBy(
                    $order_args['field'],
                    $order_args['order'] ?? 'ASC'
                );
            })
            ->paginate($count, ['*'], 'page', $page);
    }
}
