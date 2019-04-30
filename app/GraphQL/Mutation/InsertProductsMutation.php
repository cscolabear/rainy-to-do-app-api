<?php

namespace App\GraphQL\Mutation;

use App\Models\Product;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\DB;
use Rebing\GraphQL\Support\Mutation;
use App\Services\InsertProductsService;
use GraphQL\Type\Definition\ResolveInfo;
use Rebing\GraphQL\Support\SelectFields;
use Rebing\GraphQL\Support\Facades\GraphQL;

class InsertProductsMutation extends Mutation
{
    protected $insert_products_service;

    protected $attributes = [
        'name' => 'InsertProductsMutation',
        'description' => '批次寫入 product'
    ];

    public function __construct(InsertProductsService $service)
    {
        parent::__construct();

        $this->insert_products_service = $service;
    }

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
        $input_collection = collect($args['data']);
        $input_data = $this->insert_products_service->bulkInsert($input_collection);

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
}
