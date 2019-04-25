<?php

namespace App\Repositories;

use App\Models\Source;
use App\Models\Category;

class ProductsRepository
{
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
