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
            //**************************account******************************* */

            'account-list',
            'account-show',
            'account-create',
            'account-update',
            'account-delete',

            //**************************costcenter******************************* */

            'costcenter-list',
            'costcenter-show',
            'costcenter-create',
            'costcenter-update',
            'costcenter-delete',

            //**************************period******************************* */

            'period-list',
            'period-show',
            'period-create',
            'period-update',
            'period-delete',
            'period-close',
            'period-open',

            //**************************opening_balance******************************* */

            'opening_balance-list',
            'opening_balance-show',
            'opening_balance-create',
            'opening_balance-update',
            'opening_balance-delete',

            //**************************transaction******************************* */

            'transaction-list',
            'transaction-show',
            'transaction-create',
            'transaction-update',
            'transaction-delete',

            //**************************project******************************* */

            'project-list',
            'project-show',
            'project-create',
            'project-update',
            'project-delete',
            'project-archiveshow',
            'project-archive',
            'project-addInvestor',
            'project-addStage',


            //**************************customer******************************* */

            'customer-list',
            'customer-show',
            'customer-create',
            'customer-update',
            'customer-delete',
            'customer-statement',

            //**************************investor******************************* */

            'investor-list',
            'investor-show',
            'investor-create',
            'investor-update',
            'investor-delete',

            //**************************income******************************* */

            'income-list',
            'income-show',
            'income-create',
            'income-update',
            'income-delete',
            'income-approve',

            //**************************expense******************************* */

            'expense-list',
            'expense-show',
            'expense-create',
            'expense-update',
            'expense-delete',
            'expense-approve',

            //**************************report******************************* */

            'report-list',
            'report-trialBalance',
            'report-balanceSheet',
            'report-profitAndLoss',
            'report-statementOfAccount',


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
