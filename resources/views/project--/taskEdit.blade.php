{{ Form::model($task, array('route' => array('project.task.update', $task->id), 'method' => 'post')) }}
<div class="row">
    <div class="form-group col-md-6">
        {{ Form::label('title', __('Title')) }}
        {{ Form::text('title', null, array('class' => 'form-control','required'=>'required')) }}
    </div>
    <div class="form-group col-md-6">
        {{ Form::label('priority', __('Priority')) }}
        {{ Form::select('priority', $priority,null, array('class' => 'form-control','data-toggle'=>'select','required'=>'required')) }}
    </div>
    <div class="form-group col-md-6">
        {{ Form::label('start_date', __('Start Date')) }}
        {{Form::date('start_date',null,array('class'=>'form-control'))}}
    </div>
    <div class="form-group col-md-6">
        {{ Form::label('due_date', __('Due Date')) }}
        {{Form::date('due_date',null,array('class'=>'form-control'))}}
    </div>
    <div class="form-group col-md-6">
        {{ Form::label('assign_to', __('Assign To')) }}
        {!! Form::select('assign_to', $users, null,array('class' => 'form-control','data-toggle'=>'select','required'=>'required')) !!}
    </div>

    <div class="form-group col-md-6">
        {{ Form::label('milestone_id', __('Milestone')) }}
        {!! Form::select('milestone_id', $milestones, null,array('class' => 'form-control','data-toggle'=>'select')) !!}
    </div>

    <div class="form-group col-md-12">
        {{ Form::label('description', __('Description')) }}
        {{ Form::textarea('description',null, array('class' => 'form-control','rows'=>'3')) }}
    </div>
</div>
<div class="modal-footer pr-0">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
    {{Form::submit(__('Update'),array('class'=>'btn  btn-primary'))}}
</div>
{{ Form::close() }}
