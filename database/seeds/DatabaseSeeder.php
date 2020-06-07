<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Teste',
            'email' => 'teste@email.com',
            'password' => Hash::make('password'),
        ]);
        // $this->call(UserSeeder::class);
        factory(User::class, 100)->create();
    }
}
