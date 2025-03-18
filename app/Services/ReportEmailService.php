<?php

namespace App\Services;

use App\Contact;
use App\Mail\Reporting;
use App\Transaction;
use App\User;
use App\Utils\BusinessUtil;
use App\Utils\ProductUtil;
use App\Utils\TransactionUtil;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Models\Activity;

class ReportEmailService
{
    public $transactionUtil;
    public $productUtil;
    public $filename;
    public $businessUtil;
    public $logo;
    public function __construct(TransactionUtil $transactionUtil, ProductUtil $productUtil, BusinessUtil $businessUtil)
    {
        $this->transactionUtil = $transactionUtil;
        $this->productUtil = $productUtil;
        $this->businessUtil = $businessUtil;
        $this->logo = public_path('img/logo-small.png');
        // $this->filename = storage_path('app/public/pdf/report/Ajyal Al-Madina.pdf');
    }

    public function generateReportAttachment($data,$dates,$interval)
    {
        $report = null;
            
        $filename = "pdf/report/{$data->user_id}_{$data->type}_" . now()->format('Ymd_His') . ".pdf";
        $user = User::find($data->user_id);
        $directory = dirname($filename);

        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        switch ($data->type) {
            case 'purchase_n_sell_report':
                $type = 'purchase_n_sale';
                $data['report_type'] = 'Purchase & Sales Summary';
                $report = $this->getPurchaseSellReport($user, $dates['start_date'], $dates['end_date']);
                break;
            case 'contacts_report':
                $type = 'contact';
                $data['report_type'] = 'Contacts Summary';
                break;
            case 'tax_report':
                $type = 'tax';
                $data['report_type'] = 'Tax';
                break;
            case 'stock_report':
                $type = 'stock';
                $data['report_type'] = 'Stock';
                $report = $this->getStockValue($user, $dates['start_date'], $dates['end_date']);
                break;
            case 'trending_product_report':
                $type = 'trending_product';
                $data['report_type'] = 'Trending Product';
                break;
            case 'sales_representative':
                $type = 'sales_representative';
                break;
            case 'register_report':
                $type = 'register';
                $data['report_type'] = 'Register';
                $report = $this->getRegisterReport($user, $dates['start_date'], $dates['end_date']);
                break;
            case 'expense_report':
                $type = 'expense';
                $data['report_type'] = 'Expense';
                break;
            case 'profit_or_loss_report':
                $type = 'profit_or_loss';
                $data['report_type'] = 'Profit / Loss';
                $report = $this->getProfitOrLossReport($user, $dates['start_date'], $dates['end_date']);
                break;
            case 'activity_log':
                $type = 'activity_log';
                $data['report_type'] = 'Activity Log';
                $report = $this->getActivityLog($user, $dates['start_date'], $dates['end_date']);
                break;
            case 'customer_n_supplier_report':
                // FIXME: fix customer & supplier report arabic and logo
                // Log::info("CUSTOMER & SUPPLIER -------------------------------------------------->");
                $type = 'customer_n_supplier';
                $data['report_type'] = 'Customer & Supplier';
                $report = $this->getCustomerSupplierReport($user, $dates['start_date'], $dates['end_date']);
                break;
                
            case 'customer_group_report':
                // FIXME: fix customer group report arabic and logo
                $type = 'customer_group';
                $data['report_type'] = 'Customer Group';
                $report = $this->getCustomerGroupReport($user, $dates['start_date'], $dates['end_date']);
            default:
        }
        $view = 'report_settings/export/' . $type;
        $pdf = Pdf::setPaper('a4', 'landscape')
            ->loadView($view, [
                'data' => $data, 
                'logo' => $this->logo, 
                'user' => $user,
                'report' => $report,
                'dates' => $dates,
                'currency' => 'ر.س'
            ]);

            
        $data['interval'] = $interval;


        $file=Storage::disk('public')->put($filename, $pdf->output()); 

        Mail::to($user->email)
            ->send(new Reporting($data, $filename, $type));

        Storage::disk('public')->delete($filename);
        return $filename;
    }

    public function getActivityLog(User $user, $start_date = null, $end_date = null)
    {
        $business_id = $user->business_id;
        $activities = Activity::with(['subject'])
                                ->leftjoin('users as u', 'u.id', '=', 'activity_log.causer_id')
                                ->where('activity_log.business_id', $business_id)
                                ->select(
                                    'activity_log.*',
                                    DB::raw("CONCAT(COALESCE(u.surname, ''), ' ', COALESCE(u.first_name, ''), ' ', COALESCE(u.last_name, '')) as created_by")
                                )->whereDate('activity_log.created_at', '>=', $start_date)
                                ->whereDate('activity_log.created_at', '<=', $end_date)->get();

        if (! empty(request()->user_id)) {
            $activities->where('causer_id', request()->user_id);
        }

        $subject_type = request()->subject_type;
        if (! empty($subject_type)) {
            if ($subject_type == 'contact') {
                $activities->where('subject_type', \App\Contact::class);
            } elseif ($subject_type == 'user') {
                $activities->where('subject_type', \App\User::class);
            } elseif (in_array($subject_type, ['sell', 'purchase',
                'sales_order', 'purchase_order', 'sell_return', 'purchase_return', 'sell_transfer', 'expense', 'purchase_order', ])) {
                $activities->where('subject_type', \App\Transaction::class);
                $activities->whereHasMorph('subject', Transaction::class, function ($q) use ($subject_type) {
                    $q->where('type', $subject_type);
                });
            }
        }

        $sell_statuses = Transaction::sell_statuses();
        $sales_order_statuses = Transaction::sales_order_statuses(true);
        $purchase_statuses = $this->transactionUtil->orderStatuses();
        $shipping_statuses = $this->transactionUtil->shipping_statuses();

        $statuses = array_merge($sell_statuses, $sales_order_statuses, $purchase_statuses);
        
        $activities = $activities->map(function ($row) use ($statuses, $shipping_statuses) {
            $html = '';

            $subject_type = '';
            if ($row->subject_type == \App\Contact::class) {
                $subject_type = __('contact.contact');
            } elseif ($row->subject_type == \App\User::class) {
                $subject_type = __('report.user');
            } elseif ($row->subject_type == \App\Transaction::class && ! empty($row->subject->type)) {
                $subject_type = isset($transaction_types[$row->subject->type]) ? $transaction_types[$row->subject->type] : '';
            } elseif (($row->subject_type == \App\TransactionPayment::class)) {
                $subject_type = __('lang_v1.payment');
            }
    
            if (!empty($row->subject?->ref_no)) {
                $html .= __('purchase.ref_no') . ': ' . $row->subject->ref_no . '<br>';
            }
    
            if (!empty($row->subject?->invoice_no)) {
                $html .= __('sale.invoice_no') . ': ' . $row->subject->invoice_no . '<br>';
            }
    
            if ($row->subject_type === \App\Models\Transaction::class && in_array($row->subject?->type, ['sell', 'purchase'])) {
                $html .= view('sale_pos.partials.activity_row', [
                    'activity' => $row,
                    'statuses' => $statuses,
                    'shipping_statuses' => $shipping_statuses
                ])->render();
            } else {
                $update_note = $row->getExtraProperty('update_note');
                if (!empty($update_note) && !is_array($update_note)) {
                    $html .= $update_note;
                }
            }
    
            if ($row->description === 'contact_deleted') {
                $html .= $row->getExtraProperty('supplier_business_name') ?? '';
                $html .= '<br>';
            }
    
            if (!empty($row->getExtraProperty('name'))) {
                $html .= __('user.name') . ': ' . $row->getExtraProperty('name') . '<br>';
            }
    
            if (!empty($row->getExtraProperty('id'))) {
                $html .= 'ID: ' . $row->getExtraProperty('id') . '<br>';
            }
    
            if (!empty($row->getExtraProperty('invoice_no'))) {
                $html .= __('sale.invoice_no') . ': ' . $row->getExtraProperty('invoice_no') . '<br>';
            }
    
            if (!empty($row->getExtraProperty('ref_no'))) {
                $html .= __('purchase.ref_no') . ': ' . $row->getExtraProperty('ref_no');
            }

            $row['note'] = $html; 
            $row['subject_type'] = $subject_type;
            return $row;
        });
        return $activities;
    }


    public function getPurchaseSellReport(User $user, $start_date = null, $end_date = null)
    {
        $business_id = $user->business_id;
        $location_id = 10;

        $purchase_details = $this->transactionUtil->getPurchaseTotals
        (
            $business_id, 
            $start_date,
            $end_date,
            // $location_id
        );

        $sell_details = $this->transactionUtil->getSellTotals(
            $business_id,
            $start_date,
            $end_date,
            // $location_id
        );

        $transaction_types = [
            'purchase_return', 'sell_return',
        ];

        $transaction_totals = $this->transactionUtil->getTransactionTotals(
            $business_id,
            $transaction_types,
            $start_date,
            $end_date,
            $location_id
        );

        $total_purchase_return_inc_tax = $transaction_totals['total_purchase_return_inc_tax'];
        $total_sell_return_inc_tax = $transaction_totals['total_sell_return_inc_tax'];

        $difference = [
            'total' => $sell_details['total_sell_inc_tax'] - $total_sell_return_inc_tax - ($purchase_details['total_purchase_inc_tax'] - $total_purchase_return_inc_tax),
            'due' => $sell_details['invoice_due'] - $purchase_details['purchase_due'],
        ];
        return ['purchase' => $purchase_details,
            'sell' => $sell_details,
            'total_purchase_return' => $total_purchase_return_inc_tax,
            'total_sell_return' => $total_sell_return_inc_tax,
            'difference' => $difference,
        ];
    }

    public function getStockValue(User $user, $start_date = null, $end_date = null, $location_id = null)
    {
        $business_id = $user->business_id;
        $location_id = $location_id;
        $filters = request()->only(['category_id', 'sub_category_id', 'brand_id', 'unit_id']);

        $permitted_locations = $user->permitted_locations();
        //Get Closing stock
        $closing_stock_by_pp = $this->transactionUtil->getOpeningClosingStock(
            $business_id,
            $end_date,
            $location_id,
            false,
            false,
            $filters,
            $permitted_locations
        );
        $closing_stock_by_sp = $this->transactionUtil->getOpeningClosingStock(
            $business_id,
            $end_date,
            $location_id,
            false,
            true,
            $filters,
            $permitted_locations
        );
        $potential_profit = $closing_stock_by_sp - $closing_stock_by_pp;
        $profit_margin = empty($closing_stock_by_sp) ? 0 : ($potential_profit / $closing_stock_by_sp) * 100;

        
        // $for = request()->input('for') == 'view_product' ? 'view_product' : 'datatables';

        // $products = $this->productUtil->getProductStockDetails($business_id, $filters, $for);
        // // \Log::info($products);

        return [
            'closing_stock_by_pp' => $closing_stock_by_pp,
            'closing_stock_by_sp' => $closing_stock_by_sp,
            'potential_profit' => $potential_profit,
            'profit_margin' => $profit_margin,
        ];
    }

    public function getProfitOrLossReport(User $user, $start_date = null, $end_date = null)
    {
        $business_id = $user->business_id;
        $location_id = 10;
        $purchase_details = $this->transactionUtil->getPurchaseTotals($business_id, $start_date, $end_date, $location_id);
        $fy = $this->businessUtil->getCurrentFinancialYear($business_id);
        $user_id = $user->id;
        $permitted_locations = $user->permitted_locations();
        $data = $this->transactionUtil->getProfitLossDetails($business_id, $location_id, $start_date, $end_date, $user_id, $permitted_locations);
        return $data;
    }
    public function getRegisterReport(User $user, $start_date = null, $end_date = null, $location_id = null, $contact_id = null)
    {
        $business_id = $user->business_id;
        $start_date = $start_date;
        $end_date = $end_date;
        $location_id = $location_id;
        $contact_id = $contact_id;

        $permitted_locations = $user->permitted_locations();

        $registers = $this->transactionUtil->registerReportExport($business_id, $permitted_locations, $start_date, $end_date, $user->id);
        return $registers;
    }

    public function getCustomerSupplierReport(User $user, $start_date = null, $end_date = null)
    {
        Auth::login($user);

        $business_id = $user->business_id;
        // $location_id = 10;
        $contact_id = null;

        $contacts = Contact::where('contacts.business_id', $business_id)
        ->join('transactions AS t', 'contacts.id', '=', 't.contact_id')
        ->active()
        ->groupBy('contacts.id')
        ->select(
            DB::raw("SUM(IF(t.type = 'purchase', final_total, 0)) as total_purchase"),
            DB::raw("SUM(IF(t.type = 'purchase_return', final_total, 0)) as total_purchase_return"),
            DB::raw("SUM(IF(t.type = 'sell' AND t.status = 'final', final_total, 0)) as total_invoice"),
            DB::raw("SUM(IF(t.type = 'purchase', (SELECT SUM(amount) FROM transaction_payments WHERE transaction_payments.transaction_id=t.id), 0)) as purchase_paid"),
            DB::raw("SUM(IF(t.type = 'sell' AND t.status = 'final', (SELECT SUM(IF(is_return = 1,-1*amount,amount)) FROM transaction_payments WHERE transaction_payments.transaction_id=t.id), 0)) as invoice_received"),
            DB::raw("SUM(IF(t.type = 'sell_return', (SELECT SUM(amount) FROM transaction_payments WHERE transaction_payments.transaction_id=t.id), 0)) as sell_return_paid"),
            DB::raw("SUM(IF(t.type = 'purchase_return', (SELECT SUM(amount) FROM transaction_payments WHERE transaction_payments.transaction_id=t.id), 0)) as purchase_return_received"),
            DB::raw("SUM(IF(t.type = 'sell_return', final_total, 0)) as total_sell_return"),
            DB::raw("SUM(IF(t.type = 'opening_balance', final_total, 0)) as opening_balance"),
            DB::raw("SUM(IF(t.type = 'opening_balance', (SELECT SUM(IF(is_return = 1,-1*amount,amount)) FROM transaction_payments WHERE transaction_payments.transaction_id=t.id), 0)) as opening_balance_paid"),
            DB::raw("SUM(IF(t.type = 'ledger_discount' AND sub_type='sell_discount', final_total, 0)) as total_ledger_discount_sell"),
            DB::raw("SUM(IF(t.type = 'ledger_discount' AND sub_type='purchase_discount', final_total, 0)) as total_ledger_discount_purchase"),
            'contacts.supplier_business_name',
            'contacts.name',
            'contacts.id',
            'contacts.type as contact_type'
        );
        $permitted_locations = auth()->user()->permitted_locations();

        if ($permitted_locations != 'all') {
            $contacts->whereIn('t.location_id', $permitted_locations);
        }

        // if (! empty($request->input('customer_group_id'))) {
        //     $contacts->where('contacts.customer_group_id', $request->input('customer_group_id'));
        // }

        // if (! empty($request->input('location_id'))) {
        //     $contacts->where('t.location_id', $request->input('location_id'));
        // }

        // if (! empty($request->input('contact_id'))) {
        //     $contacts->where('t.contact_id', $request->input('contact_id'));
        // }

        // if (! empty($request->input('contact_type'))) {
        //     $contacts->whereIn('contacts.type', [$request->input('contact_type'), 'both']);
        // }

        // $start_date = $request->get('start_date');
        // $end_date = $request->get('end_date');
        if (! empty($start_date) && ! empty($end_date)) {
            $contacts->where('t.transaction_date', '>=', $start_date)
                ->where('t.transaction_date', '<=', $end_date);
        }



        $contacts = $contacts->get();

        foreach ($contacts as $row) {
            if (! empty($row->supplier_business_name)) {
                $row->name .= ', '.$row->supplier_business_name;
            }
            
            $total_ledger_discount_purchase = $row->total_ledger_discount_purchase ?? 0;
            $total_ledger_discount_sell = $total_ledger_discount_sell ?? 0;
            $due = ($row->total_invoice - $row->invoice_received - $total_ledger_discount_sell) - ($row->total_purchase - $row->purchase_paid - $total_ledger_discount_purchase) - ($row->total_sell_return - $row->sell_return_paid) + ($row->total_purchase_return - $row->purchase_return_received);

            if ($row->contact_type == 'supplier') {
                $due -= $row->opening_balance - $row->opening_balance_paid;
            } else {
                $due += $row->opening_balance - $row->opening_balance_paid;
            }

            $due_formatted = $this->transactionUtil->num_f($due, true);
        
            $row->due = $due_formatted;
        }

        Auth::logout();
        Log::info("CONTACT -------------------------------------------------->");
        Log::info(json_encode($contacts,JSON_PRETTY_PRINT));

        return $contacts;
    }

    public function getCustomerGroupReport(User $user, $start_date = null, $end_date = null)
    {
        $business_id = $user->business_id;
        // $location_id = 10;
        Auth::login($user);

        $contact_id = null;

        $query = Transaction::leftjoin('customer_groups AS CG', 'transactions.customer_group_id', '=', 'CG.id')
        ->where('transactions.business_id', $business_id)
        ->where('transactions.type', 'sell')
        ->where('transactions.status', 'final')
        ->groupBy('transactions.customer_group_id')
        ->select(DB::raw('SUM(final_total) as total_sell'), 'CG.name');

        // $group_id = $request->get('customer_group_id', null);
        $group_id = null;
        if (! empty($group_id)) {
        $query->where('transactions.customer_group_id', $group_id);
        }

        $permitted_locations = auth()->user()->permitted_locations();
        if ($permitted_locations != 'all') {
        $query->whereIn('transactions.location_id', $permitted_locations);
        }

        // $location_id = $request->get('location_id', null);
        // if (! empty($location_id)) {
        // $query->where('transactions.location_id', $location_id);
        // }

        // $start_date = $request->get('start_date');
        // $end_date = $request->get('end_date');

        if (! empty($start_date) && ! empty($end_date)) {
        $query->whereBetween(DB::raw('date(transaction_date)'), [$start_date, $end_date]);
        }

        $customer_group = $query->get();

        Auth::logout();
        Log::info("CUSTOMER GROUP -------------------------------------------------->");
        Log::info(json_encode($customer_group,JSON_PRETTY_PRINT));

        return $customer_group;

        
    }
}