<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Period_Seeder extends Seeder
{
    public function run()
    {
        $periods = [
            [
                'url_address' => $this->get_random_string(60),
                'name' => 'الافتراضي',
                'start_date' => Carbon::now()->startOfMonth(), // Set start_date to the first day of the current month
                'end_date' => Carbon::now()->endOfMonth(), // Set end_date to the last day of the current month
                'is_active' => true,
            ],
        ];

        DB::table('periods')->insert($periods);
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