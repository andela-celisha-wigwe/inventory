<?php

use Illuminate\Database\Seeder;

class InventoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	\DB::table('inventories')->truncate();
    	factory(\App\Inventory::class, 50)->create();
    }
}
