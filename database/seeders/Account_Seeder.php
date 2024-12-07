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
            ['url_address' => $this->get_random_string(60), 'code' => '102', 'name' => 'رأس المال الأولي', 'type' => 'Equity', 'class' => 1, 'category' => 'Non-Current'],
            ['url_address' => $this->get_random_string(60), 'code' => '103', 'name' => 'قروض طويلة الأجل', 'type' => 'Equity', 'class' => 1, 'category' => 'Non-Current'],
            ['url_address' => $this->get_random_string(60), 'code' => '104', 'name' => 'احتياطي رأس المال', 'type' => 'Equity', 'class' => 1, 'category' => 'Non-Current'],
            ['url_address' => $this->get_random_string(60), 'code' => '105', 'name' => 'الأرباح المحتجزة', 'type' => 'Equity', 'class' => 1, 'category' => 'Non-Current'],

            // الفصل 2 - الأصول الثابتة (Fixed Assets)
            ['url_address' => $this->get_random_string(60), 'code' => '201', 'name' => 'الأراضي', 'type' => 'Asset', 'class' => 2, 'category' => 'Non-Current'],
            ['url_address' => $this->get_random_string(60), 'code' => '202', 'name' => 'المباني', 'type' => 'Asset', 'class' => 2, 'category' => 'Non-Current'],
            ['url_address' => $this->get_random_string(60), 'code' => '203', 'name' => 'الآلات والأدوات', 'type' => 'Asset', 'class' => 2, 'category' => 'Non-Current'],
            ['url_address' => $this->get_random_string(60), 'code' => '204', 'name' => 'مركبات', 'type' => 'Asset', 'class' => 2, 'category' => 'Non-Current'],
            ['url_address' => $this->get_random_string(60), 'code' => '205', 'name' => 'معدات مكتبية', 'type' => 'Asset', 'class' => 2, 'category' => 'Non-Current'],

            // الفصل 3 - المخزونات والعمل الجاري (Stocks and Work in Progress)
            ['url_address' => $this->get_random_string(60), 'code' => '301', 'name' => 'المواد الخام', 'type' => 'Asset', 'class' => 3, 'category' => 'Current'],
            ['url_address' => $this->get_random_string(60), 'code' => '302', 'name' => 'المنتجات الجارية', 'type' => 'Asset', 'class' => 3, 'category' => 'Current'],
            ['url_address' => $this->get_random_string(60), 'code' => '303', 'name' => 'السلع الجاهزة', 'type' => 'Asset', 'class' => 3, 'category' => 'Current'],

            // الفصل 4 - الحسابات المدينة والدائنة (Accounts Receivable and Payable)
            ['url_address' => $this->get_random_string(60), 'code' => '401', 'name' => 'العملاء', 'type' => 'Asset', 'class' => 4, 'category' => 'Current'],
            ['url_address' => $this->get_random_string(60), 'code' => '402', 'name' => 'الموردين', 'type' => 'Liability', 'class' => 4, 'category' => 'Current'],
            ['url_address' => $this->get_random_string(60), 'code' => '403', 'name' => 'الحسابات المدينة قصيرة الأجل', 'type' => 'Asset', 'class' => 4, 'category' => 'Current'],
            ['url_address' => $this->get_random_string(60), 'code' => '404', 'name' => 'الحسابات الدائنة قصيرة الأجل', 'type' => 'Liability', 'class' => 4, 'category' => 'Current'],
            ['url_address' => $this->get_random_string(60), 'code' => '455', 'name' => 'الحسابات الجارية للمساهمين / المستثمرين', 'type' => 'Liability', 'class' => 4, 'category' => 'Operating'],

            // الفصل 5 - النقد والبنك (Cash and Bank)
            ['url_address' => $this->get_random_string(60), 'code' => '501', 'name' => 'الصندوق', 'type' => 'Asset', 'class' => 5, 'category' => 'Current'],
            ['url_address' => $this->get_random_string(60), 'code' => '502', 'name' => 'البنك', 'type' => 'Asset', 'class' => 5, 'category' => 'Current'],
            ['url_address' => $this->get_random_string(60), 'code' => '503', 'name' => 'حسابات جارية', 'type' => 'Asset', 'class' => 5, 'category' => 'Current'],
            ['url_address' => $this->get_random_string(60), 'code' => '504', 'name' => 'ودائع بنكية', 'type' => 'Asset', 'class' => 5, 'category' => 'Non-Current'],

            // الفصل 6 - المصاريف (Expenses)
            ['url_address' => $this->get_random_string(60), 'code' => '601', 'name' => 'المشتريات', 'type' => 'Expense', 'class' => 6, 'category' => 'Operating'],
            ['url_address' => $this->get_random_string(60), 'code' => '602', 'name' => 'الرواتب', 'type' => 'Expense', 'class' => 6, 'category' => 'Operating'],
            ['url_address' => $this->get_random_string(60), 'code' => '603', 'name' => 'الإيجارات', 'type' => 'Expense', 'class' => 6, 'category' => 'Operating'],
            ['url_address' => $this->get_random_string(60), 'code' => '604', 'name' => 'فواتير الخدمات', 'type' => 'Expense', 'class' => 6, 'category' => 'Operating'],
            ['url_address' => $this->get_random_string(60), 'code' => '605', 'name' => 'المصاريف الإدارية', 'type' => 'Expense', 'class' => 6, 'category' => 'Operating'],
            ['url_address' => $this->get_random_string(60), 'code' => '606', 'name' => 'المصاريف التسويقية', 'type' => 'Expense', 'class' => 6, 'category' => 'Operating'], // Marketing Expenses
            ['url_address' => $this->get_random_string(60), 'code' => '607', 'name' => 'المصاريف القانونية', 'type' => 'Expense', 'class' => 6, 'category' => 'Operating'], // Legal Expenses
            ['url_address' => $this->get_random_string(60), 'code' => '608', 'name' => 'الضرائب', 'type' => 'Expense', 'class' => 6, 'category' => 'Operating'], // Taxes
            ['url_address' => $this->get_random_string(60), 'code' => '609', 'name' => 'التأمينات', 'type' => 'Expense', 'class' => 6, 'category' => 'Operating'], // Insurance Expenses
            ['url_address' => $this->get_random_string(60), 'code' => '610', 'name' => 'مصاريف الصيانة', 'type' => 'Expense', 'class' => 6, 'category' => 'Operating'], // Maintenance Expenses
            ['url_address' => $this->get_random_string(60), 'code' => '611', 'name' => 'مصاريف السفر', 'type' => 'Expense', 'class' => 6, 'category' => 'Operating'], // Travel Expenses
            ['url_address' => $this->get_random_string(60), 'code' => '612', 'name' => 'مصاريف التدريب', 'type' => 'Expense', 'class' => 6, 'category' => 'Operating'], // Training Expenses
            ['url_address' => $this->get_random_string(60), 'code' => '613', 'name' => 'مصاريف النقل', 'type' => 'Expense', 'class' => 6, 'category' => 'Operating'], // Transport Expenses
            ['url_address' => $this->get_random_string(60), 'code' => '614', 'name' => 'مصاريف أخرى', 'type' => 'Expense', 'class' => 6, 'category' => 'Operating'], // Other Expenses

            // الفصل 7 - الإيرادات (Incomes)
            ['url_address' => $this->get_random_string(60), 'code' => '701', 'name' => 'الإيرادات التشغيلية', 'type' => 'Income', 'class' => 7, 'category' => 'Operating'],
            ['url_address' => $this->get_random_string(60), 'code' => '702', 'name' => 'إيرادات الاستثمارات', 'type' => 'Income', 'class' => 7, 'category' => 'Non-Operating'],
            ['url_address' => $this->get_random_string(60), 'code' => '703', 'name' => 'الإيرادات الأخرى', 'type' => 'Income', 'class' => 7, 'category' => 'Non-Operating'],
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
