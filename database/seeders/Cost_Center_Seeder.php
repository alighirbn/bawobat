<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Cost_Center_Seeder extends Seeder
{
    public function run()
    {
        $cost_centers = [
            ['code' => 'MA', 'name' => 'الافتراضي', 'description' => 'مركز الكلفة الافتراضي'],
        ];

        DB::table('cost_centers')->insert($cost_centers);
    }
}
