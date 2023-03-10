<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        try {
            $this->createAdmin();
            $this->createCustomer();
        } catch (\Exception $e) {
            // user already exists
            echo $e->getMessage();
        }
    }

    private function createAdmin(): void
    {
        $user = new User();
        $user->name = env('ADMIN_NAME');
        $user->email = env('ADMIN_EMAIL');
        $user->password = Hash::make(env('ADMIN_PASSWORD'));
        $user->is_admin = true;
        $user->save();
    }

    private function createCustomer(): void
    {
        $user = new User();
        $user->name = fake()->name();
        $user->email = fake()->email();
        $user->password = Hash::make(env('ADMIN_PASSWORD'));
        $user->is_admin = false;
        $user->save();
    }
}
