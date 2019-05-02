<?php

namespace App\GraphQL\Query;

use App\Models\Product;
use App\Models\Category;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use GraphQL\Type\Definition\ResolveInfo;
use Rebing\GraphQL\Support\SelectFields;
use Rebing\GraphQL\Support\Facades\GraphQL;

class CategoriesQuery extends Query
{
    protected $attributes = [
        'name' => 'CategoriesQuery',
        'description' => '取得分類列表'
    ];

    public function type()
    {
        return Type::listOf(GraphQL::type('CategoryType'));
    }

    public function args()
    {
        return [
            // field
            'id' => ['name' => 'id', 'type' => Type::int()],

            'source_id' => ['name' => 'source_id', 'type' => Type::int()],
        ];
    }

    public function resolve($root, $args, SelectFields $fields, ResolveInfo $info)
    {
        $select = $fields->getSelect();
        // $with = $fields->getRelations();

        $args['source_id'] = $args['source_id'] ?? null;
        $category_ids = null;

        if (isset($args['source_id'])) {
            $category_ids = Product::where('source_id', $args['source_id'])
                ->groupBy('category_id')
                ->select('category_id')
                ->pluck('category_id')
                ->toArray();
        }

        return Category::select($select)
            ->when($args['source_id'] || $category_ids, function ($query) use ($category_ids) {
                return $query->whereIn('id', $category_ids);
            })
            ->select($select)
            ->get();
    }
}
