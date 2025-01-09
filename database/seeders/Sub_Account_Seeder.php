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

            ['url_address' => $this->get_random_string(60), 'code' => '6260001', 'name' => 'اجور مولده ( كاز - دهن - صيانه)', 'type' => 'Expense', 'class' => 6, 'category' => 'Operating', 'parent_id' => 25],
            ['url_address' => $this->get_random_string(60), 'code' => '6260002', 'name' => 'اجور فواتير الماء', 'type' => 'Expense', 'class' => 6, 'category' => 'Operating', 'parent_id' => 25],
            ['url_address' => $this->get_random_string(60), 'code' => '6260003', 'name' => 'اجور فواتير الكهرباء', 'type' => 'Expense', 'class' => 6, 'category' => 'Operating', 'parent_id' => 25],
            ['url_address' => $this->get_random_string(60), 'code' => '6260004', 'name' => 'اجور فواتير الانترنيت', 'type' => 'Expense', 'class' => 6, 'category' => 'Operating', 'parent_id' => 25],
            ['url_address' => $this->get_random_string(60), 'code' => '6260005', 'name' => 'لوازم قرطاسية', 'type' => 'Expense', 'class' => 6, 'category' => 'Operating', 'parent_id' => 25],
            ['url_address' => $this->get_random_string(60), 'code' => '6260006', 'name' => 'اجور احبار الطابعات', 'type' => 'Expense', 'class' => 6, 'category' => 'Operating', 'parent_id' => 25],
            ['url_address' => $this->get_random_string(60), 'code' => '6260007', 'name' => 'اجور اساسية ( ماء - شاي - قهوه - سكر - سفريات)', 'type' => 'Expense', 'class' => 6, 'category' => 'Operating', 'parent_id' => 25],
            ['url_address' => $this->get_random_string(60), 'code' => '6260008', 'name' => 'اجور مستحضرات تنظيف (زاهي - صابون - كلينكس)', 'type' => 'Expense', 'class' => 6, 'category' => 'Operating', 'parent_id' => 25],
            ['url_address' => $this->get_random_string(60), 'code' => '6260009', 'name' => 'اجور اطعام (افطار - غداء - عشاء)', 'type' => 'Expense', 'class' => 6, 'category' => 'Operating', 'parent_id' => 25],
            ['url_address' => $this->get_random_string(60), 'code' => '6260010', 'name' => 'اجور دعاية واعلان', 'type' => 'Expense', 'class' => 6, 'category' => 'Operating', 'parent_id' => 25],
            ['url_address' => $this->get_random_string(60), 'code' => '6260011', 'name' => 'اجور مصاريف عمال شركة داخلية', 'type' => 'Expense', 'class' => 6, 'category' => 'Operating', 'parent_id' => 25],

            ['url_address' => $this->get_random_string(60), 'code' => '6310001', 'name' => 'رواتب الموظفين', 'type' => 'Expense', 'class' => 6, 'category' => 'Operating', 'parent_id' => 26],

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
