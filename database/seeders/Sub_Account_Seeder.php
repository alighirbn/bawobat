<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Sub_Account_Seeder extends Seeder
{
    public function run()
    {
        $accounts = [

            ['url_address' => $this->get_random_string(60), 'code' => '5300001', 'name' => 'صندوق بوابة العلم', 'type' => 'Asset', 'class' => 5, 'category' => 'Current', 'parent_id' => 22],
            ['url_address' => $this->get_random_string(60), 'code' => '5300002', 'name' => 'صندوق مجمع الياسمين', 'type' => 'Asset', 'class' => 5, 'category' => 'Current', 'parent_id' => 22],

            ['url_address' => $this->get_random_string(60), 'code' => '7010001', 'name' => 'ايراد عام', 'type' => 'Income', 'class' => 7, 'category' => 'Operating', 'parent_id' => 33],
            ['url_address' => $this->get_random_string(60), 'code' => '7010202', 'name' => 'ايراد دفعات الدور', 'type' => 'Income', 'class' => 7, 'category' => 'Operating', 'parent_id' => 33],
            ['url_address' => $this->get_random_string(60), 'code' => '7010203', 'name' => 'ايراد فسخ الدور', 'type' => 'Income', 'class' => 7, 'category' => 'Operating', 'parent_id' => 33],

        ];

        DB::table('accounts')->insert($accounts);
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
