{{ Form::open(array('route' => array('project.milestone.store',$project->id))) }}
<div class="row">
    <div class="form-group  col-md-6">
        {{ Form::label('title', __('Title'),['class' => 'col-form-label']) }}
        {{ Form::text('title', '', array('class' => 'form-control','required'=>'required')) }}
    </div>
    <div class="form-group  col-md-6">
        {{ Form::label('status', __('Status'),['class' => 'col-form-label']) }}
        {!! Form::select('status', $status, null,array('class' => 'form-control','data-toggle'=>'select','required'=>'required')) !!}
    </div>
    <div class="form-group  col-md-6">
        {{ Form::label('cost', __('Cost'),['class' => 'col-form-label']) }}
        {{ Form::number('cost', '', array('class' => 'form-control','required'=>'required','stage'=>'0.01')) }}
    </div>
    <div class="form-group  col-md-6">
        {{ Form::label('due_date', __('Due Date'),['class' => 'col-form-label']) }}
        {{ Form::date('due_date', '', array('class' => 'form-control','required'=>'required')) }}
    </div>
</div>
<div class="row">
    <div class="form-group  col-md-12">
        {{ Form::label('description', __('Description'),['class' => 'col-form-label']) }}
        {!! Form::textarea('description', null, ['class'=>'form-control','rows'=>'2']) !!}
    </div>
</div>
<div class="modal-footer pr-0">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
    {{Form::submit(__('Create'),array('class'=>'btn  btn-primary'))}}
</div>
{{ Form::close() }}


