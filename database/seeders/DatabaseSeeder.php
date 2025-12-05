<?php


namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\PermissionsSeeder; 

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Llama a las clases Seeder que deseas ejecutar
        $this->call([
            PermissionsSeeder::class,
            UserSeeder::class,
             // <-- ¡Tu seeder de permisos debe estar aquí!
            // Otros seeders, como UserSeeder::class, si los tienes
        ]);
    }
}
