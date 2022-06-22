{{Form::open(array('url'=>'training-type','method'=>'post'))}}
<div class="card-body p-0">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {{Form::label('name',__('Name') ,['class' => 'col-form-label'])}}
                {{Form::text('name',null,array('class'=>'form-control'))}}
            </div>
        </div>
    </div>

</div>
<div class="modal-footer pr-0">
    <div class="modal-footer">
        <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
        {{Form::submit(__('Create'),array('class'=>'btn  btn-primary'))}}
    </div>
</div>
{{Form::close()}}
