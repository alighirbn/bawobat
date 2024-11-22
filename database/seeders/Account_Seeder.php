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
            ['url_address' => $this->get_random_string(60), 'code' => '101', 'name' => 'رأس المال الاجتماعي', 'type' => 'liability', 'class' => 1],
            ['url_address' => $this->get_random_string(60), 'code' => '102', 'name' => 'رأس المال الأولي', 'type' => 'liability', 'class' => 1],
            ['url_address' => $this->get_random_string(60), 'code' => '103', 'name' => 'قروض طويلة الأجل', 'type' => 'liability', 'class' => 1],
            ['url_address' => $this->get_random_string(60), 'code' => '104', 'name' => 'احتياطي رأس المال', 'type' => 'liability', 'class' => 1],
            ['url_address' => $this->get_random_string(60), 'code' => '105', 'name' => 'الأرباح المحتجزة', 'type' => 'liability', 'class' => 1],

            // الفصل 2 - الأصول الثابتة (Fixed Assets)
            ['url_address' => $this->get_random_string(60), 'code' => '201', 'name' => 'الأراضي', 'type' => 'asset', 'class' => 2],
            ['url_address' => $this->get_random_string(60), 'code' => '202', 'name' => 'المباني', 'type' => 'asset', 'class' => 2],
            ['url_address' => $this->get_random_string(60), 'code' => '203', 'name' => 'الآلات والأدوات', 'type' => 'asset', 'class' => 2],
            ['url_address' => $this->get_random_string(60), 'code' => '204', 'name' => 'مركبات', 'type' => 'asset', 'class' => 2],
            ['url_address' => $this->get_random_string(60), 'code' => '205', 'name' => 'معدات مكتبية', 'type' => 'asset', 'class' => 2],

            // الفصل 3 - المخزونات والعمل الجاري (Stocks and Work in Progress)
            ['url_address' => $this->get_random_string(60), 'code' => '301', 'name' => 'المواد الخام', 'type' => 'asset', 'class' => 3],
            ['url_address' => $this->get_random_string(60), 'code' => '302', 'name' => 'المنتجات الجارية', 'type' => 'asset', 'class' => 3],
            ['url_address' => $this->get_random_string(60), 'code' => '303', 'name' => 'السلع الجاهزة', 'type' => 'asset', 'class' => 3],

            // الفصل 4 - الحسابات المدينة والدائنة (Accounts Receivable and Payable)
            ['url_address' => $this->get_random_string(60), 'code' => '401', 'name' => 'العملاء', 'type' => 'asset', 'class' => 4],
            ['url_address' => $this->get_random_string(60), 'code' => '402', 'name' => 'الموردين', 'type' => 'liability', 'class' => 4],
            ['url_address' => $this->get_random_string(60), 'code' => '403', 'name' => 'الحسابات المدينة قصيرة الأجل', 'type' => 'asset', 'class' => 4],
            ['url_address' => $this->get_random_string(60), 'code' => '404', 'name' => 'الحسابات الدائنة قصيرة الأجل', 'type' => 'liability', 'class' => 4],
            ['url_address' => $this->get_random_string(60), 'code' => '455', 'name' => 'الحسابات الجارية للمساهمين / المستثمرين', 'type' => 'liability', 'class' => 4],

            // الفصل 5 - النقد والبنك (Cash and Bank)
            ['url_address' => $this->get_random_string(60), 'code' => '501', 'name' => 'الصندوق', 'type' => 'asset', 'class' => 5],
            ['url_address' => $this->get_random_string(60), 'code' => '502', 'name' => 'البنك', 'type' => 'asset', 'class' => 5],
            ['url_address' => $this->get_random_string(60), 'code' => '503', 'name' => 'حسابات جارية', 'type' => 'asset', 'class' => 5],
            ['url_address' => $this->get_random_string(60), 'code' => '504', 'name' => 'ودائع بنكية', 'type' => 'asset', 'class' => 5],

            // الفصل 6 - المصاريف (Expenses)
            ['url_address' => $this->get_random_string(60), 'code' => '601', 'name' => 'المشتريات', 'type' => 'expense', 'class' => 6],
            ['url_address' => $this->get_random_string(60), 'code' => '602', 'name' => 'الرواتب', 'type' => 'expense', 'class' => 6],
            ['url_address' => $this->get_random_string(60), 'code' => '603', 'name' => 'الإيجارات', 'type' => 'expense', 'class' => 6],
            ['url_address' => $this->get_random_string(60), 'code' => '604', 'name' => 'فواتير الخدمات', 'type' => 'expense', 'class' => 6],
            ['url_address' => $this->get_random_string(60), 'code' => '605', 'name' => 'المصاريف الإدارية', 'type' => 'expense', 'class' => 6],
            ['url_address' => $this->get_random_string(60), 'code' => '606', 'name' => 'المصاريف التسويقية', 'type' => 'expense', 'class' => 6], // Marketing Expenses
            ['url_address' => $this->get_random_string(60), 'code' => '607', 'name' => 'المصاريف القانونية', 'type' => 'expense', 'class' => 6], // Legal Expenses
            ['url_address' => $this->get_random_string(60), 'code' => '608', 'name' => 'الضرائب', 'type' => 'expense', 'class' => 6], // Taxes
            ['url_address' => $this->get_random_string(60), 'code' => '609', 'name' => 'التأمينات', 'type' => 'expense', 'class' => 6], // Insurance Expenses
            ['url_address' => $this->get_random_string(60), 'code' => '610', 'name' => 'مصاريف الصيانة', 'type' => 'expense', 'class' => 6], // Maintenance Expenses
            ['url_address' => $this->get_random_string(60), 'code' => '611', 'name' => 'مصاريف السفر', 'type' => 'expense', 'class' => 6], // Travel Expenses
            ['url_address' => $this->get_random_string(60), 'code' => '612', 'name' => 'مصاريف التدريب', 'type' => 'expense', 'class' => 6], // Training Expenses
            ['url_address' => $this->get_random_string(60), 'code' => '613', 'name' => 'مصاريف الإنترنت والاتصالات', 'type' => 'expense', 'class' => 6], // Internet and Communication Expenses
            ['url_address' => $this->get_random_string(60), 'code' => '614', 'name' => 'مصاريف النقل', 'type' => 'expense', 'class' => 6], // Transport Expenses
            ['url_address' => $this->get_random_string(60), 'code' => '615', 'name' => 'مصاريف الطاقة', 'type' => 'expense', 'class' => 6], // Energy Expenses
            ['url_address' => $this->get_random_string(60), 'code' => '616', 'name' => 'مصاريف البحث والتطوير', 'type' => 'expense', 'class' => 6], // Research and Development Expenses
            ['url_address' => $this->get_random_string(60), 'code' => '617', 'name' => 'مصاريف الخدمات العامة', 'type' => 'expense', 'class' => 6], // Public Services Expenses
            ['url_address' => $this->get_random_string(60), 'code' => '618', 'name' => 'مصاريف الطعام والشراب', 'type' => 'expense', 'class' => 6], // Food and Beverage Expenses

            // الفصل 7 - الإيرادات (Income)
            ['url_address' => $this->get_random_string(60), 'code' => '701', 'name' => 'المبيعات', 'type' => 'income', 'class' => 7],
            ['url_address' => $this->get_random_string(60), 'code' => '702', 'name' => 'خدمات الأداء', 'type' => 'income', 'class' => 7],
            ['url_address' => $this->get_random_string(60), 'code' => '703', 'name' => 'المنتجات المالية', 'type' => 'income', 'class' => 7],
            ['url_address' => $this->get_random_string(60), 'code' => '704', 'name' => 'الإيرادات من الاستثمارات', 'type' => 'income', 'class' => 7],
            ['url_address' => $this->get_random_string(60), 'code' => '705', 'name' => 'الإيرادات من الفوائد', 'type' => 'income', 'class' => 7],
            ['url_address' => $this->get_random_string(60), 'code' => '706', 'name' => 'الإيرادات من الإيجارات', 'type' => 'income', 'class' => 7], // Rental Income
            ['url_address' => $this->get_random_string(60), 'code' => '707', 'name' => 'الإيرادات من العمولات', 'type' => 'income', 'class' => 7], // Commission Income
            ['url_address' => $this->get_random_string(60), 'code' => '708', 'name' => 'الإيرادات من الأرباح الرأسمالية', 'type' => 'income', 'class' => 7], // Capital Gains Income
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
