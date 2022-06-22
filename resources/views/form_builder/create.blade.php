{{ Form::open(array('url' => 'form_builder')) }}
<div class="row">
    <div class="col-12 form-group">
        {{ Form::label('name', __('Name'),['class'=>'col-form-label']) }}
        {{ Form::text('name', '', array('class' => 'form-control','required'=> 'required')) }}
    </div>
    <div class="col-12 form-group">
        {{ Form::label('active', __('Active'),['class'=>'col-form-label ']) }}
        <div class="form-check form-check-inline"> 
            <input class="form-check-input" type="radio" name="is_active" value="1"
                id="on" checked="checked">
            <label class="form-check-label" for="on">
                {{__('On')}}
            </label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="is_active" value="0"
                id="off">
            <label class="form-check-label" for="off">
                {{__('Off')}}
            </label>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
        {{Form::submit(__('Create'),array('class'=>'btn  btn-primary'))}}
    </div>
</div>
{{ Form::close() }}
