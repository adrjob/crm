{{ Form::model($projectStage, array('route' => array('projectStage.update', $projectStage->id), 'method' => 'PUT')) }}
<div class="row">
    <div class="form-group col-md-12">
        {{ Form::label('name', __('Name'),['class' => 'col-form-label']) }}
        {{ Form::text('name', null, array('class' => 'form-control','required'=>'required')) }}
    </div>
    <div class="form-group col-md-12">
        {{ Form::label('color', __('Color'),['class' => 'col-form-label']) }}
        {{ Form::color('color', null, array('class' => 'form-control','required'=>'required')) }}
    </div>
</div>
<div class="modal-footer pr-0">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
    {{Form::submit(__('Update'),array('class'=>'btn  btn-primary'))}}
</div>
{{ Form::close() }}

