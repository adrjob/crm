{{ Form::open(array('url' => 'employee')) }}
<div class="row">
    <div class="form-group col-md-12">
        {{ Form::label('name', __('Name'),['class' => 'col-form-label']) }}
        {{ Form::text('name', '', array('class' => 'form-control','required'=>'required')) }}
    </div>
    <div class="form-group col-md-12">
        {{ Form::label('email', __('Email'),['class' => 'col-form-label']) }}
        {{ Form::text('email', '', array('class' => 'form-control','required'=>'required')) }}
    </div>
    <div class="form-group col-md-12">
        {{ Form::label('password', __('Password'),['class' => 'col-form-label']) }}
        {{Form::password('password',array('class'=>'form-control','required'=>'required','minlength'=>"6"))}}
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
    {{Form::submit(__('Create'),array('class'=>'btn  btn-primary'))}}
</div>

{{ Form::close() }}
