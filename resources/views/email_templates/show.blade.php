@extends('layouts.admin')

@push('pre-purpose-css-page')
<link rel="stylesheet" href="{{asset('css/summernote/summernote-bs4.css')}}">
@endpush

@push('script-page')
<script src="{{asset('css/summernote/summernote-bs4.js')}}"></script> 
<script src="{{asset('assets/js/plugins/tinymce/tinymce.min.js')}}"></script>
<script>
    if ($(".pc-tinymce-2").length) {
        tinymce.init({
            selector: '.pc-tinymce-2',
            height: "400",
            content_style: 'body { font-family: "Inter", sans-serif; }'
        });
    }
</script>
@endpush

@section('page-title')
    {{ $emailTemplate->name }}
@endsection
@section('title')
    <div class="d-inline-block">
        <h5 class="h4 d-inline-block font-weight-400 mb-0"> {{ $emailTemplate->name }}</h5>
    </div>
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item"><a href="{{route('email_template.index')}}">{{__('Email Template')}}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{$emailTemplate->name}}</li>
@endsection
@section('action-btn')
@endsection
@section('content')
    <div class="row">
        <div class="col-4">
            <div class="card">
                <div class="card-header card-body">
                    <h5></h5>
                    {{Form::model($emailTemplate, array('route' => array('email_template.update', $emailTemplate->id), 'method' => 'PUT')) }}
                    <div class="row">
                        <div class="form-group col-md-12">
                            {{Form::label('name',__('Name'),['class'=>'form-label text-dark'])}}
                            {{Form::text('name',null,array('class'=>'form-control font-style','disabled'=>'disabled'))}}
                        </div>
                        <div class="form-group col-md-12">
                            {{Form::label('from',__('From'),['class'=>'form-label text-dark'])}}
                            {{Form::text('from',null,array('class'=>'form-control font-style','required'=>'required'))}}
                        </div>
                        {{Form::hidden('lang',$currEmailTempLang->lang,array('class'=>''))}}
                            <div class="col-12 text-end">
                                <input type="submit" value="{{__('Save')}}" class="btn btn-print-invoice  btn-primary m-r-10">
                            </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>

        <div class="col-8">
            <div class="card">
                <div class="card-header card-body">
                    <h5></h5>
                    <div class="row text-xs">
                        @if($emailTemplate->slug=='create_user')
                            <div class="row">
                                <h6 class="font-weight-bold pb-3">{{__('Create User')}}</h6>
                                <p class="col-6">{{__('App Name')}} : <span class="pull-end text-primary">{app_name}</span></p>
                                <p class="col-6">{{__('Company Name')}} : <span class="pull-right text-primary">{company_name}</span></p>
                                <p class="col-6">{{__('App Url')}} : <span class="pull-right text-primary">{app_url}</span></p>
                                <p class="col-6">{{__('Email')}} : <span class="pull-right text-primary">{email}</span></p>
                                <p class="col-6">{{__('Password')}} : <span class="pull-right text-primary">{password}</span></p>
                            </div>
                            @elseif($emailTemplate->slug=='lead_assign')
                            <div class="row">
                                <h6 class="font-weight-bold pb-3">{{__('Lead Assign')}}</h6>
                                <p class="col-6">{{__('Lead Name')}} : <span class="pull-right text-primary">{lead_name}</span></p>
                                <p class="col-6">{{__('Lead Email')}} : <span class="pull-right text-primary">{lead_email}</span></p>
                                <p class="col-6">{{__('Lead Subject')}} : <span class="pull-right text-primary">{lead_subject}</span></p>
                                <p class="col-6">{{__('Lead Pipeline')}} : <span class="pull-right text-primary">{lead_pipeline}</span></p>
                                <p class="col-6">{{__('Lead Stage')}} : <span class="pull-right text-primary">{lead_stage}</span></p>
                            </div>
                        @elseif($emailTemplate->slug=='deal_assign')
                            <div class="row">
                                <h6 class="font-weight-bold pb-3">{{__('Deal Assign')}}</h6>
                                <p class="col-6">{{__('Deal Name')}} : <span class="pull-right text-primary">{deal_name}</span></p>
                                <p class="col-6">{{__('Deal Pipeline')}} : <span class="pull-right text-primary">{deal_pipeline}</span></p>
                                <p class="col-6">{{__('Deal Stage')}} : <span class="pull-right text-primary">{deal_stage}</span></p>
                                <p class="col-6">{{__('Deal Status')}} : <span class="pull-right text-primary">{deal_status}</span></p>
                                <p class="col-6">{{__('Deal Price')}} : <span class="pull-right text-primary">{deal_price}</span></p>
                                <p class="col-6">{{__('Deal Stage')}} : <span class="pull-right text-primary">{deal_stage}</span></p>
                            </div>
                        @elseif($emailTemplate->slug=='send_estimation')
                            <div class="row">
                                <h6 class="font-weight-bold pb-3">{{__('Send Estimation')}}</h6>
                                <p class="col-6">{{__('Estimation Name')}} : <span class="pull-right text-primary">{estimation_id}</span></p>
                                <p class="col-6">{{__('Estimation Client')}} : <span class="pull-right text-primary">{estimation_client}</span></p>
                                <p class="col-6">{{__('Estimation Category')}} : <span class="pull-right text-primary">{estimation_category}</span></p>
                                <p class="col-6">{{__('Estimation Issue Date')}} : <span class="pull-right text-primary">{estimation_issue_date}</span></p>
                                <p class="col-6">{{__('Estimation Expiry Date')}} : <span class="pull-right text-primary">{estimation_expiry_date}</span></p>
                                <p class="col-6">{{__('Estimation Status')}} : <span class="pull-right text-primary">{estimation_status}</span></p>
                            </div>
                        @elseif($emailTemplate->slug=='create_project')
                            <div class="row">
                                <h6 class="font-weight-bold pb-3">{{__('Create Project')}}</h6>
                                <p class="col-6">{{__('Project Title')}} : <span class="pull-right text-primary">{project_title}</span></p>
                                <p class="col-6">{{__('Project Category')}} : <span class="pull-right text-primary">{project_category}</span></p>
                                <p class="col-6">{{__('Project Price')}} : <span class="pull-right text-primary">{project_price}</span></p>
                                <p class="col-6">{{__('Project Client')}} : <span class="pull-right text-primary">{project_client}</span></p>
                                <p class="col-6">{{__('Project Assign User')}} : <span class="pull-right text-primary">{project_assign_user}</span></p>
                                <p class="col-6">{{__('Project Start Date')}} : <span class="pull-right text-primary">{project_start_date}</span></p>
                                <p class="col-6">{{__('Project Due Date')}} : <span class="pull-right text-primary">{project_due_date}</span></p>
                                <p class="col-6">{{__('Project Lead')}} : <span class="pull-right text-primary">{project_lead}</span></p>
                            </div>
                        @elseif($emailTemplate->slug=='task_assign')
                            <div class="row">
                                <h6 class="font-weight-bold pb-3">{{__('Task Assign')}}</h6>
                                <p class="col-6">{{__('Project')}} : <span class="pull-right text-primary">{project}</span></p>
                                <p class="col-6">{{__('Task Title')}} : <span class="pull-right text-primary">{task_title}</span></p>
                                <p class="col-6">{{__('Task Priority')}} : <span class="pull-right text-primary">{task_priority}</span></p>
                                <p class="col-6">{{__('Task Start Date')}} : <span class="pull-right text-primary">{task_start_date}</span></p>
                                <p class="col-6">{{__('Task Due Date')}} : <span class="pull-right text-primary">{task_due_date}</span></p>
                                <p class="col-6">{{__('Task Stage')}} : <span class="pull-right text-primary">{task_stage}</span></p>
                                <p class="col-6">{{__('Task Assign User')}} : <span class="pull-right text-primary">{task_assign_user}</span></p>
                                <p class="col-6">{{__('Task Description')}} : <span class="pull-right text-primary">{task_description}</span></p>
                            </div>
                        @elseif($emailTemplate->slug=='send_invoice' || $emailTemplate->slug=='invoice_payment_recored')
                            <div class="row">
                                <h6 class="font-weight-bold pb-3">{{__('Send Invoice')}}</h6>
                                <p class="col-6">{{__('Invoice Number')}} : <span class="pull-right text-primary">{invoice_id}</span></p>
                                <p class="col-6">{{__('Invoice Client')}} : <span class="pull-right text-primary">{invoice_client}</span></p>
                                <p class="col-6">{{__('Invoice Issue Date')}} : <span class="pull-right text-primary">{invoice_issue_date}</span></p>
                                <p class="col-6">{{__('Invoice Due Date')}} : <span class="pull-right text-primary">{invoice_due_date}</span></p>
                                <p class="col-6">{{__('Invoice Status')}} : <span class="pull-right text-primary">{invoice_status}</span></p>
                                <p class="col-6">{{__('Invoice Total')}} : <span class="pull-right text-primary">{invoice_total}</span></p>
                                <p class="col-6">{{__('Invoice Sub Total')}} : <span class="pull-right text-primary">{invoice_sub_total}</span></p>
                                <p class="col-6">{{__('Invoice Due Amount')}} : <span class="pull-right text-primary">{invoice_due_amount}</span></p>
                                <p class="col-6">{{__('Invoice Status')}} : <span class="pull-right text-primary">{invoice_status}</span></p>
                                <p class="col-6">{{__('Invoice Payment Recorded Total')}} : <span class="pull-right text-primary">{payment_total}</span></p>
                                <p class="col-6">{{__('Invoice Payment Recorded Date')}} : <span class="pull-right text-primary">{payment_date}</span></p>
    
                            </div>
                        @elseif($emailTemplate->slug=='credit_note')
                            <div class="row">
                                <h6 class="font-weight-bold pb-3">{{__('Credit Note')}}</h6>
                                <p class="col-6">{{__('Invoice Number')}} : <span class="pull-right text-primary">{invoice_id}</span></p>
                                <p class="col-6">{{__('Date')}} : <span class="pull-right text-primary">{credit_note_date}</span></p>
                                <p class="col-6">{{__('Invoice Client')}} : <span class="pull-right text-primary">{invoice_client}</span></p>
                                <p class="col-6">{{__('Amount')}} : <span class="pull-right text-primary">{credit_amount}</span></p>
                                <p class="col-6">{{__('Description')}} : <span class="pull-right text-primary">{credit_description}</span></p>
                            </div>
                        @elseif($emailTemplate->slug=='create_support')
                            <div class="row">
                                <h6 class="font-weight-bold pb-3">{{__('Create Support')}}</h6>
                                <p class="col-6">{{__('Ticket Title')}} : <span class="pull-right text-primary">{support_title}</span></p>
                                <p class="col-6">{{__('Ticket Assign User')}} : <span class="pull-right text-primary">{assign_user}</span></p>
                                <p class="col-6">{{__('Ticket Priority')}} : <span class="pull-right text-primary">{support_priority}</span></p>
                                <p class="col-6">{{__('Ticket End Date')}} : <span class="pull-right text-primary">{support_end_date}</span></p>
                                <p class="col-6">{{__('Ticket Description')}} : <span class="pull-right text-primary">{support_description}</span></p>
                            </div>
                        @elseif($emailTemplate->slug=='create_contract')
                            <div class="row">
                                <h6 class="font-weight-bold pb-3">{{__('Create Contract')}}</h6>
                                <p class="col-6">{{__('Contract Subject')}} : <span class="pull-right text-primary">{contract_subject}</span></p>
                                <p class="col-6">{{__('Contract Client')}} : <span class="pull-right text-primary">{contract_client}</span></p>
                                <p class="col-6">{{__('Contract Value')}} : <span class="pull-right text-primary">{contract_value}</span></p>
                                <p class="col-6">{{__('Contract Start Date')}} : <span class="pull-right text-primary">{contract_start_date}</span></p>
                                <p class="col-6">{{__('Contract End Date')}} : <span class="pull-right text-primary">{contract_end_date}</span></p>
                                <p class="col-6">{{__('Contract Description')}} : <span class="pull-right text-primary">{contract_description}</span></p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card">
            <div class="card-header card-body">
                <h5></h5>
                <div class="language-wrap">
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-12 language-list-wrap">
                            <div class="language-list">
                                <ul class="nav nav-pills flex-column" id="myTab4" role="tablist">
                                    @foreach($languages as $lang)
                                        <li class="nav-item">
                                            <a href="{{route('manage.email.language',[$emailTemplate->id,$lang])}}" class="nav-link {{($currEmailTempLang->lang == $lang)?'active':''}}">{{Str::upper($lang)}}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-9 col-md-9 col-sm-12 language-form-wrap">
                            {{Form::model($currEmailTempLang, array('route' => array('store.email.language',$currEmailTempLang->parent_id), 'method' => 'post')) }}
                            <div class="row">
                                <div class="form-group col-12">
                                    {{Form::label('subject',__('Subject'),['class'=>'form-label text-dark'])}}
                                    {{Form::text('subject',null,array('class'=>'form-control font-style','required'=>'required'))}}
                                </div>
                                <div class="form-group col-12">
                                    {{Form::label('content',__('Email Message'),['class'=>'form-label text-dark'])}}
                                    {{Form::textarea('content',$currEmailTempLang->content,array('class'=>'pc-tinymce-2','required'=>'required'))}}

                                </div>
                                @can('Edit Email Template Lang')
                                    <div class="col-md-12 text-end">
                                        {{Form::hidden('lang',null)}}
                                        <input type="submit" value="{{__('Save')}}" class="btn btn-print-invoice  btn-primary m-r-10">
                                    </div>
                                @endcan
                            </div>
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection

