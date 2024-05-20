<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;
use App\Models\Company;
use App\Models\User;
use App\Models\File;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create countries
        $countries = Country::factory(5)->create();

        // Create companies and associate them with countries
        $countries->each(function ($country) {
            $companies = Company::factory(3)->create(['country_id' => $country->id]);

            // Create users and associate them with companies
            $companies->each(function ($company) {
                $users = User::factory(5)->create();
                $company->users()->attach($users, ['connected_at' => now()]);
            });
        });

        // Create some files
        File::factory(10)->create();
    }
}
