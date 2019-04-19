<?php

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (Category::count() > 0) {
            return;
        }

        Category::unguard();
        Category::insert([
            ['id' => 1, 'name' => '愛上戶外'],
            ['id' => 2, 'name' => '玩樂廚房'],
            ['id' => 3, 'name' => '紓壓生活'],
            ['id' => 4, 'name' => '藝文手作'],
        ]);
        Category::reguard();
    }
}
