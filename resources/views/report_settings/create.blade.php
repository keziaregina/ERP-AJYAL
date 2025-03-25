@extends('layouts.app')
@section('title',  __('report_settings.add_new_setting'))

@section('content')
<style type="text/css">



</style>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black">@lang('report_settings.add_new_setting')</h1>
    <!-- <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
    </ol> -->
</section>

<!-- Main content -->
<section class="content">
{!! Form::open(['url' => action([\App\Http\Controllers\ReportSettingsController::class, 'store']), 'method' => 'post', 
'id' => 'add_report_settings_form' ]) !!}
	@component('components.widget')
  <div class="row">
    <div class="col-sm-12">
      <div class="form-group">
        {!! Form::label('user_name', __('report_settings.user_name') . ':*') !!}
          {!! Form::select('user_name', $users, null, ['class' => 'form-control', 'required',
          'placeholder' => __('report_settings.user_name'), 'required']); !!}
      </div>
    </div>
    <div class="col-sm-12">
      <div class="form-group">
        {!! Form::label('report_type', __('report_settings.report_type') . ':*') !!}
          {!! Form::select('report_type', $reportTypes, null, ['class' => 'form-control',
          'placeholder' => __('report_settings.report_type'), 'required']); !!}
      </div>
    </div>
    
    <div class="col-sm-12">
      <div class="form-group">
         {!! Form::label('report_interval', __('report_settings.report_interval') . ':*') !!}
        <div class="input-group">
          <span class="input-group-addon">
            <span class="glyphicon glyphicon-calendar" aria-hidden="true"></span>
          </span>
          {!! Form::select('report_interval', $intervals, null, ['class' => 'form-control',
          'placeholder' => __('report_settings.report_interval'), 'required']); !!}
        </div>
      </div>
    </div>
    <div class="col-sm-12">
      <div class="form-group">
        {!! Form::label('attachment_lang', __('report_settings.attachment_lang') . ':*') !!}
          {!! Form::select('attachment_lang', ['All'=>'All Languages', 'en'=>'English', 'ar'=>'Arabic'], ['All'], ['class' => 'form-control',
          'placeholder' => __('report_settings.attachment_lang'), 'required']); !!}
      </div>
    </div>
    <div class="col-sm-12 text-center">
      <button type="submit" class="tw-dw-btn tw-dw-btn-primary tw-dw-btn-lg tw-text-white">@lang('messages.save')</button>
    </div>
  </div>
  @endcomponent
  {!! Form::close() !!}
</section>
<!-- /.content -->
@endsection