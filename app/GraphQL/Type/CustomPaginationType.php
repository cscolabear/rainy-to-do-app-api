<?php

namespace App\GraphQL\Type;

use Rebing\GraphQL\Support\PaginationType;
use Illuminate\Pagination\LengthAwarePaginator;
use GraphQL\Type\Definition\Type as GraphQLType;

class CustomPaginationType extends PaginationType
{

    protected function getPaginationFields($typeName)
    {
        return array_merge(
            parent::getPaginationFields($typeName),
            [
                // Add in the 'last page' value from the Laravel Paginator
                'last_page' => [
                    'type' => GraphQLType::nonNull(GraphQLType::int()),
                    'description' => 'Last page of the result set',
                    'resolve' => function (LengthAwarePaginator $data) {
                        return $data->lastPage();
                    },
                    'selectable' => false,
                ],
            ]
        );
    }
}
