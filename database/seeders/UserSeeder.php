<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Crear el usuario Admin
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'), // Contraseña: password
        ]);
        
        // 2. Crear el usuario Juez
        User::create([
            'name' => 'Juez Creador',
            'email' => 'juez@example.com',
            'password' => Hash::make('password'), // Contraseña: password
        ]);
        
        // 3. Crear el usuario Estudiante
        User::create([
            'name' => 'Estudiante Proy',
            'email' => 'estudiante@example.com',
            'password' => Hash::make('password'), // Contraseña: password
        ]);

        $this->command->info('Usuarios de prueba (Admin, Juez, Estudiante) creados con email y password: password.');
    }
}
