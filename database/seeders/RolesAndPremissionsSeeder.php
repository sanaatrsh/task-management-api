<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPremissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $arrayOfPermissions = ["create task", "assign tasks", "view all tasks", "complete task"];
        // $permission = collect($arrayOfPermissions)->map(
        //     function ($permission) {
        //         return ['name' => $permission, 'guard_name' => 'api'];
        //     }
        // );
        // Permission::insert($permission->toArray());

        $createTaskPermission = Permission::create(['name' => 'create task', 'guard_name' => 'api']);
        $assignTasksPermission = Permission::create(['name' => 'assign tasks', 'guard_name' => 'api']);
        $viewAllTasksPermission = Permission::create(['name' => 'view all tasks', 'guard_name' => 'api']);
        $completeTask = Permission::create(['name' => 'complete task']);


        $role = Role::create(['name' => "admin"])->givePermissionTo(Permission::all());
        $role = Role::create(['name' => "manager"])->givePermissionTo(Permission::all());
        $role = Role::create(['name' => "employee"])->givePermissionTo($completeTask);
    }
}
