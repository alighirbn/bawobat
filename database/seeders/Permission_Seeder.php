<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Permission_Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // add Permissions
        $permissions = [
            //dashboard



            //**************************customer******************************* */
            //customer permissions
            'customer-list',
            'customer-show',
            'customer-create',
            'customer-update',
            'customer-delete',
            'customer-statement',





            //**************************income******************************* */
            //income permissions
            'income-list',
            'income-show',
            'income-create',
            'income-update',
            'income-delete',
            'income-approve',

            //**************************expense******************************* */
            //expense permissions
            'expense-list',
            'expense-show',
            'expense-create',
            'expense-update',
            'expense-delete',
            'expense-approve',

            //**************************cash_account******************************* */
            //cash_account permissions
            'cash_account-list',
            'cash_account-show',
            'cash_account-create',
            'cash_account-update',
            'cash_account-delete',

            //**************************cash_transfer******************************* */
            //cash_transfer permissions
            'cash_transfer-list',
            'cash_transfer-show',
            'cash_transfer-create',
            'cash_transfer-update',
            'cash_transfer-delete',
            'cash_transfer-approve',








            //**************************user******************************* */



            'user-list',
            'user-show',
            'user-create',
            'user-update',
            'user-delete',

            //**************************role******************************* */


            'role-list',
            'role-show',
            'role-create',
            'role-update',
            'role-delete',

        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        $user = User::create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('12345678'),
            'department_id' => 1,
            'url_address' => $this->get_random_string(60),
            'Status' => 'active',
        ]);

        $role = Role::create(['name' => 'admin']);

        $per = Permission::pluck('id', 'id')->all();

        $role->syncPermissions($per);

        $user->assignRole([$role]);
    }

    public function get_random_string($length)
    {
        $array = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
        $text = "";
        $length = rand(22, $length);

        for ($i = 0; $i < $length; $i++) {
            $random = rand(0, 61);
            $text .= $array[$random];
        }
        return $text;
    }
}
