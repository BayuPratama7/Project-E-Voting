<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\User;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create Super Admin
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@himsi.ac.id',
            'username' => 'superadmin',
            'password' => Hash::make('superadmin123'),
            'role' => 'super_admin',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        // Create Regular Admin
        User::create([
            'name' => 'Admin HIMSI',
            'email' => 'admin@himsi.ac.id',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        // Create Additional Admin
        User::create([
            'name' => 'Admin Pemilihan',
            'email' => 'adminpemilihan@himsi.ac.id',
            'username' => 'adminpemilihan',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        $this->command->info('Admin users created successfully!');
    }
}
