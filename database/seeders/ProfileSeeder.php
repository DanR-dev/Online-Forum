<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Profile;
use App\Models\User;
use App\Models\Role;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userA = User::find(1);
        $userB = User::find(2);
        $roleA = Role::find(1);
        $roleB = Role::find(2);
        $roleC = Role::find(3);

        $profileA = new Profile;
        $profileA->name = 'Alex';
        $profileA->user_id = $userA->id;
        $profileA->save();
        $profileA->roles()->attach($roleA);
        $profileA->roles()->attach($roleB);
        
        $profileB = new Profile;
        $profileB->name = 'Bob';
        $profileB->user_id = $userB->id;
        $profileB->save();
        $profileB->roles()->attach($roleC);

        
        foreach(Profile::factory(50)->create() as &$profile)
        {
            $role = Role::inRandomOrder()->limit(mt_rand(0, Role::get()->count()))->get();
            $profile->roles()->attach($role);
        }
    }
}
