<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user1 = User::create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => 123,
        ]);
        
        $user2 = User::create([
            'name' => 'user',
            'email' => 'user@user.com',
            'password' => 'user',
        ]);

        $user3 = User::create([
            'name' => 'maintenance',
            'email' => 'maintenance@maintenance.com',
            'password' => 12345,
        ]);
        

        $roleAdmin = Role::create(['name' => 'admin']);
        $user1->assignRole($roleAdmin);

        $roleUser = Role::create(['name' => 'user']);
        $user2->assignRole($roleUser);

        $roleMaintenance = Role::create(['name' => 'maintenance']);
        $user2->assignRole($roleMaintenance);

    }
}
