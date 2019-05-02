<?php

namespace App\Services;

use App\Entities\QueryArgumentEntity;
use App\Repositories\ProductsRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductsService
{
    private $products_repository;

    public function __construct(ProductsRepository $repo)
    {
        $this->products_repository = $repo;
    }

    public function resolve(
        array $args,
        array $select_fields,
        array $with_relations

    ): LengthAwarePaginator
    {
        return $this->products_repository
            ->resolve(
                $this->defaultArguments($args),
                $select_fields,
                $with_relations
            );
    }

    private function defaultArguments(array $args): array
    {
        $args['page'] = $args['page'] ?? QueryArgumentEntity::DEFAULT_PAGE;
        $args['count'] = $args['count'] ?? QueryArgumentEntity::DEFAULT_COUNT;

        return $args;
    }
}
