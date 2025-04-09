<?php

return [
    // General
    'general' => [
        'daterange' => 'Report : :start ~ :end',
        'empty' => 'No data available in table',
        'subtotal' => 'Total :',
    ],

    // Tax 
    'tax' => [
        'overall_title' => 'Overall (Input - Output - Expense)',
        'overall_value' => 'Output Tax - Input Tax - Expense Tax : :tax_diff SAR',
        'input_title' => 'Input Tax (Purchase)',
        'output_title' => 'Output Tax (Sales)',
        'expense_title' => 'Expense Tax',

        'th_date' => 'Date',
        'th_ref' => 'Reference No.',
        'th_supplier' => 'Supplier',
        'th_customer' => 'Customer',
        'th_taxnum' => 'Tax Number',
        'th_amount' => 'Total Amount',
        'th_payment_method' => 'Payment Method',
        'th_discount' => 'Discount',              
    ],

    // Activity Log
    'activity' => [
        'th_date' => 'Date',
        'th_subject' => 'Subject Type',
        'th_action' => 'Action',
        'th_by' => 'By',
        'th_note' => 'Note',    
    ],

    // Customer Group
    'cg' => [
        'th_cg' => 'Customer Group',
        'th_total' => 'Total Sale',
    ],

    // Customer & Supplier
    'customer_supplier' => [
        'th_contact' => 'Contact',
        'th_tpurchase' => 'Total Purchase',
        'th_tpurchase_return' => 'Total Purchase Return',
        'th_tsale' => 'Total Sale',
        'th_tsell_return' => 'Total Sell Return',
        'th_opening_balance' => 'Opening Balance Due',
        'th_due_amount' => 'Due Amount',
    ],

    // Expense 
    'expense' => [
        'th_categories' => 'Expense Categories',
        'th_total' => 'Total Expense',
    ],
    
    // Items
    'items' => [
        'th_product' => 'Product',
        'th_sku' => 'SKU',
        'th_description' => 'Description',
        'th_purchase_date' => 'Purchase Date',
        'th_purchase' => 'Purchase',
        'th_lot_number' => 'Lot Number',
        'th_supplier' => 'Supplier',
        'th_purchase_price' => 'Purchase Price',
    
        'th_sell_date' => 'Sell Date',
        'th_sale' => 'Sale',
        'th_customer' => 'Customer',
        'th_location' => 'Location',
        'th_sell_quantity' => 'Sell Quantity',
        'th_selling_price' => 'Selling Price',
        'th_subtotal' => 'Subtotal',
    
        'tf_purchase_price' => 'Purchase Price',
        'tf_quantity' => 'Quantity',
        'tf_selling_price' => 'Selling Price',
        'tf_subtotal' => 'Subtotal',
    ],

    // Product Purchase
    'product_purchase' => [
        'th_product' => 'Product',
        'th_sku' => 'SKU',
        'th_supplier' => 'Supplier',
        'th_ref_no' => 'Ref. No',
        'th_date' => 'Date',

        'th_quantity' => 'Quantity',
        'th_tunit_adjusted' => 'Total Unit Adjusted',
        'th_unit_purchase_price' => 'Unit Purchase Price',
        
        'tf_unit_purchase_price' => 'Unit Purchase Price',
        
        'subtotal' => 'Subtotal',
    ],

    // Product Sell
    'product_sell' => [
        'th_product' => 'Product',
        'th_sku' => 'SKU',
        'th_customer_name' => 'Customer Name',
        'th_contact_id' => 'Contact ID',
        'th_invoice_no' => 'Invoice No.',
        'th_date' => 'Date',

        'th_unit_price' => 'Unit Price',
        'th_discount' => 'Discount',
        'th_tax' => 'Tax',
        'th_price_inctax' => 'Price (Inc. Tax)',
        'th_total' => 'Total',
        'th_payment_method' => 'Payment Method',

        'subtotal' => 'Subtotal',
    ],

    // Purchase & Sale
    'purchase_n_sale' => [
        'purchase' => 'Purchase',
        'sales' => 'Sales',
        'total_purchase' => 'Total Purchase',
        'total_sales' => 'Total Sales',
        'purchase_inc_tax' => 'Total Purchase Return Including Tax :',
        'sale_inc_tax' => 'Total Sell Return Including Tax :',
        'purchase_return_inc_tax' => 'Total Purchase Return Including Tax :',
        'sale_return_inc_tax' => 'Total Purchase Return Including Tax :',
        'purchase_due' => 'Purchase Due :',
        'sale_due' => 'Sale Due :',
        'overall' => 'Overall',
        'operation' => '(Sale - Sell Return) - (Purchase - Purchase Return)',
        'sale_purchase' => 'Sale - Purchase :',
        'due_amount' => 'Due Amount :',
    ],

    // Purchase Payment
    'purchase_payment' => [
        'th_ref_no' => 'Ref. No',
        'th_paid' => 'Paid on',
        'th_amount' => 'Amount',
        'th_supplier' => 'Supplier',
        'th_payment_method' => 'Payment Method',
    ],

    // Register
    'register' => [
        'th_open' => 'Open At',
        'th_close' => 'Closed At',
        'th_location' => 'Location',
        'th_user' => 'User',
        'th_card' => 'Total Card Slips',
        'th_cheques' => 'Cheques',
        'th_cash' => 'Cash',
        'th_bank' => 'Bank Transfers',
        'th_advance' => 'Advance Payments',

        'th_cust_payment1' => 'Cust Payment 1',
        'th_cust_payment2' => 'Cust Payment 2',
        'th_cust_payment3' => 'Cust Payment 3',
        'th_cust_payment4' => 'Cust Payment 4',
        'th_cust_payment5' => 'Cust Payment 5',
        'th_cust_payment6' => 'Cust Payment 6',
        'th_cust_payment7' => 'Cust Payment 7',
        'th_other_payment' => 'Other Payment',
        'th_total' => 'Total',

        'tf_card' => 'Total Card Slips',
        'tf_cheques' => 'Total Cheques',
        'tf_cash' => 'Total Cash',
        'tf_bank' => 'Total Bank Transfer',
        'tf_advance' => 'Total Advance Payment',
        'tf_cust_payment1' => 'Cust. Payment 1',

        'tf_cust_payment2' => 'Cust. Payment 2',
        'tf_cust_payment3' => 'Cust. Payment 3',
        'tf_cust_payment4' => 'Cust. Payment 4',
        'tf_cust_payment5' => 'Cust. Payment 5',
        'tf_cust_payment6' => 'Cust. Payment 6',
        'tf_cust_payment7' => 'Cust. Payment 7',

        'tf_subtotal' => 'Subtotal',
    ],

    // Sales Representative
    'sales_representative' => [
        'total_sale-return' => 'Total Sale - Total Sales Return :',
        'total_expense' => 'Total Expense : ',
        'summary' => 'Summary',
        'sales' => 'Sales',
        'expenses' => 'Expenses',
        'sell_due' => 'Sell Due',
        'sell_return_due' => 'Sell Return Due',
        
        'th_payment_status' => 'Payment Status',
        'th_amount' => 'Total Amount',
        'th_location' => 'Location',
        'th_date' => 'Date',

        'th_invoice_no' => 'Invoice No.',
        'th_cust_name' => 'Customer Name',
        'th_paid' => 'Total Paid',
        'th_remaining' => 'Total Remaining',

        'th_ref_no' => 'Reference No.',
        'th_expense_cat' => 'Expense Category',
        'th_expense_for' => 'Expense For',
        'th_expense_note' => 'Expense Note',
    ],

    // Stock
    'stock' => [
        'th_closing_by_purchase' => 'Closing stock (By purchase price)',
        'th_closing_by_sale' => 'Closing stock (By sale price)',
        'th_margin' => 'Profit Margin',

        'th_sku' => 'SKU',
        'th_product' => 'Product',
        'th_variations' => 'Variations',
        'th_cat' => 'Category',
        'th_location' => 'Location',
        'th_unit_selling_price' => 'Unit Selling Price',
        'th_current_stock' => 'Current Stock',
        'th_current_stock_by_purchase' => 'Current Stock (By Purchase Price)',
        'th_current_stock_by_sale' => 'Current Stock (By Sale Price)',

        'th_potential' => 'Potential Profit',
        'th_tunit_sold' => 'Total Unit Sold',
        'th_tunit_transfered' => 'Total Unit Transfered',
        'th_tunit_adjusted' => 'Total Unit Adjusted',
        'th_cust1' => 'Cust Field1',
        'th_cust2' => 'Cust Field2',
        'th_cust3' => 'Cust Field3',
        'th_cust4' => 'Cust Field4',
        'th_current_stock_manufacturing' => 'Current Stock (Manufacturing)',

        'bags' => 'Bags' 
    ],

    // Trending Product
    'trending_product' => [
        'th_product' => 'Product',
        'th_sku' => 'SKU',
        'th_unit' => 'Unit',
        'th_tunit_sold' => 'Total Unit Sold',
    ],

    // Stock Adjustment
    'stock_adjustment' => [
        'tbox_normal' => 'Total Normal',
        'tbox_abnormal' => 'Total Abnormal',
        'tbox_sa' => 'Total Stock Adjustment',
        'tbox_ar' => 'Total Amount Recovered',

        'sa' => 'Stock Adjustments',

        'th_date' => 'Date',
        'th_ref_no' => 'Ref. No',
        'th_location' => 'Location',
        'th_type' => 'Adjustment Type',
        'th_tamount' => 'Total Amount',
        'th_tamount_recovered' => 'Total Amount Recovered',
        'th_reason' => 'Reason',
        'th_added_by' => 'Added By',
    ],

    // Profit / Loss
    'profit_loss' => [
        'th_purchase' => 'Purchases',
        'th_sales' => 'Sales',

        'td_opening_by_pp' => 'Opening Stock <br> (By purchase price)',
        'td_closing_by_pp' => 'Closing Stock <br> (By purchase price)',
        'td_opening_by_sp' => 'Opening Stock <br> (By sale price)',
        'td_closing_by_sp' => 'Closing Stock <br> (By sale price)',
        'td_tpurchase_exc_tax' => 'Total Purchase <br> (Exc. tax, Discount)',
        'td_tsales_exc_tax' => 'Total Sales <br> (Exc. tax, Discount)',
        
        'td_tstock_adjustment' => 'Total Stock Adjustment',
        'td_tsell_shipping' => 'Total Sell Shipping Charge',
        'td_texpense' => 'Total Expense',
        'td_tsell_additional_expenses' => 'Total Sell additional expenses',
        'td_tpurchase_shipping' => 'Total Purchase Shipping Charge',
        'td_tstock_recovered' => 'Total Stock Recovered',
        'td_purchase_additional' => 'Purchase Additional Expenses',
        'td_tpurchase_return' => 'Total Purchase Return',
        'td_ttransfer_shipping' => 'Total Transfer Shipping Charge',
        'td_tsell_round_off' => 'Total Sell Round Off',
        'td_tsell_discount' => 'Total Sell Discount',
        'td_tcustomer_reward' => 'Total Customer Reward',
        'td_tsell_return' => 'Total Sell Return',

        'cogs' => 'COGS :',
        'cogs_desc' => 'Cost of Goods Sold = Starting inventory(opening stock) + purchases − ending inventory(closing stock)',
        'gross_profit' => 'Gross Profit :',
        'tsell-tpurchase_price' => '(Total sell price - Total purchase price)',
        'nett_profit' => 'Net Profit :',
        'desc' => 'Gross Profit + (Total sell shipping charge + Sell additional expenses + Total Stock Recovered + Total <br> Purchase discount + <br> Total sell round off ) <br> - ( Total Stock Adjustment + Total Expense + Total purchase shipping charge + Total transfer shipping charge <br> + Purchase <br> additional expenses + Total Sell discount + Total customer reward + Total Payroll + Total Production Cost )',
    ],
];