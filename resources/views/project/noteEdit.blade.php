{{ Form::model($note, array('route' => array('project.note.update', $project_id,$note->id), 'method' => 'post')) }}
<div class="row">
    <div class="form-group  col-md-12">
        {{ Form::label('title', __('Title'),['class' => 'col-form-label']) }}
        {{ Form::text('title', null, array('class' => 'form-control','required'=>'required')) }}
    </div>
    <div class="form-group  col-md-12">
        {{ Form::label('description', __('Description'),['class' => 'col-form-label']) }}
        {!! Form::textarea('description', null, ['class'=>'form-control','rows'=>'5']) !!}
    </div>
</div>

<div class="modal-footer pr-0">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
    {{Form::submit(__('Update'),array('class'=>'btn  btn-primary'))}}
</div>
{{ Form::close() }}


