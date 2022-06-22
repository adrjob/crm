@extends('layouts.admin')
@section('page-title')
    {{__('Project Create')}}
@endsection
@push('css-page')
@endpush
@push('script-page')
@endpush
@section('title')
    <div class="d-inline-block">
        <h5 class="h4 d-inline-block font-weight-400 mb-0">{{__('Project Create')}}</h5>
    </div>
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item"><a href="{{route('project.index')}}">{{__('Project')}}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{__('Create')}}</li>
@endsection
@section('content')
    {{ Form::open(array('url' => 'project','class'=>'mt-4')) }}
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="form-group col-md-4">
                    {{ Form::label('title', __('Project Title'),['class'=>'form-label']) }}
                    {{ Form::text('title', null, array('class' => 'form-control','required'=>'required')) }}
                </div>
                <div class="form-group col-md-4">
                    {{ Form::label('category', __('Category'),['class'=>'form-label']) }}
                    {{ Form::select('category', $categories,'', array('class' => 'form-control','data-toggle'=>'select')) }}
                </div>
                <div class="form-group col-md-4">
                    {{ Form::label('price', __('Price'),['class'=>'form-label']) }}
                    {{ Form::number('price', null, array('class' => 'form-control','required'=>'required','stage'=>'0.01')) }}
                </div>
                <div class="form-group col-md-4">
                    {{ Form::label('start_date', __('Start Date'),['class'=>'form-label']) }}
                    {{Form::date('start_date',null,array('class'=>'form-control','required'=>'required'))}}
                </div>
                <div class="form-group col-md-4">
                    {{ Form::label('due_date', __('Due Date'),['class'=>'form-label']) }}
                    {{Form::date('due_date',null,array('class'=>'form-control','required'=>'required'))}}
                </div>
                <div class="form-group col-md-4">
                    {{ Form::label('lead', __('Lead'),['class'=>'form-label']) }}
                    {{ Form::select('lead', $leads,null, array('class' => 'form-control','data-toggle'=>'select')) }}
                </div>
                <div class="form-group col-md-4">
                    {{ Form::label('client', __('Client'),['class'=>'form-label']) }}
                    {{ Form::select('client', $clients,'', array('class' => 'form-control','data-toggle'=>'select')) }}
                </div>
                <div class="form-group col-md-4">
                    {{ Form::label('employee', __('Employee'),['class'=>'form-label']) }}
                    {{ Form::select('employee[]', $employees,null, array('class' => 'form-control multi-select','id'=>'choices-multiple','multiple'=>'','required'=>'required')) }}

                </div>
                <div class="form-group col-md-4">
                    {{ Form::label('status', __('Status'),['class'=>'form-label']) }}
                    {{ Form::select('status', $projectStatus,null, array('class' => 'form-control','data-toggle'=>'select','required'=>'required')) }}
                </div>
                <div class="form-group col-md-12">
                    {{ Form::label('description', __('Description'),['class'=>'form-label']) }}
                    {{ Form::textarea('description',null, array('class' => 'form-control','rows'=>'2')) }}
                </div>
                <div class="modal-footer pr-0">
                    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
                    {{Form::submit(__('Create'),array('class'=>'btn  btn-primary'))}}
                </div>
            </div>
        </div>
    </div>
    {{ Form::close() }}
@endsection

<script src="{{asset('assets/js/plugins/choices.min.js')}}"></script>
<script>
    if ($(".multi-select").length > 0) {
              $( $(".multi-select") ).each(function( index,element ) {
                  var id = $(element).attr('id');
                     var multipleCancelButton = new Choices(
                          '#'+id, {
                              removeItemButton: true,
                          }
                      );
              });
         }
  </script>

