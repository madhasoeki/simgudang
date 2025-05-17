<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        // Buat akun super-admin
        $user = User::firstOrCreate(
            ['email' => 'superadmin@example.com'], // Ganti dengan email yang Anda inginkan
            [
                'name' => 'Super Admin',
                'password' => bcrypt('password'), // Ganti dengan password yang Anda inginkan
            ]
        );

        // Tambahkan role super-admin
        $role = Role::where('name', 'super-admin')->first();
        if ($role) {
            $user->assignRole($role);
        }
    }
}