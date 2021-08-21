<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(TimesTableSeeder::class);
        $this->call(EventTypesTableSeeder::class);
        $this->call(CabinetsTableSeeder::class);
        $this->call(StatusesTableSeeder::class);
        $this->call(PermissionsTableSeeder::class);
        $this->call(MenusTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(PackagesTableSeeder::class);
        $this->call(CountriesTableSeeder::class);
        $this->call(BonusesTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(NewsTableSeeder::class);
        $this->call(BinaryTreeNodesTableSeeder::class);
        $this->call(BinaryTreeNodeInfosTableSeeder::class);
        $this->call(OssTreeNodesTableSeeder::class);
        $this->call(ProductsTableSeeder::class);
        $this->call(RequisitionTypesTableSeeder::class);
        $this->call(TagsTableSeeder::class);
        $this->call(DiscountTableSeeder::class);
        $this->call(LearnVideoTypesTableSeeder::class);
        $this->call(RewardsTableSeeder::class);
    }
}
