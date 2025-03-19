<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Profit / Loss Report    </title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        header img {
            width: 100px;
        }

        header h1 {
            font-size: 15px;
        }

        .card {
            background-color: #f4f7fa;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            min-width: 300px;
            margin-bottom: 20px;
        }

        .card h3 {
            margin-top: 0;
            margin-bottom: 15px;
            color: #444;
            font-size: 18px;
        }

        .card table {
            width: 100%;
            border-collapse: collapse;
        }

        .card table td {
            padding: 6px 0;
            color: #333;
        }

        .label {
            font-weight: bold;
        }

        .info {
            color: #3498db;
            cursor: pointer;
            margin-left: 5px;
        }

        .overall {
            margin-top: 20px;
            background-color: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        .overall h4 {
            margin: 0 0 10px 0;
            font-size: 16px;
            color: #444;
        }
        .overall p{
            font-size: 14px;
            color: #444;
        }
        .overall h3{
            font-size: 16px;
            margin:0 0 0 0;
            color: #444;
        }

        .negative {
            color: #e74c3c;
            /* font-weight: bold; */
        }
        
        @font-face {
    font-family: 'Amiri';
    src: url("{{ asset('fonts/Amiri-Regular.ttf') }}") format("truetype");
}

.arabic {
    direction: rtl;
    text-align: right;
    font-family: 'Amiri', sans-serif;
    font-weight: normal;
}

    </style>
</head>


<body>
    <header>
        <img src="{{ $logo }}" alt="logo">
        <h1>Ajyal Al - Madina</h1>
        <span class="arabic">{{ env('APP_TITLE') }}</span>
    </header>
    <main>
        <div class="container">
            <h3 style="text-center">
                Report : {{ $dates['start_date'] }} ~ {{ $dates['end_date'] }}
            </h3>
            <div class="card">
                <h3>Purchases</h3>
                <table>
                    <tr>
                        <td class="label">
                            Opening Stock
                            <div>
                                (By purchase price):
                            </div>
                        </td>

                        <td>{{ $report['opening_stock']}}<span class="arabic">{{ $currency }}</span></td>
                    </tr>
                    <tr>
                        <td class="label">
                            Opening Stock
                            <div>
                                (By sale price):
                            </div>
                        </td>
                        <td>
                            @if(isset($report['opening_stock_by_sp']))
                                <span class="display_currency" data-currency_symbol="true">{{ $report['opening_stock_by_sp']? $report['opening_stock_by_sp'] : 0 }}</span>
                            @else
                                 <span id="opening_stock_by_sp"><i class="fa fa-sync fa-spin fa-fw "></i></span>
                            @endif<span class="arabic">0</span><span class="arabic">{{ $currency }}</span></td>
                    </tr>
                    <tr>
                        <td class="label">
                            Total Purchase:
                            <div>
                                (Exc. tax, Discount)	
                            </div>
                        </td>

                        <td>{{ $report['total_purchase']}}<span class="arabic">{{ $currency }}</span></td>
                    </tr>
                    <tr>
                        <td class="label">Opening Stock
                            <div>
                                (By sale price):
                            </div>
                        </td>
                        <td> @if(isset($report['opening_stock_by_sp']))
                            <span class="display_currency" data-currency_symbol="true">{{ $report['opening_stock_by_sp']? $report['opening_stock_by_sp'] : 0 }}</span>
                        @else
                             <span id="opening_stock_by_sp"><i class="fa fa-sync fa-spin fa-fw "></i></span>
                        @endif<span class="arabic">0</span><span class="arabic">{{ $currency }}</span></td>
                    </tr>
                    <tr>
                        <td class="label">Total purchase:
                            <div>
                                (Exc. tax, Discount)
                            </div>
                        </td>
                        <td>{{ $report['total_purchase']}}<span class="arabic">{{ $currency }}</span></td>
                    </tr>
                    <tr>
                        <td class="label">Total Stock Adjustment: </td>
                        <td>{{ $report['total_adjustment']}}<span class="arabic">{{ $currency }}</span></td>
                    </tr>
                    <tr>
                        <td class="label">Total Expense: </td>
                        <td>{{ $report['total_expense']}}<span class="arabic">{{ $currency }}</span></td>
                    </tr>
                    <tr>
                        <td class="label">Total purchase shipping charge: </td>
                        <td>{{ $report['total_purchase_shipping_charge']}}<span class="arabic">{{ $currency }}</span></td>
                    </tr>
                    <tr>
                        <td class="label">Purchase additional expenses:  </td>
                        <td>{{ $report['total_purchase_additional_expense']}}<span class="arabic">{{ $currency }}</span></td>
                    </tr>
                    <tr>
                        <td class="label">Total transfer shipping charge:  </td>
                        <td>{{ $report['total_transfer_shipping_charges']}}<span class="arabic">{{ $currency }}</span></td>
                    </tr>
                    <tr>
                        <td class="label">Total Sell discount:   </td>
                        <td>{{ $report['total_sell_discount']}}<span class="arabic">{{ $currency }}</span></td>
                    </tr>
                    <tr>
                        <td class="label">Total customer reward:  </td>
                        <td>{{ $report['total_reward_amount']}}<span class="arabic">{{ $currency }}</span></td>
                    </tr>
                    <tr>
                        <td class="label">Total sell return:  </td>
                        <td>{{ $report['total_sell_return']}}<span class="arabic">{{ $currency }}</span></td>
                    </tr>
                    {{-- <tr>
                        <td class="label">Total Payroll: </td>
                        <td>{{ $report['total_payroll']? $data['total_payroll'] : 0 }}<span class="arabic">{{ $currency }}</span></td>
                    </tr>
                    <tr>
                        <td class="label">Total Production Cost:  </td>
                        <td>{{ $report['total_production_cost']? $data['total_production_cost'] : 0 }}<span class="arabic">{{ $currency }}</span></td>
                    </tr> --}}
                     @foreach($report['left_side_module_data'] as $module_data)
                        <tr>
                            <td class="label">{{ $module_data['label'] }}:</td>
                            <td>
                                <span class="display_currency" data-currency_symbol="true">{{ $module_data['value'] }}<span class="arabic">{{ $currency }}</span></span>
                            </td>
                        </tr>
                    @endforeach
                    
                 
                </table>
            </div>
            <div class="card">
                {{-- <h3>Sales</h3> --}}
                <table>
                    <tr>
                        <td class="label">Closing stock
                            <div>
                                (By sale price):
                            </div>
                        </td>
                        <td>{{ $data['closing_stock_by_sp']? $data['closing_stock_by_sp'] : 0 }}<span class="arabic">{{ $currency }}</span></td>
                    </tr>
                    <tr>
                        <td class="label">Closing stock
                            <div>
                                (By sale price):
                            </div>
                        </td>
                        <td>{{ $data['closing_stock']? $data['closing_stock'] : 0 }}<span class="arabic">{{ $currency }}</span></td>
                    </tr>
                    <tr>
                        <td class="label">Total Sales:
                            <div>
                                (Exc. tax, Discount)
                            </div>
                        </td>
                        <td>
                            @if(count($report['total_sell_by_subtype']) > 1)
                            <ul>
                                @foreach($report['total_sell_by_subtype'] as $sell)
                                    <li>
                                        <span class="display_currency" data-currency_symbol="true">
                                            {{$sell->total_before_tax}}    
                                        </span>
                                        @if(!empty($sell->sub_type))
                                            &nbsp;<small class="text-muted">({{ucfirst($sell->sub_type)}})</small>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                            @endif

                            <span class="arabic">{{ $currency }}</span></td>
                    </tr>
                    <tr>
                        <td class="label">
                            Total sell shipping charge
                        </td>
                        <td>{{ $report['total_sell_shipping_charge']? $report['total_sell_shipping_charge'] : 0 }}<span class="arabic">{{ $currency }}</span></td>
                    </tr>
                    <tr>
                        <td class="label">
                            Sell additional expenses
                        </td>
                        <td>{{ $report['total_sell_additional_expense'] }}<span class="arabic">{{ $currency }}</span></td>
                    </tr>
                    <tr>
                        <td class="label">
                            Total Stock Recovered:
                        </td>
                        <td>{{ $report['total_recovered']}}<span class="arabic">{{ $currency }}</span></td>
                    </tr>
                    <tr>
                        <td class="label">
                            Total Purchase Return:
                        </td>
                        <td>{{ $report['total_purchase_return']}}<span class="arabic">{{ $currency }}</span></td>
                    </tr>
                    <tr>
                        <td class="label">
                            Total Purchase discount:
                        </td>
                        <td>{{ $report['total_purchase_discount']}}<span class="arabic">{{ $currency }}</span></td>
                    </tr>
                    <tr>
                        <td class="label">
                            Total sell round off:
                        </td>
                        <td>{{ $report['total_sell_round_off']}}<span class="arabic">{{ $currency }}</span></td>
                    </tr>
                    @foreach($report['right_side_module_data'] as $module_data)
                        <tr>
                            <td class="label">{{ $module_data['label'] }}:</td>
                            <td>
                                <span class="display_currency" data-currency_symbol="true">{{ $module_data['value'] }}<span class="arabic">{{ $currency }}</span></span>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
         </div>
          <div class="overall">
            <h3>COGS: {{ ($report['opening_stock'] - $report['total_purchase'] + $report['closing_stock']) }} <span class="arabic">{{ $currency }}</span></h3>
            <p>
                Cost of Goods Sold = Starting inventory(opening stock) + purchases − ending inventory(closing stock)
            </p>
           
            <h3>Gross Profit: {{$report['gross_profit']}} <span class="arabic">{{ $currency }}</span></h3>
            <p>
                (Total sell price - Total purchase price)
                @if(!empty($report['gross_profit_label']))
                    {{-- + {{$data['gross_profit_label']}} --}}
                    @foreach ($report['gross_profit_label'] as $val)
                        + {{$val}}
                    @endforeach
                @endif
            </p>
            <h3>Net Profit: {{$report['net_profit']}} <span class="arabic">{{ $currency }}</span></h3>
            <p>
                Gross Profit + (Total sell shipping charge + Sell additional expenses + Total Stock Recovered + Total Purchase discount +
Total sell round off )
- ( Total Stock Adjustment + Total Expense + Total purchase shipping charge + Total transfer shipping charge + Purchase
additional expenses + Total Sell discount + Total customer reward + Total Payroll + Total Production Cost )
            </p>
           {{-- <small class="help-block">@lang('lang_v1.gross_profit') + (@lang('lang_v1.total_sell_shipping_charge') + @lang('lang_v1.sell_additional_expense') + @lang('report.total_stock_recovered') + @lang('lang_v1.total_purchase_discount') + @lang('lang_v1.total_sell_round_off') 
            @foreach($report['right_side_module_data'] as $module_data)
                @if(!empty($module_data['add_to_net_profit']))
                    + {{$module_data['label']}} 
                @endif
            @endforeach
            ) <br> - ( @lang('report.total_stock_adjustment') + @lang('report.total_expense') + @lang('lang_v1.total_purchase_shipping_charge') + @lang('lang_v1.total_transfer_shipping_charge') + @lang('lang_v1.purchase_additional_expense') + @lang('lang_v1.total_sell_discount') + @lang('lang_v1.total_reward_amount') 
            @foreach($report['left_side_module_data'] as $module_data)
                @if(!empty($module_data['add_to_net_profit']))
                    + {{$module_data['label']}}
                @endif 
            @endforeach )</small> --}}
        </div>
    </main>
</body>

</html>
