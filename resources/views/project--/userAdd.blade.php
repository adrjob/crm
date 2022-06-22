{{ Form::open(array('route' => array('project.user.add',$id))) }}
<div class="row">
    <div class="form-group col-md-12">
        {{ Form::label('user', __('User')) }}
        {!! Form::select('user[]', $employee, null,array('class' => 'form-control','data-toggle'=>'select','required'=>'required')) !!}
    </div>
    <div class="modal-footer pr-0">
        <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
        {{Form::submit(__('Add'),array('class'=>'btn  btn-primary'))}}
    </div>
</div>

{{ Form::close() }}
