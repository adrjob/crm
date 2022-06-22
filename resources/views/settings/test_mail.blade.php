{{ Form::open(array('route' => array('test.send.mail'))) }}
<div class="row">
    <div class="form-group col-md-12">
        {{ Form::label('email', __('Email'),['class' => 'col-form-label']) }}
        {{ Form::text('email', '', array('class' => 'form-control','required'=>'required')) }}
        @error('email')
        <span class="invalid-email" role="alert">
            <strong class="text-danger">{{ $message }}</strong>
        </span>
        @enderror
    </div>
</div>
<div class="modal-footer pr-0">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
    {{Form::submit(__('Send'),array('class'=>'btn  btn-primary'))}}
</div>
{{ Form::close() }}

