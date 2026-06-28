<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        Student::create([
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'student@example.com',
            'password' => Hash::make('password'),
            'status' => 'active',
        ]);
    }
}
