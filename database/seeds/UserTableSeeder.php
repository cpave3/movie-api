<?php

use Illuminate\Database\Seeder;

use App\User;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('users')->delete();
        $user = User::create([
          'id' => 1,
          'name' => "Administrator",
          'email' => "admin@admin.com",
          'password' => Hash::make('secret'),
        ]);
        $user->createApiKey();

    }
}
