<div class="modal-dialog modal-lg" role="document">
  	<div class="modal-content">
  		<div class="modal-header no-print">
	      	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	      		<span aria-hidden="true">&times;</span>
	      	</button>
	      	<h4 class="modal-title no-print">
	      		{!! __('essentials::lang.payroll_of_employee', ['employee' => $payroll->transaction_for->user_full_name, 'date' => $month_name . ' ' . $year]) !!}
	      	</h4>
	    </div>
	    <div class="modal-body">
	    	<div class="table-responsive">
		      	<table class="table table-bordered" id="payroll-view">
		      		<tr>
		      			<td colspan="4">
			      			@if(!empty(Session::get('business.logo')))
			                  <img src="{{ asset( 'uploads/business_logos/' . Session::get('business.logo', '1737635769_logo ajyal.jpg') ) }}" alt="Logo" style="width: auto; max-height: 50px; margin: auto;">
			                @endif
			                <div class="pull-right text-center">
			                	<strong class="font-23">
			                		{{Session::get('business.name') ?? ''}}
			                	</strong>
			                	<br>
			                	{!!Session::get('business.business_address') ?? ''!!}
			                </div>
			                <br>
			                <div style="text-align: center;padding-top: 40px;">
			                	@lang('essentials::lang.payslip_for_the_month', ['month' => $month_name, 'year' => $year])
			                </div>
		                </td>
		      		</tr>
		      		<tr>
		      			<td colspan="2" style="vertical-align:top;">
		      				<strong>@lang('essentials::lang.employee'):</strong> {{$payroll->transaction_for->user_full_name}}<br>
		      				<strong>@lang('essentials::lang.department'):</strong> {{$department->name ?? ''}}<br>
		      				<strong>@lang('essentials::lang.designation'):</strong> {{$designation->name ?? ''}}<br>
		      				<strong>@lang('lang_v1.primary_work_location'):</strong>
		      				@if(!empty($location)) {{$location->name}} @else {{__('report.all_locations')}} @endif<br>
		      				@if(!empty($payroll->transaction_for->id_proof_name) && !empty($payroll->transaction_for->id_proof_number))
		      					<strong>{{ucfirst($payroll->transaction_for->id_proof_name)}}:</strong> {{$payroll->transaction_for->id_proof_number}}<br>
		      				@endif
		      				<strong>@lang('lang_v1.tax_payer_id'):</strong> {{$bank_details['tax_payer_id'] ?? ''}}<br>
		      			</td>
		      			<td colspan="2" style="vertical-align:top;">
		      				<strong>@lang('lang_v1.bank_name'):</strong> {{$bank_details['bank_name'] ?? ''}}<br>
		      				<strong>@lang('lang_v1.branch'):</strong> {{$bank_details['branch'] ?? ''}}<br>
		      				<strong>@lang('lang_v1.bank_code'):</strong> {{$bank_details['bank_code'] ?? ''}}<br>
		      				<strong>@lang('lang_v1.account_holder_name'):</strong> {{$bank_details['account_holder_name'] ?? ''}}<br>
		      				<strong>@lang('lang_v1.bank_account_no'):</strong> {{$bank_details['account_number'] ?? ''}}<br>
		      			</td>
		      		</tr>
		      		<tr>
		      			<td><strong>@lang('essentials::lang.total_overtime_hours'):</strong> {{$total_overtime}}</td>
		      			<td><strong>@lang('essentials::lang.days_present'):</strong> {{$total_days_present}}</td>
		      			<td><strong>@lang('essentials::lang.days_absent'):</strong> {{$total_absent}}</td>
		      			<td><strong>@lang('essentials::lang.total_leaves'):</strong> {{$total_leaves}}</td>
		      		</tr>
		      		<tr class="bg-info">
		      			<th colspan="2" class="text-center">@lang('essentials::lang.earnings')</th>
		      			<th colspan="2" class="text-center">@lang('essentials::lang.deductions')</th>
		      		</tr>
		      		<tr>
		      			<th>@lang('essentials::lang.description')</th>
		      			<th>@lang('sale.amount')</th>
		      			<th>@lang('essentials::lang.description')</th>
		      			<th>@lang('sale.amount')</th>
		      		</tr>
		      		@php
		      			$max_rows = max(
		      				1 + (isset($allowances['allowance_names']) ? count($allowances['allowance_names']) : 0),
		      				(isset($deductions['deduction_names']) ? count($deductions['deduction_names']) : 0)
		      			);
		      			$row = 0;
		      			$total_earnings = $payroll->essentials_duration * $payroll->essentials_amount_per_unit_duration;
		      			$total_deduction = 0;
		      		@endphp
		      		@for($i = 0; $i < $max_rows; $i++)
		      			<tr>
		      				@if($i == 0)
		      					<td>@lang('essentials::lang.salary')</td>
		      					<td>
		      						<span class="display_currency" data-currency_symbol="true">
		      							{{$payroll->essentials_duration * $payroll->essentials_amount_per_unit_duration}}
		      						</span>
		      						<br>
		      						<small>( {{@num_format($payroll->essentials_duration)}} {{$payroll->essentials_duration_unit}} * {{@num_format($payroll->essentials_amount_per_unit_duration)}} )</small>
		      					</td>
		      				@elseif(isset($allowances['allowance_names'][$i-1]))
		      					<td>{{ $allowances['allowance_names'][$i-1] }}</td>
		      					<td>
		      						<span class="display_currency" data-currency_symbol="true">
		      							{{ $allowances['allowance_amounts'][$i-1] }}
		      						</span>
		      					</td>
		      					@php $total_earnings += !empty($allowances['allowance_amounts'][$i-1]) ? $allowances['allowance_amounts'][$i-1] : 0; @endphp
		      				@else
		      					<td></td><td></td>
		      				@endif

		      				@if(isset($deductions['deduction_names'][$i]))
		      					<td>{{ $deductions['deduction_names'][$i] }}</td>
		      					<td>
		      						<span class="display_currency" data-currency_symbol="true">
		      							{{ $deductions['deduction_amounts'][$i] }}
		      						</span>
		      					</td>
		      					@php $total_deduction += !empty($deductions['deduction_amounts'][$i]) ? $deductions['deduction_amounts'][$i] : 0; @endphp
		      				@else
		      					<td></td><td></td>
		      				@endif
		      			</tr>
		      		@endfor
		      		<tr class="bg-light">
		      			<td><strong>@lang('essentials::lang.total_earnings')</strong></td>
		      			<td><strong><span class="display_currency" data-currency_symbol="true">{{$total_earnings}}</span></strong></td>
		      			<td><strong>@lang('essentials::lang.total_deductions')</strong></td>
		      			<td><strong><span class="display_currency" data-currency_symbol="true">{{$total_deduction}}</span></strong></td>
		      		</tr>
		      		<tr class="bg-success">
		      			<td colspan="2" class="text-right"><strong>@lang('essentials::lang.net_pay')</strong></td>
		      			<td colspan="2"><strong><span class="display_currency" data-currency_symbol="true">{{ $payroll->final_total }}</span></strong></td>
		      		</tr>
		      		<tr>
		      			<td colspan="4">
		      				<strong>@lang('essentials::lang.in_words'):</strong> {{ucfirst($final_total_in_words)}}
		      			</td>
		      		</tr>
		      		<tr>
		      			<td colspan="4">
		      				<strong>{{ __('sale.payment_info') }}:</strong>
		      				<table class="table bg-gray table-slim">
		      					<tr class="bg-green">
		      						<th>#</th>
		      						<th>{{ __('messages.date') }}</th>
		      						<th>{{ __('purchase.ref_no') }}</th>
		      						<th>{{ __('sale.amount') }}</th>
		      						<th>{{ __('sale.payment_mode') }}</th>
		      						<th>{{ __('sale.payment_note') }}</th>
		      					</tr>
		      					@php $total_paid = 0; @endphp
		      					@forelse($payroll->payment_lines as $payment_line)
		      						@php
		      							if($payment_line->is_return == 1){
		      								$total_paid -= $payment_line->amount;
		      							} else {
		      								$total_paid += $payment_line->amount;
		      							}
		      						@endphp
		      						<tr>
		      							<td>{{ $loop->iteration }}</td>
		      							<td>{{ @format_date($payment_line->paid_on) }}</td>
		      							<td>{{ $payment_line->payment_ref_no }}</td>
		      							<td><span class="display_currency" data-currency_symbol="true">{{ $payment_line->amount }}</span></td>
		      							<td>{{ $payment_types[$payment_line->method]}}</td>
		      							<td>@if($payment_line->note) {{ ucfirst($payment_line->note) }} @else -- @endif</td>
		      						</tr>
		      					@empty
		      						<tr><td colspan="6" class="text-center">@lang('purchase.no_records_found')</td></tr>
		      					@endforelse
		      				</table>
		      			</td>
		      		</tr>
		      		<tr>
		      			<td colspan="4">
		      				<strong>@lang('brand.note'):</strong><br>
		      				{{$payroll->staff_note ?? ''}}
		      			</td>
		      		</tr>
		      	</table>
	      	</div>
	    </div>
	    <div class="modal-footer no-print">
	      	<button type="button" class="tw-dw-btn tw-dw-btn-primary tw-text-white" aria-label="Print" onclick="$(this).closest('div.modal-content').find('.modal-body').printThis();">
	      		<i class="fa fa-print"></i> @lang( 'messages.print' )
      		</button>
	      	<button type="button" class="tw-dw-btn tw-dw-btn-neutral tw-text-white" data-dismiss="modal">@lang( 'messages.close' )</button>
	    </div>
  	</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
<style type="text/css">
	#payroll-view>thead>tr>th, #payroll-view>tbody>tr>th,
	#payroll-view>tfoot>tr>th, #payroll-view>thead>tr>td,
	#payroll-view>tbody>tr>td, #payroll-view>tfoot>tr>td {
		border: 1px solid #1d1a1a;
	}
	#payroll-view th, #payroll-view td {
		vertical-align: middle !important;
	}
	#payroll-view .bg-info {
		background: #e8f4fa !important;
	}
	#payroll-view .bg-light {
		background: #f8f9fa !important;
	}
	#payroll-view .bg-success {
		background: #d4edda !important;
	}
</style>