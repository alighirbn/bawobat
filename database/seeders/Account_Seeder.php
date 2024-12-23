<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Account_Seeder extends Seeder
{
    public function run()
    {
        $accounts = [
            // الفصل 1 - رأس المال والالتزامات (Capital and Liabilities)
            ['url_address' => $this->get_random_string(60), 'code' => '101', 'name' => 'رأس المال الاجتماعي', 'type' => 'Equity', 'class' => 1, 'category' => 'Non-Current'],
            ['url_address' => $this->get_random_string(60), 'code' => '102', 'name' => 'رأس المال الفردي', 'type' => 'Equity', 'class' => 1, 'category' => 'Non-Current'],
            ['url_address' => $this->get_random_string(60), 'code' => '104', 'name' => 'احتياطي رأس المال', 'type' => 'Equity', 'class' => 1, 'category' => 'Non-Current'],
            ['url_address' => $this->get_random_string(60), 'code' => '110', 'name' => 'الأرباح المحتجزة', 'type' => 'Equity', 'class' => 1, 'category' => 'Non-Current'],
            ['url_address' => $this->get_random_string(60), 'code' => '120', 'name' => 'نتيجة السنة', 'type' => 'Equity', 'class' => 1, 'category' => 'Non-Current'],
            ['url_address' => $this->get_random_string(60), 'code' => '161', 'name' => 'قروض طويلة الأجل', 'type' => 'Liability', 'class' => 1, 'category' => 'Non-Current'],

            // الفصل 2 - الأصول الثابتة (Fixed Assets)
            ['url_address' => $this->get_random_string(60), 'code' => '224', 'name' => 'اصول عامة', 'type' => 'Asset', 'class' => 2, 'category' => 'Non-Current'],
            ['url_address' => $this->get_random_string(60), 'code' => '225', 'name' => 'مركبات', 'type' => 'Asset', 'class' => 2, 'category' => 'Non-Current'],
            ['url_address' => $this->get_random_string(60), 'code' => '226', 'name' => 'معدات مكتبية', 'type' => 'Asset', 'class' => 2, 'category' => 'Non-Current'],
            ['url_address' => $this->get_random_string(60), 'code' => '252', 'name' => 'استثمار في رأس المال', 'type' => 'Asset', 'class' => 2, 'category' => 'Non-Current'],
            ['url_address' => $this->get_random_string(60), 'code' => '282', 'name' => 'اندثار اصل', 'type' => 'Asset', 'class' => 2, 'category' => 'Non-Current'],

            // الفصل 3 - المخزونات والعمل الجاري (Stocks and Work in Progress)

            ['url_address' => $this->get_random_string(60), 'code' => '371', 'name' => 'السلع الجاهزة', 'type' => 'Asset', 'class' => 3, 'category' => 'Current'],

            // الفصل 4 - الحسابات المدينة والدائنة (Accounts Receivable and Payable)
            ['url_address' => $this->get_random_string(60), 'code' => '401', 'name' => 'الموردين', 'type' => 'Liability', 'class' => 4, 'category' => 'Current'],
            ['url_address' => $this->get_random_string(60), 'code' => '411', 'name' => 'العملاء', 'type' => 'Asset', 'class' => 4, 'category' => 'Current'],
            ['url_address' => $this->get_random_string(60), 'code' => '421', 'name' => 'ذمة الموظفين (الرواتب)', 'type' => 'Liability', 'class' => 4, 'category' => 'Current'],
            ['url_address' => $this->get_random_string(60), 'code' => '428', 'name' => 'سلف الموظفين', 'type' => 'Asset', 'class' => 4, 'category' => 'Current'],
            ['url_address' => $this->get_random_string(60), 'code' => '451', 'name' => 'الحسابات الجارية للمساهمين / المستثمرين', 'type' => 'Liability', 'class' => 4, 'category' => 'Operating'],
            ['url_address' => $this->get_random_string(60), 'code' => '472', 'name' => 'ذمة لمصاريف محتسبة', 'type' => 'Liability', 'class' => 4, 'category' => 'Current'],
            ['url_address' => $this->get_random_string(60), 'code' => '473', 'name' => 'ذمة لأيرادات محتسبة', 'type' => 'Asset', 'class' => 4, 'category' => 'Current'],

            // الفصل 5 - النقد والبنك (Cash and Bank)
            ['url_address' => $this->get_random_string(60), 'code' => '512', 'name' => 'البنك', 'type' => 'Asset', 'class' => 5, 'category' => 'Current'],
            ['url_address' => $this->get_random_string(60), 'code' => '530', 'name' => 'الصندوق', 'type' => 'Asset', 'class' => 5, 'category' => 'Current'],

            // الفصل 6 - المصاريف (Expenses)
            ['url_address' => $this->get_random_string(60), 'code' => '601', 'name' => 'المشتريات', 'type' => 'Expense', 'class' => 6, 'category' => 'Operating'],
            ['url_address' => $this->get_random_string(60), 'code' => '618', 'name' => 'مصاريف الشركة المستثمرة', 'type' => 'Expense', 'class' => 6, 'category' => 'Operating'],
            ['url_address' => $this->get_random_string(60), 'code' => '626', 'name' => 'مصاريف تشغيلية', 'type' => 'Expense', 'class' => 6, 'category' => 'Operating'], // Other Expenses
            ['url_address' => $this->get_random_string(60), 'code' => '631', 'name' => 'الرواتب', 'type' => 'Expense', 'class' => 6, 'category' => 'Operating'],
            ['url_address' => $this->get_random_string(60), 'code' => '651', 'name' => 'مصاريف اندثار اصل', 'type' => 'Expense', 'class' => 6, 'category' => 'Operating'], // Other Expenses
            ['url_address' => $this->get_random_string(60), 'code' => '665', 'name' => 'مصاريف خسائر استثمار', 'type' => 'Expense', 'class' => 6, 'category' => 'Operating'], // Other Expenses
            ['url_address' => $this->get_random_string(60), 'code' => '673', 'name' => ' فوائد على القروض', 'type' => 'Expense', 'class' => 6, 'category' => 'Operating'], // Other Expenses
            ['url_address' => $this->get_random_string(60), 'code' => '675', 'name' => 'فروقات تحويل عملة', 'type' => 'Expense', 'class' => 6, 'category' => 'Operating'], // Other Expenses
            ['url_address' => $this->get_random_string(60), 'code' => '681', 'name' => 'مصاريف اخلاء اصل', 'type' => 'Expense', 'class' => 6, 'category' => 'Operating'], // Other Expenses
            ['url_address' => $this->get_random_string(60), 'code' => '685', 'name' => 'مصاريف أخرى', 'type' => 'Expense', 'class' => 6, 'category' => 'Operating'], // Other Expenses

            // الفصل 7 - الإيرادات (Incomes)
            ['url_address' => $this->get_random_string(60), 'code' => '701', 'name' => 'الإيرادات التشغيلية', 'type' => 'Income', 'class' => 7, 'category' => 'Operating'],
            ['url_address' => $this->get_random_string(60), 'code' => '765', 'name' => 'إيرادات الاستثمارات', 'type' => 'Income', 'class' => 7, 'category' => 'Non-Operating'],
            ['url_address' => $this->get_random_string(60), 'code' => '775', 'name' => 'فروقات تحويل عملة', 'type' => 'Income', 'class' => 7, 'category' => 'Operating'], // Other Expenses
            ['url_address' => $this->get_random_string(60), 'code' => '781', 'name' => 'ايراد بيع اصل', 'type' => 'Income', 'class' => 7, 'category' => 'Operating'], // Other Expenses
            ['url_address' => $this->get_random_string(60), 'code' => '788', 'name' => 'الإيرادات الأخرى', 'type' => 'Income', 'class' => 7, 'category' => 'Non-Operating'],
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
