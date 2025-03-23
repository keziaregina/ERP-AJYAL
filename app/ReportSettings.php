<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportSettings extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'type', 'interval', 'business_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected function getReportTypes()
    {
        switch($this->type){
            case 'profit_or_loss_report':
                return 'Profit / Loss Report';
            case 'purchase_n_sell_report':
                return 'Purchase & Sell Report';
            case 'contacts_report':
                return 'Contacts Report';
            case 'customer_n_supplier_report':
                return 'Customer & Supplier Report';
            case 'customer_group_report':
                return 'Customer Group Report';
            case 'stock_report':
                return 'Stock Report';
            case 'stock_adjustment_report':
                return 'Stock Adjustment Report';
            case 'tax_report':
                return 'Tax Report';
            case 'trending_product_report':
                return 'Trending Product Report';
            case 'items_report':
                return 'Items Report';
            case 'product_purchase_report':
                return 'Product Purchase Report';
            case 'product_sell_report':
                return 'Product Sell Report';
            case 'purchase_payment_report':
                return 'Purchase Payment Report';
            case 'sales_representative':
                return 'Sales Representative Report';
            case 'register_report':
                return 'Register Report';
            case 'expense_report':
                return 'Expense Report';
            case 'activity_log':
                return 'Activity Log';
        }
    }

    protected function getReportIntervals()
    {
        switch($this->interval){
            case 'daily':
                return __('report_settings.daily');
            case 'weekly':
                return __('report_settings.weekly');
            case 'monthly':
                return __('report_settings.monthly');
            case 'yearly':
                return __('report_settings.yearly');
            default:
                return $this->interval;
        }
    }

    public static function getUsers($business_id)
    {
        return ReportSettings::with('user')
            ->where('business_id', $business_id)
            ->get()
            ->map(function ($reportSetting) {
                return [
                    'id' => $reportSetting->id,
                    'user_name' => $reportSetting->user->first_name,
                    'type' => $reportSetting->getReportTypes(),
                    'interval' => $reportSetting->getReportIntervals(),
                ];
            });
    }
}
