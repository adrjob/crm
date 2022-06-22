{{ Form::model($file, array('route' => array('project.file.update', $project_id,$file->id),'enctype'=>"multipart/form-data", 'method' => 'post')) }}
<div class="row">
    <div class="form-group  col-md-12">
        {{ Form::label('file', __('File'),['class' => 'col-form-label']) }}
        {{ Form::file('file', array('class' => 'form-control')) }}
    </div>
    <div class="form-group  col-md-12">
        {{ Form::label('description', __('Description'),['class' => 'col-form-label']) }}
        {!! Form::textarea('description', null, ['class'=>'form-control','rows'=>'3']) !!}
    </div>
</div>
<div class="modal-footer pr-0">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
    {{Form::submit(__('Update'),array('class'=>'btn  btn-primary'))}}
</div>
{{ Form::close() }}


