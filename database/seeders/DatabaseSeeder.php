<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call([
            AppSettingsTableSeeder::class,
            RoleTableSeeder::class,
            UserTableSeeder::class,
            ModelHasRolesTableSeeder::class,
            PermissionTableSeeder::class,
            RoleHasPermissionsTableSeeder::class,
            ModelHasPermissionsTableSeeder::class,
            CountriesTableSeeder::class,
            StatesTableSeeder::class,
            CitiesTableSeeder::class,
            BookingStatusesTableSeeder::class,
            SettingsTableSeeder::class,
            ProviderTypesTableSeeder::class,
            HandymanTypesTableSeeder::class,
            PlansTableDataSeeder::class,
            StaticDataSeeder::class,
            CategoriesTableSeeder::class,
            ServicesTableSeeder::class,
            SlidersTableSeeder::class,
            PostRequestStatusesTableSeeder::class,
        ]);
    }
}
