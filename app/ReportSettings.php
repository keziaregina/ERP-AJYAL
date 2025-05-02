<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportSettings extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'type', 'interval', 'business_id', 'attachment_lang'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected function getReportTypes()
    {
        switch($this->type){
            case 'profit_or_loss_report':
                return __('report_type.profit_or_loss_report');
            case 'purchase_n_sell_report':
                return __('report_type.purchase_n_sell_report');
            case 'contacts_report':
                return __('report_type.contacts_report');
            case 'customer_n_supplier_report':
                return __('report_type.customer_n_supplier_report');
            case 'customer_group_report':
                return __('report_type.customer_group_report');
            case 'stock_report':
                return __('report_type.stock_report');
            case 'stock_adjustment_report':
                return __('report_type.stock_adjustment_report');
            case 'tax_report':
                return __('report_type.tax_report');
            case 'trending_product_report':
                return __('report_type.trending_product_report');
            case 'items_report':
                return __('report_type.items_report');
            case 'product_purchase_report':
                return __('report_type.product_purchase_report');
            case 'product_sell_report':
                return __('report_type.product_sell_report');
            case 'purchase_payment_report':
                return __('report_type.purchase_payment_report');
            case 'sales_representative':
                return __('report_type.sales_representative');
            case 'register_report':
                return __('report_type.register_report');
            case 'expense_report':
                return __('report_type.expense_report');
            case 'activity_log':
                return __('report_type.activity_log');
            case 'overtime':
                return __('report_type.overtime');
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
                    'attachment_lang' => $reportSetting->attachment_lang,
                    'interval' => $reportSetting->getReportIntervals(),
                ];
            });
    }
}
