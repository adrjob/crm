
    {{ Form::model($formBuilder, array('route' => array('form_builder.update', $formBuilder->id), 'method' => 'PUT')) }}
    <div class="row">
        <div class="col-12 form-group">
            {{ Form::label('name', __('Name'),['class'=>'col-form-label']) }}
            {{ Form::text('name', null, array('class' => 'form-control','required' => 'required')) }}
        </div>
        <div class="col-12 form-group">
            {{ Form::label('active', __('Active'),['class'=>'col-form-label ']) }}
            <div class="form-check form-check-inline"> 
                <input class="form-check-input" type="radio" name="is_active" value="1"
                    id="on" {{($formBuilder->is_active == 1) ? 'checked' : ''}}>
                <label class="form-check-label" for="on">
                    {{__('On')}}
                </label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="is_active" value="0" {{($formBuilder->is_active == 0) ? 'checked' : ''}}
                    id="off">
                <label class="form-check-label" for="off">
                    {{__('Off')}}
                </label>
            </div>
        </div>

        {{-- <div class="col-12 form-group">
            {{ Form::label('active', __('Active'),['class'=>'form-control-label']) }}
            <div class="d-flex radio-check">
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="on" value="1" name="is_active" class="custom-control-input" {{($formBuilder->is_active == 1) ? 'checked' : ''}}>
                    <label class="custom-control-label form-control-label" for="on">{{__('On')}}</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="off" value="0" name="is_active" class="custom-control-input" {{($formBuilder->is_active == 0) ? 'checked' : ''}}>
                    <label class="custom-control-label form-control-label" for="off">{{__('Off')}}</label>
                </div>
            </div>
        </div> --}}
        <div class="modal-footer">
            <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
            {{Form::submit(__('Update'),array('class'=>'btn  btn-primary'))}}
        </div>
    </div>
    {{ Form::close() }}

