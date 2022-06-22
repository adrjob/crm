{{ Form::open(array('url' => 'leaveType')) }}
<div class="form-group">
    {{ Form::label('title', __('Title'),['class' => 'col-form-label']) }}
    {{ Form::text('title', '', array('class' => 'form-control','required'=>'required')) }}
</div>
<div class="form-group">
    {{Form::label('days',__('Days Per Year'),['class' => 'col-form-label'])}}
    {{Form::number('days',null,array('class'=>'form-control'))}}
</div>
<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
    {{Form::submit(__('Create'),array('class'=>'btn  btn-primary'))}}
</div>
{{ Form::close() }}
