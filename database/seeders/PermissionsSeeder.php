<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User; // Necesario para asignar el rol al primer usuario
use Illuminate\Support\Facades\DB; // Se mantiene por si lo usas más tarde, aunque no es estrictamente necesario aquí

class PermissionsSeeder extends Seeder
{
    /**
     * Ejecuta las semillas de la base de datos.
     */
    public function run(): void
    {
        // 1. Limpiar caché de permisos
        // Esto previene errores de caché cuando se crean o modifican permisos.
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 2. Definir los permisos según los requisitos de las vistas
        $permissions = [
            'ver eventos',          // Para EventInformation.blade.php y Dashboard.blade.php
            'enviar proyecto',      // Para Envio.blade.php
            'calificar',            // Para Calificar.blade.php
            'crear eventos',        // Para Newevent.blade.php
            'gestionar usuarios',   // Permiso de superadministrador (Admin)
        ];

        // 3. Crear los permisos si no existen
        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName]);
        }

        // 4. Crear los Roles
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $juezRole = Role::firstOrCreate(['name' => 'Juez']);
        $estudianteRole = Role::firstOrCreate(['name' => 'Estudiante']);

        // 5. Asignar Permisos a Roles

        // ROL: Admin (Tiene todos los permisos registrados)
        $adminRole->givePermissionTo(Permission::all());

        // ROL: Juez
        $juezRole->givePermissionTo([
            'ver eventos',
            'calificar',
        ]);
        
        // ROL: Estudiante
        $estudianteRole->givePermissionTo([
            'ver eventos',
            'enviar proyecto',
        ]);

        // 6. Asignar un rol a un usuario existente (Ejemplo para empezar)
        // Busca el primer usuario y le asigna el rol 'Admin'.
        $adminUser = User::first();
        if ($adminUser) {
            $adminUser->assignRole('Admin');
            // Nota: \Illuminate\Console\Concerns\InteractsWithIO::info es solo una función de salida de consola
            $this->command->info("Rol 'Admin' asignado al primer usuario (ID: {$adminUser->id}).");
        } else {
            $this->command->warn("No se encontró ningún usuario. Por favor, regístrate para que se asigne el rol 'Admin'.");
        }
    }
}
