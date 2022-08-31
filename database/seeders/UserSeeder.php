<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
            DB::table('users')->insert([
                'name' => 'RENAN LUIZ MACEDO DE SOUZA',
                'email' => 'renanluizmacedo@hotmail.com',
                'password' => Hash::make('renan123'),
                'role_id' => 1,
            ]);
            
            DB::table('users')->insert([
                'name' => 'KAUAN MATHEUS MACEDO DE SOUZA',
                'email' => 'kauanmatheus@hotmail.com',
                'password' => Hash::make('kauan123'),
                'role_id' => 2,
            ]);

            DB::table('users')->insert([
                'name' => 'RAFAEL MACEDO DE SOUZA',
                'email' => 'rafaelmacedo@hotmail.com',
                'password' => Hash::make('rafael123'),
                'role_id' => 3,
            ]);
    }
}
