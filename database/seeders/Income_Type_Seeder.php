<?php

namespace Database\Seeders;

use App\Models\Income\IncomeType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Income_Type_Seeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {

    $income_types = [
      [
        'name' => 'واردات',
        'description' => 'الحساب الرئيسي',
      ],
    ];
    foreach ($income_types as $income_type) {
      IncomeType::create($income_type);
    }
  }
}
