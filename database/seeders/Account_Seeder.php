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
            ['code' => '101', 'name' => 'رأس المال الاجتماعي', 'type' => 'liability', 'class' => 1],
            ['code' => '102', 'name' => 'رأس المال الأولي', 'type' => 'liability', 'class' => 1],
            ['code' => '103', 'name' => 'قروض طويلة الأجل', 'type' => 'liability', 'class' => 1],
            ['code' => '104', 'name' => 'احتياطي رأس المال', 'type' => 'liability', 'class' => 1],
            ['code' => '105', 'name' => 'الأرباح المحتجزة', 'type' => 'liability', 'class' => 1],

            // الفصل 2 - الأصول الثابتة (Fixed Assets)
            ['code' => '201', 'name' => 'الأراضي', 'type' => 'asset', 'class' => 2],
            ['code' => '202', 'name' => 'المباني', 'type' => 'asset', 'class' => 2],
            ['code' => '203', 'name' => 'الآلات والأدوات', 'type' => 'asset', 'class' => 2],
            ['code' => '204', 'name' => 'مركبات', 'type' => 'asset', 'class' => 2],
            ['code' => '205', 'name' => 'معدات مكتبية', 'type' => 'asset', 'class' => 2],

            // الفصل 3 - المخزونات والعمل الجاري (Stocks and Work in Progress)
            ['code' => '301', 'name' => 'المواد الخام', 'type' => 'asset', 'class' => 3],
            ['code' => '302', 'name' => 'المنتجات الجارية', 'type' => 'asset', 'class' => 3],
            ['code' => '303', 'name' => 'السلع الجاهزة', 'type' => 'asset', 'class' => 3],

            // الفصل 4 - الحسابات المدينة والدائنة (Accounts Receivable and Payable)
            ['code' => '401', 'name' => 'العملاء', 'type' => 'asset', 'class' => 4],
            ['code' => '402', 'name' => 'الموردين', 'type' => 'liability', 'class' => 4],
            ['code' => '403', 'name' => 'الحسابات المدينة قصيرة الأجل', 'type' => 'asset', 'class' => 4],
            ['code' => '404', 'name' => 'الحسابات الدائنة قصيرة الأجل', 'type' => 'liability', 'class' => 4],

            // الفصل 5 - النقد والبنك (Cash and Bank)
            ['code' => '501', 'name' => 'الصندوق', 'type' => 'asset', 'class' => 5],
            ['code' => '502', 'name' => 'البنك', 'type' => 'asset', 'class' => 5],
            ['code' => '503', 'name' => 'حسابات جارية', 'type' => 'asset', 'class' => 5],
            ['code' => '504', 'name' => 'ودائع بنكية', 'type' => 'asset', 'class' => 5],

            // الفصل 6 - المصاريف (Expenses)
            ['code' => '601', 'name' => 'المشتريات', 'type' => 'expense', 'class' => 6],
            ['code' => '602', 'name' => 'الرواتب', 'type' => 'expense', 'class' => 6],
            ['code' => '603', 'name' => 'الإيجارات', 'type' => 'expense', 'class' => 6],
            ['code' => '604', 'name' => 'فواتير الخدمات', 'type' => 'expense', 'class' => 6],
            ['code' => '605', 'name' => 'المصاريف الإدارية', 'type' => 'expense', 'class' => 6],
            ['code' => '606', 'name' => 'المصاريف التسويقية', 'type' => 'expense', 'class' => 6], // Marketing Expenses
            ['code' => '607', 'name' => 'المصاريف القانونية', 'type' => 'expense', 'class' => 6], // Legal Expenses
            ['code' => '608', 'name' => 'الضرائب', 'type' => 'expense', 'class' => 6], // Taxes
            ['code' => '609', 'name' => 'التأمينات', 'type' => 'expense', 'class' => 6], // Insurance Expenses
            ['code' => '610', 'name' => 'مصاريف الصيانة', 'type' => 'expense', 'class' => 6], // Maintenance Expenses
            ['code' => '611', 'name' => 'مصاريف السفر', 'type' => 'expense', 'class' => 6], // Travel Expenses
            ['code' => '612', 'name' => 'مصاريف التدريب', 'type' => 'expense', 'class' => 6], // Training Expenses
            ['code' => '613', 'name' => 'مصاريف الإنترنت والاتصالات', 'type' => 'expense', 'class' => 6], // Internet and Communication Expenses
            ['code' => '614', 'name' => 'مصاريف النقل', 'type' => 'expense', 'class' => 6], // Transport Expenses
            ['code' => '615', 'name' => 'مصاريف الطاقة', 'type' => 'expense', 'class' => 6], // Energy Expenses
            ['code' => '616', 'name' => 'مصاريف البحث والتطوير', 'type' => 'expense', 'class' => 6], // Research and Development Expenses
            ['code' => '617', 'name' => 'مصاريف الخدمات العامة', 'type' => 'expense', 'class' => 6], // Public Services Expenses
            ['code' => '618', 'name' => 'مصاريف الطعام والشراب', 'type' => 'expense', 'class' => 6], // Food and Beverage Expenses

            // الفصل 7 - الإيرادات (Income)
            ['code' => '701', 'name' => 'المبيعات', 'type' => 'income', 'class' => 7],
            ['code' => '702', 'name' => 'خدمات الأداء', 'type' => 'income', 'class' => 7],
            ['code' => '703', 'name' => 'المنتجات المالية', 'type' => 'income', 'class' => 7],
            ['code' => '704', 'name' => 'الإيرادات من الاستثمارات', 'type' => 'income', 'class' => 7],
            ['code' => '705', 'name' => 'الإيرادات من الفوائد', 'type' => 'income', 'class' => 7],
            ['code' => '706', 'name' => 'الإيرادات من الإيجارات', 'type' => 'income', 'class' => 7], // Rental Income
            ['code' => '707', 'name' => 'الإيرادات من العمولات', 'type' => 'income', 'class' => 7], // Commission Income
            ['code' => '708', 'name' => 'الإيرادات من الأرباح الرأسمالية', 'type' => 'income', 'class' => 7], // Capital Gains Income
        ];

        DB::table('accounts')->insert($accounts);
    }
}
