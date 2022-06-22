{{Form::open(array('url'=>'holiday','method'=>'post'))}}
<div class="form-group">
    {{Form::label('date',__('Date'),['class' => 'col-form-label'])}}
    {{Form::date('date',null,array('class'=>'form-control'))}}
</div>
<div class="form-group">
    {{Form::label('occasion',__('Occasion'),['class' => 'col-form-label'])}}
    {{Form::text('occasion',null,array('class'=>'form-control'))}}
</div>

<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
    {{Form::submit(__('Create'),array('class'=>'btn  btn-primary'))}}
</div>
{{Form::close()}}
