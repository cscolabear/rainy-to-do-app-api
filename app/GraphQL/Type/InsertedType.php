<?php

namespace App\GraphQL\Type;

use App\Models\Category;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class InsertedType extends GraphQLType
{
    protected $attributes = [
        'name' => 'InsertedType',
        'description' => '批次插入回傳欄位',
    ];

    public function fields()
    {
        return [
            'affected_rows' => [
                'type' => Type::nonNull(Type::int()),
                'description' => '成功寫入筆數',
            ],
        ];
    }
}
