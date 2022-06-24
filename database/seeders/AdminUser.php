<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminUser extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name'=>'Administrator',
            'email'=>'admin@gmail.com',
            'email_verified_at'=>date('Y-m-d h:i:s'),
            'password'=>Hash::make(12345),
            'remember_token'=>Str::random(20),
            'api_token'=>Str::random(20),
        ]);
    }
}
