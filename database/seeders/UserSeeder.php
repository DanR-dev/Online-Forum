<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userA = new User;
        $userA->name = 'Alex';
        $userA->email = 'Alex@email.com';
        $userA->email_verified_at = now();
        $userA->password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';
        $userA->remember_token = Str::random(10);
        $userA->save();
        
        $userB = new User;
        $userB->name = 'Bob';
        $userB->email = 'Bob@email.com';
        $userB->email_verified_at = now();
        $userB->password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';
        $userB->remember_token = Str::random(10);
        $userB->save();

        User::factory(50)->create();
    }
}
