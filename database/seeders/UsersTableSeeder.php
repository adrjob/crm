<?php

namespace Database\Seeders;
use App\Models\User;
use App\Models\Utility;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        // company

        $company = User::create(
            [
                'name' => 'company',
                'email' => 'company@example.com',
                'password' => Hash::make('1234'),
                'type' => 'company',
                'lang' => 'en',
                'avatar' => '',
                'plan' => 1,
                'created_by' => 0,
            ]
        );

        $company->defaultEmail();
        $company->userDefaultData();
        Utility::add_landing_page_data();
        Utility::chartOfAccountTypeData($company->id);
        Utility::chartOfAccountData($company);


    }
}
