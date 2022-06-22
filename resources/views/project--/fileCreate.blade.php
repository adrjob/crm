{{ Form::open(array('route' => array('project.file.store',$project->id),'enctype'=>"multipart/form-data")) }}
<div class="row">
    <div class="form-group  col-md-12">
        {{ Form::label('file', __('File')) }}
        {{ Form::file('file', array('class' => 'form-control','required'=>'required')) }}
    </div>
    <div class="form-group  col-md-12">
        {{ Form::label('description', __('Description')) }}
        {!! Form::textarea('description', null, ['class'=>'form-control','rows'=>'3']) !!}
    </div>
</div>

<div class="modal-footer pr-0">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
    {{Form::submit(__('Create'),array('class'=>'btn  btn-primary'))}}
</div>
{{ Form::close() }}


