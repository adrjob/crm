{{ Form::open(array('route' => array('project.note.store',$project->id))) }}
<div class="row">
    <div class="form-group  col-md-12">
        {{ Form::label('title', __('Title')) }}
        {{ Form::text('title', '', array('class' => 'form-control','required'=>'required')) }}
    </div>
    <div class="form-group  col-md-12">
        {{ Form::label('description', __('Description')) }}
        {!! Form::textarea('description', null, ['class'=>'form-control','rows'=>'5']) !!}
    </div>
</div>

<div class="modal-footer pr-0">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
    {{Form::submit(__('Create'),array('class'=>'btn  btn-primary'))}}
</div>
{{ Form::close() }}


