<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectSeeder extends Seeder
{
    public function run()
    {
        $projects = [
            [
                'url_address' => $this->get_random_string(60),
                'cost_center_id' => 2,
                'name' => 'بوابة العلم',
                'description' => 'مركز الكلفة بوابة العلم',
                'budget' => 150000000000,
                'start_date' => '2024-06-01',
                'end_date' => '2027-06-1',
                'status' => 'ongoing',
                'user_id_create' => 1,
                'user_id_update' => 1,
            ],
        ];

        DB::table('projects')->insert($projects);
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
