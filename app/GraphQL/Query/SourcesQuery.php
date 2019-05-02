<?php

namespace App\GraphQL\Query;

use App\Models\Source;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use GraphQL\Type\Definition\ResolveInfo;
use Rebing\GraphQL\Support\SelectFields;
use Rebing\GraphQL\Support\Facades\GraphQL;

class SourcesQuery extends Query
{
    protected $attributes = [
        'name' => 'SourcesQuery',
        'description' => '取得資料來源'
    ];

    public function type()
    {
        return Type::listOf(GraphQL::type('SourceType'));
    }

    public function args()
    {
        return [
            // field
            'id' => ['name' => 'id', 'type' => Type::int()],
        ];
    }

    public function resolve($root, $args, SelectFields $fields, ResolveInfo $info)
    {
        $select = $fields->getSelect();
        $with = $fields->getRelations();

        return Source::select($select)
            ->with(array_keys($with))
            ->when(isset($args['id']), function ($query) use ($args) {
                return $query->where('id', $args['id']);
            })
            ->get();
    }
}
