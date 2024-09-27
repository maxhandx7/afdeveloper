<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Alan Ferney',
            'email' => 'alancarabali@gmail.com',
            'image' => 'system/profile.png',
            'password'=>'$2y$10$liZl2fBn4wBbJEAzsUobLuArOrEgurYORgMMPIw8J2yUU/BVSs/4y',
        ]);
    }
}
