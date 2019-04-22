<?php

use App\Models\Source;
use Illuminate\Database\Seeder;

class SourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (Source::count() > 0) {
            return;
        }

        Source::unguard();
        Source::insert([
            ['id' => 1, 'name' => 'niceday', 'prefix_url' => '//play.niceday.tw'],
        ]);
        Source::reguard();
    }
}
