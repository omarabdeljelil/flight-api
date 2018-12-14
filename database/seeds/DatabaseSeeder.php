<?php

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
        // $this->call(UsersTableSeeder::class);
        factory(App\Airport::class,5)->create();
        factory(App\Flight::class,10)->create()->each(function ($flight){
            factory(App\Customer::class,100)->make()->each(function ($customer) use($flight){
               $flight->passengers()->save($customer);
            });
        });
    }
}
