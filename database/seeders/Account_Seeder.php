<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Account_Seeder extends Seeder
{
    public function run()
    {
        $accounts = [
            // الفصل 1 - رأس المال والالتزامات (Capital and Liabilities) 6
            ['url_address' => $this->get_random_string(60), 'code' => '1010', 'name' => 'رأس المال الاجتماعي', 'type' => 'Equity', 'class' => 1, 'category' => 'Non-Current'],
            ['url_address' => $this->get_random_string(60), 'code' => '1020', 'name' => 'رأس المال الفردي', 'type' => 'Equity', 'class' => 1, 'category' => 'Non-Current'],
            ['url_address' => $this->get_random_string(60), 'code' => '1040', 'name' => 'احتياطي رأس المال', 'type' => 'Equity', 'class' => 1, 'category' => 'Non-Current'],
            ['url_address' => $this->get_random_string(60), 'code' => '1100', 'name' => 'الأرباح المحتجزة', 'type' => 'Equity', 'class' => 1, 'category' => 'Non-Current'],
            ['url_address' => $this->get_random_string(60), 'code' => '1200', 'name' => 'نتيجة السنة', 'type' => 'Equity', 'class' => 1, 'category' => 'Non-Current'],
            ['url_address' => $this->get_random_string(60), 'code' => '1610', 'name' => 'قروض طويلة الأجل', 'type' => 'Liability', 'class' => 1, 'category' => 'Non-Current'],

            // الفصل 2 - الأصول الثابتة (Fixed Assets) 6
            ['url_address' => $this->get_random_string(60), 'code' => '2150', 'name' => 'مباني الشركة المستثمرة', 'type' => 'Asset', 'class' => 2, 'category' => 'Non-Current'],
            ['url_address' => $this->get_random_string(60), 'code' => '2240', 'name' => 'اصول عامة', 'type' => 'Asset', 'class' => 2, 'category' => 'Non-Current'],
            ['url_address' => $this->get_random_string(60), 'code' => '2250', 'name' => 'مركبات', 'type' => 'Asset', 'class' => 2, 'category' => 'Non-Current'],
            ['url_address' => $this->get_random_string(60), 'code' => '2260', 'name' => 'معدات مكتبية', 'type' => 'Asset', 'class' => 2, 'category' => 'Non-Current'],
            ['url_address' => $this->get_random_string(60), 'code' => '2520', 'name' => 'استثمار في رأس المال', 'type' => 'Asset', 'class' => 2, 'category' => 'Non-Current'],
            ['url_address' => $this->get_random_string(60), 'code' => '2820', 'name' => 'اندثار اصل', 'type' => 'Asset', 'class' => 2, 'category' => 'Non-Current'],

            // الفصل 3 - المخزونات والعمل الجاري (Stocks and Work in Progress) 1

            ['url_address' => $this->get_random_string(60), 'code' => '3710', 'name' => 'السلع الجاهزة', 'type' => 'Asset', 'class' => 3, 'category' => 'Current'],

            // الفصل 4 - الحسابات المدينة والدائنة (Accounts Receivable and Payable) 7
            ['url_address' => $this->get_random_string(60), 'code' => '4010', 'name' => 'الموردين', 'type' => 'Liability', 'class' => 4, 'category' => 'Current'],
            ['url_address' => $this->get_random_string(60), 'code' => '4110', 'name' => 'العملاء', 'type' => 'Asset', 'class' => 4, 'category' => 'Current'],
            ['url_address' => $this->get_random_string(60), 'code' => '4210', 'name' => 'ذمة الموظفين (الرواتب)', 'type' => 'Liability', 'class' => 4, 'category' => 'Current'],
            ['url_address' => $this->get_random_string(60), 'code' => '4280', 'name' => 'سلف الموظفين', 'type' => 'Asset', 'class' => 4, 'category' => 'Current'],
            ['url_address' => $this->get_random_string(60), 'code' => '4510', 'name' => 'الحسابات الجارية للمساهمين / المستثمرين', 'type' => 'Liability', 'class' => 4, 'category' => 'Operating'],
            ['url_address' => $this->get_random_string(60), 'code' => '4720', 'name' => 'ذمة لمصاريف محتسبة', 'type' => 'Liability', 'class' => 4, 'category' => 'Current'],
            ['url_address' => $this->get_random_string(60), 'code' => '4730', 'name' => 'ذمة لأيرادات محتسبة', 'type' => 'Asset', 'class' => 4, 'category' => 'Current'],

            // الفصل 5 - النقد والبنك (Cash and Bank) 4
            ['url_address' => $this->get_random_string(60), 'code' => '5120', 'name' => 'البنك', 'type' => 'Asset', 'class' => 5, 'category' => 'Current'],
            ['url_address' => $this->get_random_string(60), 'code' => '5300', 'name' => 'الصندوق', 'type' => 'Asset', 'class' => 5, 'category' => 'Current'],

            // الفصل 6 - المصاريف (Expenses) 10
            ['url_address' => $this->get_random_string(60), 'code' => '6010', 'name' => 'المشتريات', 'type' => 'Expense', 'class' => 6, 'category' => 'Operating'],
            ['url_address' => $this->get_random_string(60), 'code' => '6180', 'name' => 'مصاريف الشركة المستثمرة', 'type' => 'Expense', 'class' => 6, 'category' => 'Operating'],
            ['url_address' => $this->get_random_string(60), 'code' => '6260', 'name' => 'مصاريف تشغيلية', 'type' => 'Expense', 'class' => 6, 'category' => 'Operating'], // Other Expenses
            ['url_address' => $this->get_random_string(60), 'code' => '6310', 'name' => 'الرواتب', 'type' => 'Expense', 'class' => 6, 'category' => 'Operating'],
            ['url_address' => $this->get_random_string(60), 'code' => '6510', 'name' => 'مصاريف اندثار اصل', 'type' => 'Expense', 'class' => 6, 'category' => 'Operating'], // Other Expenses
            ['url_address' => $this->get_random_string(60), 'code' => '6650', 'name' => 'مصاريف خسائر استثمار', 'type' => 'Expense', 'class' => 6, 'category' => 'Operating'], // Other Expenses
            ['url_address' => $this->get_random_string(60), 'code' => '6730', 'name' => ' فوائد على القروض', 'type' => 'Expense', 'class' => 6, 'category' => 'Operating'], // Other Expenses
            ['url_address' => $this->get_random_string(60), 'code' => '6750', 'name' => 'فروقات تحويل عملة', 'type' => 'Expense', 'class' => 6, 'category' => 'Operating'], // Other Expenses
            ['url_address' => $this->get_random_string(60), 'code' => '6810', 'name' => 'مصاريف اخلاء اصل', 'type' => 'Expense', 'class' => 6, 'category' => 'Operating'], // Other Expenses
            ['url_address' => $this->get_random_string(60), 'code' => '6850', 'name' => 'مصاريف أخرى', 'type' => 'Expense', 'class' => 6, 'category' => 'Operating'], // Other Expenses

            // الفصل 7 - الإيرادات (Incomes)
            ['url_address' => $this->get_random_string(60), 'code' => '7010', 'name' => 'الإيرادات التشغيلية', 'type' => 'Income', 'class' => 7, 'category' => 'Operating'],
            ['url_address' => $this->get_random_string(60), 'code' => '7650', 'name' => 'إيرادات الاستثمارات', 'type' => 'Income', 'class' => 7, 'category' => 'Non-Operating'],
            ['url_address' => $this->get_random_string(60), 'code' => '7750', 'name' => 'فروقات تحويل عملة', 'type' => 'Income', 'class' => 7, 'category' => 'Operating'], // Other Expenses
            ['url_address' => $this->get_random_string(60), 'code' => '7810', 'name' => 'ايراد بيع اصل', 'type' => 'Income', 'class' => 7, 'category' => 'Operating'], // Other Expenses
            ['url_address' => $this->get_random_string(60), 'code' => '7880', 'name' => 'الإيرادات الأخرى', 'type' => 'Income', 'class' => 7, 'category' => 'Non-Operating'],
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
