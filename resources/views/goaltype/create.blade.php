<div class=" bg-none card-box">
    {{Form::open(array('url'=>'goaltype','method'=>'post'))}}
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {{Form::label('name',__('Name'),['class' => 'col-form-label'])}}
                {{Form::text('name',null,array('class'=>'form-control','placeholder'=>__('Enter Goal Type Name')))}}
                @error('name')
                <span class="invalid-name" role="alert">
                    <strong class="text-danger">{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
            {{Form::submit(__('Create'),array('class'=>'btn  btn-primary'))}}
        </div>
    </div>
    {{Form::close()}}
</div>
