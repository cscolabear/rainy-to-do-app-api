<?php

namespace App\GraphQL\Query;

use App\Models\Product;
use App\Services\ProductsService;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use GraphQL\Type\Definition\ResolveInfo;
use Rebing\GraphQL\Support\SelectFields;
use Rebing\GraphQL\Support\Facades\GraphQL;

class ProductsQuery extends Query
{
    private $products_service;

    public function __construct(ProductsService $service)
    {
        parent::__construct();

        $this->products_service = $service;
    }

    protected $attributes = [
        'name' => 'ProductsQuery',
        'description' => '取得 product 列表'
    ];

    public function type()
    {
        return GraphQL::paginate('ProductType');
    }

    public function args()
    {
        return [
            // field
            'id' => ['name' => 'id', 'type' => Type::int()],
            'source_id' => ['name' => 'source_id', 'type' => Type::int()],
            'category_id' => ['name' => 'category_id', 'type' => Type::int()],

            'title' => ['name' => 'title', 'type' => Type::String()],
            'price' => ['name' => 'price', 'type' => Type::int()],
            'priceRange' => ['name' => 'priceRange', 'type' => GraphQL::type('PriceRangeByClauseInput')],

            // pagination
            'count' => ['name' => 'count', 'type' => Type::int(),
                'description' => '每頁筆數'],
            'page' => ['name' => 'page', 'type' => Type::int()],

            // filter
            'source_id' => ['name' => 'source_id', 'type' => Type::int()],
            'category_ids' => ['name' => 'category_ids', 'type' => Type::listOf(Type::int())],
            'orderBy' => ['name' => 'orderBy', 'type' => GraphQL::type('OrderByClauseInput')],
        ];
    }

    public function resolve($root, $args, SelectFields $fields, ResolveInfo $info)
    {
        $select_fields = $fields->getSelect();
        $with_relations = $fields->getRelations();

        return $this->products_service
            ->resolve($args, $select_fields, $with_relations);
    }
}
