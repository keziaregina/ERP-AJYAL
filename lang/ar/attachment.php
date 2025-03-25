<?php

return [
    // General
    'general' => [
        'daterange' => 'تقرير : :start ~ :end',
        'empty' => 'لا توجد بيانات متاحة في الجدول',
        'subtotal' => 'الإجمالي :',
    ],

    // Tax
    'tax' => [
        'overall_title' => 'الإجمالي (المدخلات - المخرجات - النفقات)',
        'overall_value' => 'ضريبة المخرجات - ضريبة المدخلات - ضريبة النفقات : :tax_diff ريال سعودي',
        'input_title' => 'ضريبة المدخلات (المشتريات)',
        'output_title' => 'ضريبة المخرجات (المبيعات)',
        'expense_title' => 'ضريبة النفقات',

        'th_date' => 'التاريخ',
        'th_ref' => 'رقم المرجع',
        'th_supplier' => 'المورد',
        'th_customer' => 'العميل',
        'th_taxnum' => 'الرقم الضريبي',
        'th_amount' => 'المبلغ الإجمالي',
        'th_payment_method' => 'طريقة الدفع',
        'th_discount' => 'الخصم',
    ],

    // Activity 
    'activity' => [
        'th_date' => 'التاريخ',
        'th_subject' => 'نوع الموضوع',
        'th_action' => 'الإجراء',
        'th_by' => 'بواسطة',
        'th_note' => 'ملاحظة',
    ],

    // Customer Group
    'cg' => [
        'th_cg' => 'مجموعة العملاء',
        'th_total' => 'إجمالي المبيعات',
    ],

    // Customer & Supplier
    'customer_supplier' => [
        'th_contact' => 'الاتصال',
        'th_tpurchase' => 'إجمالي الشراء',
        'th_tpurchase_return' => 'إجمالي المرتجع من الشراء',
        'th_tsale' => 'إجمالي المبيعات',
        'th_tsell_return' => 'إجمالي المرتجع من المبيعات',
        'th_opening_balance' => 'الرصيد الافتتاحي المستحق',
        'th_due_amount' => 'المبلغ المستحق',

        'subtotal' => 'الإجمالي :'
    ],

    // Expense 
    'expense' => [
        'th_categories' => 'فئات المصروفات',
        'th_total' => 'إجمالي المصروفات',

        'subtotal' => 'الإجمالي :'
    ],

    // Items
    'items' => [
        'th_product' => 'المنتج',
        'th_sku' => 'رمز SKU',
        'th_purchase_date' => 'تاريخ الشراء',
        'th_purchase' => 'الشراء',
        'th_supplier' => 'المورد',
        'th_purchase_price' => 'سعر الشراء',

        'th_sell_date' => 'تاريخ البيع',
        'th_sale' => 'البيع',
        'th_customer' => 'العميل',
        'th_location' => 'الموقع',
        'th_sell_quantity' => 'كمية البيع',
        'th_selling_price' => 'سعر البيع',
        'th_subtotal' => 'المجموع الفرعي',

        'tf_purchase_price' => 'سعر الشراء',
        'tf_quantity' => 'الكمية',
        'tf_selling_price' => 'سعر البيع',
        'tf_subtotal' => 'المجموع الفرعي',
    ],

    // Product Purchase
    'product_purchase' => [
        'th_product' => 'المنتج',
        'th_sku' => 'رمز SKU',
        'th_supplier' => 'المورد',
        'th_ref_no' => 'رقم المرجع',
        'th_date' => 'التاريخ',

        'th_quantity' => 'الكمية',
        'th_tunit_adjusted' => 'إجمالي الوحدة المعدلة',
        'th_unit_purchase_price' => 'سعر الشراء للوحدة',

        'tf_unit_purchase_price' => 'سعر الشراء للوحدة',

        'subtotal' => 'المجموع الفرعي',
    ],

    // Product Sell
    'product_sell' => [
        'th_product' => 'المنتج',
        'th_sku' => 'رمز SKU',
        'th_customer_name' => 'اسم العميل',
        'th_contact_id' => 'معرف الاتصال',
        'th_invoice_no' => 'رقم الفاتورة',
        'th_date' => 'التاريخ',

        'th_unit_price' => 'سعر الوحدة',
        'th_discount' => 'الخصم',
        'th_tax' => 'الضريبة',
        'th_price_inctax' => 'السعر (شامل الضريبة)',
        'th_total' => 'الإجمالي',
        'th_payment_method' => 'طريقة الدفع',

        'subtotal' => 'المجموع الفرعي',
    ],

    // Purchase & Sale
    'purchase_n_sale' => [
        'purchase' => 'الشراء',
        'sales' => 'المبيعات',
        'total_purchase' => 'إجمالي الشراء',
        'total_sales' => 'إجمالي المبيعات',
        'purchase_inc_tax' => 'إجمالي المرتجع من الشراء شامل الضريبة :',
        'sale_inc_tax' => 'إجمالي المرتجع من البيع شامل الضريبة :',
        'purchase_return_inc_tax' => 'إجمالي المرتجع من الشراء شامل الضريبة :',
        'sale_return_inc_tax' => 'إجمالي المرتجع من البيع شامل الضريبة :',
        'purchase_due' => 'المبلغ المستحق للشراء :',
        'sale_due' => 'المبلغ المستحق للبيع :',
        'overall' => 'الإجمالي',
        'operation' => '(المبيعات - المرتجع من البيع) - (الشراء - المرتجع من الشراء)',
        'sale_purchase' => 'المبيعات - الشراء :',
        'due_amount' => 'المبلغ المستحق :',
    ],

    // Purchase Payment
    'purchase_payment' => [
        'th_ref_no' => 'رقم المرجع',
        'th_paid' => 'تم الدفع في',
        'th_amount' => 'المبلغ',
        'th_supplier' => 'المورد',
        'th_payment_method' => 'طريقة الدفع',
    ],

    // Register
    'register' => [
        'th_open' => 'فتح عند',
        'th_close' => 'إغلاق عند',
        'th_location' => 'الموقع',
        'th_user' => 'المستخدم',
        'th_card' => 'إجمالي إيصالات البطاقة',
        'th_cheques' => 'الشيكات',
        'th_cash' => 'النقد',
        'th_bank' => 'التحويلات المصرفية',
        'th_advance' => 'المدفوعات المقدمة',

        'th_cust_payment1' => 'دفع العميل 1',
        'th_cust_payment2' => 'دفع العميل 2',
        'th_cust_payment3' => 'دفع العميل 3',
        'th_cust_payment4' => 'دفع العميل 4',
        'th_cust_payment5' => 'دفع العميل 5',
        'th_cust_payment6' => 'دفع العميل 6',
        'th_cust_payment7' => 'دفع العميل 7',
        'th_other_payment' => 'دفع آخر',
        'th_total' => 'الإجمالي',

        'tf_card' => 'إجمالي إيصالات البطاقة',
        'tf_cheques' => 'إجمالي الشيكات',
        'tf_cash' => 'إجمالي النقد',
        'tf_bank' => 'إجمالي التحويلات المصرفية',
        'tf_advance' => 'إجمالي المدفوعات المقدمة',
        'tf_cust_payment1' => 'دفع العميل 1',

        'tf_cust_payment2' => 'دفع العميل 2',
        'tf_cust_payment3' => 'دفع العميل 3',
        'tf_cust_payment4' => 'دفع العميل 4',
        'tf_cust_payment5' => 'دفع العميل 5',
        'tf_cust_payment6' => 'دفع العميل 6',
        'tf_cust_payment7' => 'دفع العميل 7',

        'tf_subtotal' => 'المجموع الفرعي',
    ],

    // Sales Representative
    'sales_representative' => [
        'total_sale-return' => 'إجمالي المبيعات - إجمالي مرتجع المبيعات :',
        'total_expense' => 'إجمالي النفقات :',
        'summary' => 'ملخص',
        'sales' => 'المبيعات',
        'expenses' => 'النفقات',
        'sell_due' => 'المبلغ المستحق للمبيعات',
        'sell_return_due' => 'المبلغ المستحق لمرتجع المبيعات',

        'th_payment_status' => 'حالة الدفع',
        'th_amount' => 'إجمالي المبلغ',
        'th_location' => 'الموقع',
        'th_date' => 'التاريخ',

        'th_invoice_no' => 'رقم الفاتورة',
        'th_cust_name' => 'اسم العميل',
        'th_paid' => 'إجمالي المدفوع',
        'th_remaining' => 'إجمالي المتبقي',

        'th_ref_no' => 'رقم المرجع',
        'th_expense_cat' => 'فئة النفقات',
        'th_expense_for' => 'النفقات من أجل',
        'th_expense_note' => 'ملاحظة النفقات',
    ],

    // Stock
    'stock' => [
        'th_closing_by_purchase' => 'المخزون الختامي (بسعر الشراء)',
        'th_closing_by_sale' => 'المخزون الختامي (بسعر البيع)',
        'th_margin' => 'هامش الربح',

        'th_sku' => 'رمز SKU',
        'th_product' => 'المنتج',
        'th_variations' => 'التباينات',
        'th_cat' => 'الفئة',
        'th_location' => 'الموقع',
        'th_unit_selling_price' => 'سعر البيع للوحدة',
        'th_current_stock' => 'المخزون الحالي',
        'th_current_stock_by_purchase' => 'المخزون الحالي (بسعر الشراء)',
        'th_current_stock_by_sale' => 'المخزون الحالي (بسعر البيع)',

        'th_potential' => 'الربح المحتمل',
        'th_tunit_sold' => 'إجمالي الوحدات المباعة',
        'th_tunit_transfered' => 'إجمالي الوحدات المحولة',
        'th_tunit_adjusted' => 'إجمالي الوحدات المعدلة',
        'th_cust1' => 'حقل مخصص 1',
        'th_cust2' => 'حقل مخصص 2',
        'th_cust3' => 'حقل مخصص 3',
        'th_cust4' => 'حقل مخصص 4',
        'th_current_stock_manufacturing' => 'المخزون الحالي (التصنيع)',

        'bags' => 'أكياس'
    ],

    // Trending Product
    'trending_product' => [
        'th_product' => 'المنتج',
        'th_sku' => 'رمز SKU',
        'th_unit' => 'الوحدة',
        'th_tunit_sold' => 'إجمالي الوحدات المباعة',
    ],

    // Stock Adjustment
    'stock_adjustment' => [
        'tbox_normal' => 'الإجمالي العادي',
        'tbox_abnormal' => 'الإجمالي غير العادي',
        'tbox_sa' => 'إجمالي تعديل المخزون',
        'tbox_ar' => 'إجمالي المبلغ المسترد',

        'sa' => 'تعديلات المخزون',

        'th_date' => 'التاريخ',
        'th_ref_no' => 'رقم المرجع',
        'th_location' => 'الموقع',
        'th_type' => 'نوع التعديل',
        'th_tamount' => 'إجمالي المبلغ',
        'th_tamount_recovered' => 'إجمالي المبلغ المسترد',
        'th_reason' => 'السبب',
        'th_added_by' => 'أضيف بواسطة',
    ],

    // Profit / Loss
    'profit_loss' => [
        'th_purchase' => 'المشتريات',
        'th_sales' => 'المبيعات',

        'td_opening_by_pp' => 'المخزون الافتتاحي <br> (بسعر الشراء)',
        'td_closing_by_pp' => 'المخزون الختامي <br> (بسعر الشراء)',
        'td_opening_by_sp' => 'المخزون الافتتاحي <br> (بسعر البيع)',
        'td_closing_by_sp' => 'المخزون الختامي <br> (بسعر البيع)',
        'td_tpurchase_exc_tax' => 'إجمالي المشتريات <br> (باستثناء الضريبة والخصم)',
        'td_tsales_exc_tax' => 'إجمالي المبيعات <br> (باستثناء الضريبة والخصم)',

        'td_tstock_adjustment' => 'إجمالي تعديل المخزون',
        'td_tsell_shipping' => 'إجمالي رسوم شحن المبيعات',
        'td_texpense' => 'إجمالي المصاريف',
        'td_tsell_additional_expenses' => 'إجمالي المصاريف الإضافية للمبيعات',
        'td_tpurchase_shipping' => 'إجمالي رسوم شحن المشتريات',
        'td_tstock_recovered' => 'إجمالي المخزون المسترد',
        'td_purchase_additional' => 'المصاريف الإضافية للمشتريات',
        'td_tpurchase_return' => 'إجمالي مرتجعات المشتريات',
        'td_ttransfer_shipping' => 'إجمالي رسوم شحن التحويل',
        'td_tsell_round_off' => 'إجمالي تقريب المبيعات',
        'td_tsell_discount' => 'إجمالي خصم المبيعات',
        'td_tcustomer_reward' => 'إجمالي مكافآت العملاء',
        'td_tsell_return' => 'إجمالي مرتجعات المبيعات',

        'cogs' => 'تكلفة البضائع المباعة :',
        'cogs_desc' => 'تكلفة البضائع المباعة = المخزون الافتتاحي + المشتريات - المخزون الختامي',
        'gross_profit' => 'إجمالي الربح :',
        'tsell-tpurchase_price' => '(إجمالي سعر البيع - إجمالي سعر الشراء)',
        'nett_profit' => 'صافي الربح :',
        'desc' => 'إجمالي الربح + (إجمالي رسوم شحن المبيعات + المصاريف الإضافية للمبيعات + إجمالي المخزون المسترد + إجمالي خصم المشتريات + <br> إجمالي تقريب المبيعات ) <br> - (إجمالي تعديل المخزون + إجمالي المصاريف + إجمالي رسوم شحن المشتريات + إجمالي رسوم شحن التحويل <br> + المصاريف الإضافية للمشتريات + إجمالي خصم المبيعات + إجمالي مكافآت العملاء + إجمالي الرواتب + إجمالي تكلفة الإنتاج)',
    ],

];
