<?php

namespace App\GraphQL\Type;

use App\Models\Source;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class SourceType extends GraphQLType
{
    protected $attributes = [
        'name' => 'SourceType',
        'description' => '資料來源欄位',
        'model' => Source::class,
    ];

    public function fields()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
            ],
            'name' => [
                'type' => Type::nonNull(Type::string()),
            ],
            'prefix_url' => [
                'type' => Type::nonNull(Type::string()),
            ],
        ];
    }
}
