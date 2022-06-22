@extends('layouts.admin')
@section('page-title')
    {{__('Project Edit')}}
@endsection
@push('css-page')
@endpush
@push('script-page')
@endpush
@section('title')
    <div class="d-inline-block">
        <h5 class="h4 d-inline-block font-weight-400 mb-0">{{__('Project Edit')}}</h5>
    </div>
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item"><a href="{{route('project.index')}}">{{__('Project')}}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{__('Edit')}}</li>
@endsection
@section('content')
    {{ Form::model($project, array('route' => array('project.update', $project->id), 'method' => 'PUT','class'=>'mt-4')) }}
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="form-group col-md-4">
                    {{ Form::label('title', __('Project Title')) }}
                    {{ Form::text('title', null, array('class' => 'form-control','required'=>'required')) }}
                </div>
                <div class="form-group col-md-4">
                    {{ Form::label('category', __('Category')) }}
                    {{ Form::select('category', $categories,null, array('class' => 'form-control','data-toggle'=>'select')) }}
                </div>
                <div class="form-group col-md-4">
                    {{ Form::label('price', __('Price')) }}
                    {{ Form::number('price', null, array('class' => 'form-control','required'=>'required','stage'=>'0.01')) }}
                </div>
                <div class="form-group col-md-4">
                    {{ Form::label('start_date', __('Start Date')) }}
                    {{Form::date('start_date',null,array('class'=>'form-control','required'=>'required'))}}
                </div>
                <div class="form-group col-md-4">
                    {{ Form::label('due_date', __('Due Date')) }}
                    {{Form::date('due_date',null,array('class'=>'form-control','required'=>'required'))}}
                </div>
                <div class="form-group col-md-4">
                    {{ Form::label('lead', __('Lead')) }}
                    {{ Form::select('lead', $leads,null, array('class' => 'form-control','data-toggle'=>'select')) }}
                </div>
                <div class="form-group col-md-4">
                    {{ Form::label('client', __('Client')) }}
                    {{ Form::select('client', $clients,null, array('class' => 'form-control','data-toggle'=>'select')) }}
                </div>

                <div class="form-group col-md-4">
                    {{ Form::label('status', __('Status')) }}
                    {{ Form::select('status', $projectStatus,null, array('class' => 'form-control','data-toggle'=>'select','required'=>'required')) }}
                </div>
                <div class="form-group col-md-12">
                    {{ Form::label('description', __('Description')) }}
                    {{ Form::textarea('description',null, array('class' => 'form-control','rows'=>'2')) }}
                </div>
                <div class="modal-footer pr-0">
                    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
                    {{Form::submit(__('Update'),array('class'=>'btn  btn-primary'))}}
                </div>
            </div>
        </div>
    </div>
    {{ Form::close() }}
@endsection

