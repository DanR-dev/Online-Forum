<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = new Role;
        $role->title = 'Geek';
        $role->save();
        $role = new Role;
        $role->title = 'Karate master';
        $role->save();
        $role = new Role;
        $role->title = 'Project manager';
        $role->save();
        $role = new Role;
        $role->title = 'Bully';
        $role->save();
    }
}
