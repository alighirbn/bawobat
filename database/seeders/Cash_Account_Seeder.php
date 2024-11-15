<?php

namespace Database\Seeders;


use App\Models\Cash\CashAccount;
use Illuminate\Database\Seeder;

class Cash_Account_Seeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    // add Departments
    $cash_accounts = [
      [
        'url_address' => 'kguydsgsdjdsvwuwufuvvudvvwuvuugsgssf',
        'name' => 'الحساب الرئيسي',
        'account_number' => '530000',
        'balance' => 0,
      ],

    ];
    foreach ($cash_accounts as $cash_account) {
      CashAccount::create($cash_account);
    }
  }
}
