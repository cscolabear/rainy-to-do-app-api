<?php

namespace App\Services;

use App\Models\Source;
use App\Models\Category;
use Illuminate\Support\Collection;
use App\Repositories\ProductsRepository;

class InsertProductsService
{
    public function bulkInsert(Collection $input_data): Collection
    {
        $source_data = $input_data->pluck('prefix_url', 'source');
        $source_data = $this->getOrCreateSource($source_data)->all();

        $category_data = $input_data->pluck('', 'category');
        $category_data = $this->getOrCreateCategory($category_data)->all();

        return $input_data->transform(function ($input) use ($source_data, $category_data) {

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
    }

    private function getOrCreateSource(Collection $source_data): Collection
    {
        $source_name_list = $source_data->keys()->all();
        $exist_sources = (new ProductsRepository)->getExistSources($source_name_list);
        return $this->mappingSourceData($source_data, $exist_sources);
    }

    private function mappingSourceData(
        Collection $source_data,
        array $exist_sources
    ): Collection
    {
        $insert_collection = collect();
        $source_data->transform(function ($item, $source_name) use ($exist_sources, $insert_collection) {
            $id = $item['id'] ?? $exist_sources[$source_name] ?? null;
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
            $exist_sources = (new ProductsRepository)->getExistSources($source_name_list);
            return $this->mappingSourceData($source_data, $exist_sources);
        }

        return $source_data;
    }

    private function getOrCreateCategory(Collection $category_data): Collection
    {
        $category_name_list = $category_data->keys()->all();
        $exist_categories = (new ProductsRepository)->getExistCategories($category_name_list);
        return $this->mappingCreateData($category_data, $exist_categories);
    }

    private function mappingCreateData(
        Collection $category_data,
        array $exist_categories
    ): Collection
    {
        $insert_collection = collect();
        $category_data->transform(function ($item, $category_name) use ($exist_categories, $insert_collection) {
            $id = $item['id'] ?? $exist_categories[$category_name] ?? null;

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
            $exist_sources = (new ProductsRepository)->getExistCategories($category_name_list);
            return $this->mappingCreateData($category_data, $exist_sources);
        }

        return $category_data;
    }

    private function getPurePrice(string $input_price): int
    {
        $price = str_replace(',', '', $input_price);
        return ($price = preg_replace('/[^\-\d]*(\-?\d*).*/', '$1', $price))
            ? (int)$price
            : 0;
    }
}
