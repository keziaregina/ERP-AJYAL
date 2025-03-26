@extends('layouts.app')
@section('title',  __('barcode.edit_barcode_setting'))

@section('content')
<style type="text/css">



</style>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black">@lang('report_settings.edit_setting')</h1>
</section>
{{-- @dd($report_settings->user_id) --}}
<!-- Main content -->
<section class="content">
{!! Form::open(['url' => action([\App\Http\Controllers\ReportSettingsController::class, 'update'], [$report_settings->id]), 'method' => 'PUT', 
'id' => 'add_report_settings_form' ]) !!}
  <div class="box box-solid">
    <div class="box-body">
      <div class="row">
        <div class="col-sm-12">
          <div class="form-group">
            {!! Form::label('user_name', __('report_settings.user_name') . ':*') !!}
              {!! Form::select('user_name',$users, $report_settings->user_id, ['class' => 'form-control', 'required',
              'placeholder' => __('report_settings.user_name')]); !!}
          </div>
        </div>
        <div class="col-sm-12">
          <div class="form-group">
            {!! Form::label('report_type', __('report_settings.report_type') . ':*') !!}
              {!! Form::select('report_type', $report_type, $report_settings->type, ['class' => 'form-control',
              'placeholder' => __('report_settings.report_type')]); !!}
          </div>
        </div>
        
        <div class="col-sm-12">
          <div class="form-group">
             {!! Form::label('report_interval', __('report_settings.report_interval') . ':*') !!}
            <div class="input-group">
              <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar" aria-hidden="true"></span>
              </span>
              {!! Form::select('report_interval',$intervals, $report_settings->interval, ['class' => 'form-control',
              'placeholder' => __('report_settings.report_interval')]); !!}
            </div>
          </div>
        </div>
        <div class="col-sm-12">
          <div class="form-group">
            {!! Form::label('attachment_lang', __('report_settings.attachment_lang') . ':*') !!}
              {!! Form::select('attachment_lang', $langs, $report_settings->attachment_lang, ['class' => 'form-control',
              'placeholder' => __('report_settings.attachment_lang'), 'required']); !!}
          </div>
        </div>
        <div class="clearfix"></div>
        <div class="col-sm-12 text-center">
          <button type="submit" class="btn btn-primary btn-big">@lang('messages.update')</button>
        </div>
      </div>
    </div>
  </div>
  {!! Form::close() !!}
</section>
<!-- /.content -->
@endsection