<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class GrantAllPermsToAdmin extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = Role::query()->where("name","=", "administrator")->first();
        if ($admin) {
            $admin->givePermissionTo(Permission::all());
        }
    }
}
