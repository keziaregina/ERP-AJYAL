@foreach ($payrollData as $item)
<div class="modal-dialog modal-lg" role="document">
	<div class="modal-content">		
			@php
			$item = (object)$item;
			@endphp	      				
				<div class="modal-body">
					<div class="table-responsive">
						<table class="table table-bordered" id="payroll-view">
							<tr>
								<td colspan="4" class="header">
									@if(!empty(Session::get('business.logo')))
										<img src="{{ $item->base64Image }}" alt="Logo" style="width: auto; max-height: 50px; margin: auto; justify-content: center;">
									@endif
									<div class="text-center">
										<strong class="font-23">
											{{Session::get('business.name') ?? ''}}
										</strong>
										<br>
										{!!Session::get('business.business_address') ?? ''!!}
									</div>
									<br>
									<div style="text-align: center;padding-top: 40px;">
										@lang('essentials::lang.payslip_for_the_month', ['month' => $item->month_name, 'year' => $item->year])
									</div>
								</td>
							</tr>
							<tr>
								<td colspan="2" style="vertical-align:top;">
									<strong>@lang('essentials::lang.employee'):</strong> {{$item?->payroll?->transaction_for?->user_full_name}}<br>
									<strong>@lang('essentials::lang.department'):</strong> {{$item?->department?->name ?? ''}}<br>
									<strong>@lang('essentials::lang.designation'):</strong> {{$item?->designation?->name ?? ''}}<br>
									<strong>@lang('lang_v1.primary_work_location'):</strong>
									@if(!empty($item->location)) {{$item->location->name}} @else {{__('report.all_locations')}} @endif<br>
									@if(!empty($item->payroll?->transaction_for?->id_proof_name) && !empty($item->payroll?->transaction_for?->id_proof_number))
									<strong>{{ucfirst($item->payroll?->transaction_for?->id_proof_name)}}:</strong> {{$item->payroll?->transaction_for?->id_proof_number}}<br>
									@endif
		      				<strong>@lang('lang_v1.tax_payer_id'):</strong> {{$item->bank_details['tax_payer_id'] ?? ''}}<br>
						</td>
						<td colspan="2" style="vertical-align:top;">
							<strong>@lang('lang_v1.bank_name'):</strong> {{$item?->bank_details['bank_name'] ?? ''}}<br>
							<strong>@lang('lang_v1.branch'):</strong> {{$item?->bank_details['branch'] ?? ''}}<br>
							<strong>@lang('lang_v1.bank_code'):</strong> {{$item?->bank_details['bank_code'] ?? ''}}<br>
							<strong>@lang('lang_v1.account_holder_name'):</strong> {{$item?->bank_details['account_holder_name'] ?? ''}}<br>
							<strong>@lang('lang_v1.bank_account_no'):</strong> {{$item?->bank_details['account_number'] ?? ''}}<br>
						</td>
					</tr>
					<tr>
						<td><strong>@lang('essentials::lang.total_overtime_hours'):</strong> {{$item?->total_overtime}}</td>
						<td><strong>@lang('essentials::lang.days_present'):</strong> {{$item?->total_days_present}}</td>
						<td><strong>@lang('essentials::lang.days_absent'):</strong> {{$item?->total_absent}}</td>
						<td><strong>@lang('essentials::lang.total_leaves'):</strong> {{$item?->total_leaves}}</td>
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
							  1 + (isset($item?->allowances['allowance_names']) ? count($item?->allowances['allowance_names']) : 0),
		      				(isset($item?->deductions['deduction_names']) ? count($item?->deductions['deduction_names']) : 0)
						);
						$row = 0;
						$total_earnings = $item?->payroll->essentials_duration * $item?->payroll->essentials_amount_per_unit_duration;
		      			$total_deduction = 0;
		      		@endphp
		      		@for($i = 0; $i < $max_rows; $i++)
						<tr>
					@if($i == 0)
		      			<td>@lang('essentials::lang.salary')</td>
		      			<td>
							  <span class="display_currency" data-currency_symbol="true">
		      					{{$item?->payroll->essentials_duration * $item?->payroll->essentials_amount_per_unit_duration}}
		      				</span>
		      				<br>
		      				<small>( {{@num_format($item?->payroll->essentials_duration)}} {{$item?->payroll->essentials_duration_unit}} * {{@num_format($item->payroll->essentials_amount_per_unit_duration)}} )</small>
						</td>
		      		@elseif(isset($item?->allowances['allowance_names'][$i-1]))
		      			<td>{{ $item?->allowances['allowance_names'][$i-1] }}</td>
		      			<td>
		      				<span class="display_currency" data-currency_symbol="true">
		      					{{ $item?->allowances['allowance_amounts'][$i-1] }}
		      				</span>
						</td>
					@php $total_earnings += !empty($item?->allowances['allowance_amounts'][$i-1]) ? $item->allowances['allowance_amounts'][$i-1] : 0; @endphp
					@else
						<td></td><td></td>
					@endif
					@if(isset($item?->deductions['deduction_names'][$i]))
							<td>{{ $item?->deductions['deduction_names'][$i] }}</td>
							<td>
		      					<span class="display_currency" data-currency_symbol="true">
		      						{{ $item?->deductions['deduction_amounts'][$i] }}
		      					</span>
		      				</td>
		      			@php $total_deduction += !empty($item->deductions['deduction_amounts'][$i]) ? $item->deductions['deduction_amounts'][$i] : 0; @endphp
		      		@else
		    			<td></td><td></td>
					@endif
						</tr>
					@endfor								
					<tr class="bg-light">
						<td><strong>@lang('essentials::lang.total_earnings')</strong></td>
						<td><strong><span class="display_currency" data-currency_symbol="true">{{$item?->payroll?->total_earnings}}</span></strong></td>
						<td><strong>@lang('essentials::lang.total_deductions')</strong></td>
						<td><strong><span class="display_currency" data-currency_symbol="true">{{$item?->payroll?->total_deduction}}</span></strong></td>
					</tr>
					<tr class="bg-success">
						<td colspan="2" class="text-right"><strong>@lang('essentials::lang.net_pay')</strong></td>
						<td colspan="2"><strong><span class="display_currency" data-currency_symbol="true">{{ $item?->payroll->final_total }}</span></strong></td>
					</tr>
					<tr>
						<td colspan="4">
							<strong>@lang('essentials::lang.in_words'):</strong> {{ucfirst($item?->final_total_in_words)}}
						</td>
					</tr>
					<tr>
						<td colspan="4">
							<strong>{{ __('sale.payment_info') }}:</strong>
							<table id="payment-info">
								<tr class="bg-green">
									<th>#</th>
									<th>{{ __('messages.date') }}</th>
									<th>{{ __('purchase.ref_no') }}</th>
									<th>{{ __('sale.amount') }}</th>
									<th>{{ __('sale.payment_mode') }}</th>
									<th>{{ __('sale.payment_note') }}</th>
								</tr>
								@php $total_paid = 0; @endphp
																			
								@if(!empty($item->payroll))
									@forelse($item->payroll->payment_lines as $payment_line)
									@php
										if($item->payment_line->is_return == 1){
										$total_paid -= $item->payment_line->amount;
									} else {
											$total_paid += $item->payment_line->amount;
										}
										@endphp
									<tr>
										<td>{{ $item?->loop?->iteration }}</td>
										<td>{{ @format_date($item?->payment_line?->paid_on) }}</td>
										<td>{{ $item?->payment_line?->payment_ref_no }}</td>
										<td><span class="display_currency" data-currency_symbol="true">{{ $item?->payment_line?->amount }}</span></td>
										<td>{{ $item?->payment_types[$payment_line->method]}}</td>
										<td>@if($item?->payment_line->note) {{ ucfirst($item?->payment_line?->note) }} @else -- @endif</td>
									</tr>
								@empty
									<tr><td colspan="6" class="text-center">@lang('purchase.no_records_found')</td></tr>
								@endforelse
								@else
									<tr><td colspan="6" class="text-center">@lang('purchase.no_records_found')</td></tr>
								@endif																					
		      				</table>
						</td>
					</tr>
		      		<tr>
		      			<td colspan="4">
							  <strong>@lang('brand.note'):</strong><br>
		      				{{$item->payroll?->staff_note ?? ''}}
						</td>
					</tr>
		      	</table>
	      	</div>
	    </div>
	</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
@endforeach
<style type="text/css">	
	#payroll-view th, #payroll-view td {
		vertical-align: middle ;
		border: 1px solid #1d1a1a !important;
	}
	#payroll-view .header {
		text-align: center !important;
	}
	#payroll-view #payment-info {
		width: 100% !important;
	}
	#payroll-view #payment-info th {
		border: none ; 
		background-color: skyblue !important;
	}
	#payroll-view #payment-info td {
		border: none ;
		background-color: #32a7da !important;	
	}
	#payroll-view .bg-info {
		background-color: #e8f4fa !important;
	}
	#payroll-view .bg-light {
		background-color: #f8f9fa !important;
	}
	#payroll-view .bg-success {
		background-color: #d4edda !important;
	}
	#payroll-view .text-center {
		text-align: center;
		justify-content: end !important;
	}	
</style>