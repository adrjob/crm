{{ Form::model($contractType, array('route' => array('contractType.update', $contractType->id), 'method' => 'PUT')) }}
<div class="form-group">
    {{ Form::label('name', __('Name'),['class' => 'col-form-label']) }}
    {{ Form::text('name', null, array('class' => 'form-control','required'=>'required')) }}
</div>
<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
    {{Form::submit(__('Create'),array('class'=>'btn  btn-primary'))}}
</div>

{{ Form::close() }}