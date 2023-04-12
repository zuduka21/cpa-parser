<?php

use Illuminate\Database\Seeder;
use App\Role;
use App\User;
use App\Partner;

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
        Partner::insert([[
            'name' => 'Income Access',
            'indication' => 'income_access',
            'key' => '',
            'login' => '',
            'password' => ''],
            [
            'name' => 'My affiliates',
            'indication' => 'my_affiliates',
            'key' => '',
            'login' => '',
            'password' => ''

            ]
        ]);

        Role::insert([
            [
                'name' => 'admin',
            ], [
                'name' => 'user',
            ]
        ]);

        $role = Role::find(1);

        $role->users()->saveMany([
            new User([
                'name' => 'admin',
                'email' => 'admin' . '@gmail.com',
                'allow_export' => true,
                'password' => bcrypt('admin')
            ]),
        ]);
    }
}
