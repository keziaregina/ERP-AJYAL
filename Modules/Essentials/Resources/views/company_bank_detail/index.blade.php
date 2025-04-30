@extends('layouts.app')

@section('title', __('essentials::lang.company_bank_detail'))

@section('css')
    <style>
        .modal-header {
            display: flex;
            justify-content: space-between;
            width: 100%;
            /* background-color: #007bff; */
        }        
    </style>
@endsection
@section('content')
    @include('essentials::layouts.nav_hrm')

    <section class="content-header">
        <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black">@lang('essentials::lang.company_bank_detail')
            <small class="tw-text-sm md:tw-text-base tw-text-gray-700 tw-font-semibold">@lang('essentials::lang.manage_your_company_bank_detail')</small>
        </h1>
    </section>

    <section class="content">
        {{-- @component('components.widget', ['title' => __('essentials::lang.manage_your_company_bank_detail')])
            <div class="container">
                <h1>Company Bank Detail</h1>
            </div>
        @endcomponent --}}
        {{-- @can('essentials.export_company_bank')
        <div class="tw-flex tw-gap-2 tw-mb-4">
            <a href="{{ route('payroll.pdf') }}"  class="bg-black tw-dw-btn tw-bg-gradient-to-r tw-from-red-600 tw-to-red-500 tw-font-bold tw-text-white tw-border-none tw-rounded-full tw-px-4 tw-py-2 tw-flex tw-items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="tw-mr-2">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                    <polyline points="10 9 9 9 8 9"></polyline>
                </svg>
                PDF
            </a>

            <a href="{{ route('sif-export-excel') }}" class="bg-black tw-dw-btn tw-bg-gradient-to-r tw-from-red-600 tw-to-red-500 tw-font-bold tw-text-white tw-border-none tw-rounded-full tw-px-4 tw-py-2 tw-flex tw-items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="tw-mr-2">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                    <polyline points="10 9 9 9 8 9"></polyline>
                </svg>
                Excel
            </a>
        </div>
        @endcan --}}

        <form action="{{ action([\Modules\Essentials\Http\Controllers\CompanyBankDetailController::class, 'store']) }}" method="POST">
            @csrf

            <div style="border-radius: 10px; padding: 20px; background-color: white;" class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 lg:tw-grid-cols-4 tw-gap-4">
                <div class="form-group">
                    <label for="employer_cr_no">@lang('essentials::lang.employer_cr_no')</label>
                    <input type="number" name="employer_cr_no" id="employer_cr_no" class="form-control" required @if ($companyBankDetail)
                        value="{{ old('employer_cr_no', $companyBankDetail->employer_cr_no) }}"
                    @else
                        value="{{ old('employer_cr_no') }}"
                    @endif
                    >
                </div>
                <div class="form-group">
                    <label for="payer_cr_no">@lang('essentials::lang.payer_cr_no')</label>
                    <input type="number" name="payer_cr_no" id="payer_cr_no" class="form-control" required @if ($companyBankDetail)
                        value="{{ old('payer_cr_no', $companyBankDetail->payer_cr_no) }}"
                    @else
                        value="{{ old('payer_cr_no') }}"
                    @endif
                    >
                </div>
                <div class="form-group">
                    <label for="payer_bank_short_name">@lang('essentials::lang.payer_bank_short_name')</label>
                    <input type="text" name="payer_bank_short_name" id="payer_bank_short_name" class="form-control" required @if ($companyBankDetail)
                        value="{{ old('payer_bank_short_name', $companyBankDetail->payer_bank_short_name) }}"
                    @else
                        value="{{ old('payer_bank_short_name') }}"
                    @endif
                    >
                </div>
                <div class="form-group">
                    <label for="payer_account_number">@lang('essentials::lang.payer_account_number')</label>
                    <input type="number" name="payer_account_number" id="payer_account_number" class="form-control" required @if ($companyBankDetail)
                        value="{{ old('payer_account_number', $companyBankDetail->payer_account_number) }}"
                    @else
                        value="{{ old('payer_account_number') }}"
                    @endif
                    >
                </div>

                <div class="form-group">
                    <label for="employee_type_id">@lang('essentials::lang.employee_type')</label>
                    <select name="employee_type_id" id="employee_type_id" class="form-control" required>
                        <option value="" selected disabled>@lang('essentials::lang.select_employee_type')</option>
                        @foreach ($employeeTypes as $key => $value)
                            <option value="{{ $key }}" {{ $companyBankDetail && $companyBankDetail->employee_type_id == $key ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>

            </div>

            <div class="flex justify-end">
                <button type="submit" class="btn btn-primary tw-mt-4">@lang('essentials::lang.submit')</button>
            </div>
            
        </form>
    </section>

@endsection

