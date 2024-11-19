<?php

namespace Database\Seeders;


use App\Models\Cash\ExpenseType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Expense_Type_Seeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {

    $expense_types = [
      [
        'name' => 'صرفيات متنوعة',
        'description' => 'الحساب الرئيسي',
      ],
    ];
    foreach ($expense_types as $expense_type) {
      ExpenseType::create($expense_type);
    }
  }
}
