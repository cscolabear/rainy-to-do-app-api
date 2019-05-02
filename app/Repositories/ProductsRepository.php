<?php

namespace App\Repositories;

use App\Models\Source;
use App\Models\Product;
use App\Models\Category;
use App\Entities\QueryArgumentEntity;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductsRepository
{
    public function resolve(
        array $args,
        array $select_fields,
        array $with_relations
    ): LengthAwarePaginator
    {

        $where = $this->resolveClosureWhere($args);

        return Product::with(array_keys($with_relations))
            ->where($where)
            ->select($select_fields)
            ->when(
                !empty($args['orderBy']),
                $this->resolveClosurePriceOrder($args)
            )

            // default order rule
            ->orderBy('created_at', 'DESC')
            ->orderBy('id', 'DESC')
            ->paginate($args['count'], ['*'], 'page', $args['page']);
    }

    private function resolveClosureWhere(array $args)
    {
        return function ($query) use ($args) {
            if (isset($args['id'])) {
                $query->where('id', $args['id']);
            }

            if (isset($args['source_id'])) {
                $query->where('source_id', $args['source_id']);
            }

            if (isset($args['category_ids'])) {
                $query->whereIn('category_id', $args['category_ids']);
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
    }

    private function resolveClosurePriceOrder(array $args)
    {
        return function ($query) use ($args) {
            return $query->orderBy(
                $args['orderBy']['field'],
                $args['orderBy']['order'] ?? QueryArgumentEntity::DEFAULT_ORDER
            );
        };
    }

    public function getExistSources(array $source_name_list): array
    {
        return Source::whereIn('name', array_unique($source_name_list))
            ->pluck('id', 'name')
            ->all();
    }

    public function getExistCategories(array $category_name_list): array
    {
        return Category::whereIn('name', array_unique($category_name_list))
            ->pluck('id', 'name')
            ->all();
    }
}
