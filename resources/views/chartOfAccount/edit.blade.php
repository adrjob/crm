    {{ Form::model($chartOfAccount, array('route' => array('chart-of-account.update', $chartOfAccount->id), 'method' => 'PUT')) }}
    <div class="row">
        <div class="form-group col-md-6">
            {{ Form::label('name', __('Name'),['class' => 'col-form-label']) }}
            {{ Form::text('name', null, array('class' => 'form-control','required'=>'required')) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('code', __('Code'),['class' => 'col-form-label']) }}
            {{ Form::text('code', null, array('class' => 'form-control','required'=>'required')) }}
        </div>

        <div class="form-group col-md-6">
            <input class="form-check-input" type="checkbox" value="" class="email-template-checkbox" id="is_enabled" name="is_enabled" {{$chartOfAccount->is_enabled==1?'checked':''}}>
            <label class="form-check-label" for="is_enabled">
                {{Form::label('is_enabled',__('Is Enabled'),array('class'=>'form-control-label')) }}
            </label>
        </div>

        {{-- <div class="form-group col-md-6">
            {{Form::label('is_enabled',__('Is Enabled'),array('class'=>'form-control-label')) }}
            <div class="custom-control custom-switch">
                <input type="checkbox" class="email-template-checkbox custom-control-input" name="is_enabled" id="is_enabled" {{$chartOfAccount->is_enabled==1?'checked':''}}>
                <label class="custom-control-label form-control-label" for="is_enabled"></label>
            </div>
        </div> --}}
        <div class="form-group col-md-12">
            {{ Form::label('description', __('Description'),['class' => 'col-form-label']) }}
            {!! Form::textarea('description', null, ['class'=>'form-control','rows'=>'2']) !!}
        </div>

    </div>
    <div class="modal-footer pr-0">
        <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
        {{Form::submit(__('Update'),array('class'=>'btn  btn-primary'))}}
    </div>
    {{ Form::close() }}


