<?php

namespace App\GraphQL\Mutation;

use App\Models\Source;
use App\Models\Product;
use App\Models\Category;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Rebing\GraphQL\Support\Mutation;
use GraphQL\Type\Definition\ResolveInfo;
use Rebing\GraphQL\Support\SelectFields;
use Rebing\GraphQL\Support\Facades\GraphQL;

class InsertProductsMutation extends Mutation
{
    protected $attributes = [
        'name' => 'InsertProductsMutation',
        'description' => 'A mutation'
    ];

    public function type()
    {
        return GraphQL::type('InsertedType');
    }

    public function args()
    {
        return [
            'data' =>[
                'name' => 'data',
                'type' => Type::listOf(GraphQL::type('ProductInput')),
                'rules' => ['required', 'array'],
            ]
        ];
    }

    public function resolve($root, $args, SelectFields $fields, ResolveInfo $info)
    {
        $input_data = collect($args['data']);

        $source_data = $input_data->pluck('prefix_url', 'source');
        $source_data = $this->getOrCreateSource($source_data)->all();

        $category_data = $input_data->pluck('', 'category');
        $category_data = $this->getOrCreateCategory($category_data)->all();

        $input_data->transform(function ($input) use ($source_data, $category_data) {

            return [
                'source_id' => $source_data[$input['source']]['id'],
                'category_id' => $category_data[$input['category']]['id'],
                'title' => $input['title'],
                'description' => $input['description'],
                'link' => $input['link'],
                'img' => $input['img'],
                'price' => $this->getPurePrice($input['price']),
            ];
        });

        $total = ['before' => 0, 'after' => 0];
        DB::transaction(function () use ($input_data, &$total) {
            $total['before'] = Product::count();
            Product::insert($input_data->all());
            $total['after'] = Product::count();
        });

        return [
            'affected_rows' => $total['after'] - $total['before'],
        ];
    }

    private function getOrCreateSource(Collection $source_data): Collection
    {
        $source_name_list = $source_data->keys()->all();
        $exist_sources = $this->getExistSources($source_name_list);
        return $this->mappingSourceData($source_data, $exist_sources);
    }

    private function getExistSources(array $source_name_list): array
    {
        return Source::whereIn('name', array_unique($source_name_list))
            ->pluck('id', 'name')
            ->all();
    }

    private function mappingSourceData(
        Collection $source_data,
        array $exist_sources
    ): Collection
    {
        $insert_collection = collect();
        $source_data->transform(function ($item, $source_name) use ($exist_sources, $insert_collection) {
            $id = $exist_sources[ $source_name] ?? null;
            $prefix_url = $item['prefix_url'] ?? $item;

            if (!$id) {
                $insert_collection->push([
                    'name' => $source_name,
                    'prefix_url' => $prefix_url,
                ]);
            }

            return [
                'id' => $id,
                'prefix_url' => $prefix_url,
            ];
        });

        if ($insert_collection->count()) {
            Source::insert($insert_collection->all());
            $source_name_list = $insert_collection->pluck('name')->all();
            $exist_sources = $this->getExistSources($source_name_list);
            return $this->mappingSourceData($source_data, $exist_sources);
        }

        return $source_data;
    }

    private function getOrCreateCategory(Collection $category_data): Collection
    {
        $category_name_list = $category_data->keys()->all();
        $exist_categories = $this->getExistCategories($category_name_list);
        return $this->mappingCreateData($category_data, $exist_categories);
    }

    private function getExistCategories(array $category_name_list): array
    {
        return Category::whereIn('name', array_unique($category_name_list))
            ->pluck('id', 'name')
            ->all();
    }

    private function mappingCreateData(
        Collection $category_data,
        array $exist_categories
    ): Collection
    {
        $insert_collection = collect();
        $category_data->transform(function ($item, $category_name) use ($exist_categories, $insert_collection) {
            $id = $exist_categories[$category_name] ?? null;

            if (!$id) {
                $insert_collection->push([
                    'name' => $category_name,
                ]);
            }

            return [
                'id' => $id,
            ];
        });

        if ($insert_collection->count()) {
            Category::insert($insert_collection->all());
            $category_name_list = $insert_collection->pluck('name')->all();
            $exist_sources = $this->getExistSources($category_name_list);
            return $this->mappingSourceData($category_data, $exist_sources);
        }

        return $category_data;
    }

    private function getPurePrice(string $input_price): int
    {
        $price = str_replace(',', '', $input_price);
        return ($price = preg_replace('/[^\-\d]*(\-?\d*).*/', '$1', $price))
            ? (int) $price
            : 0;
    }
}