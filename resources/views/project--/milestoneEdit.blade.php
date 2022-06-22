{{ Form::model($milestone, array('route' => array('project.milestone.update', $milestone->id), 'method' => 'post')) }}
<div class="row">
    <div class="form-group  col-md-6">
        {{ Form::label('title', __('Title')) }}
        {{ Form::text('title', null, array('class' => 'form-control','required'=>'required')) }}
    </div>
    <div class="form-group  col-md-6">
        {{ Form::label('status', __('Status')) }}
        {!! Form::select('status', $status, null,array('class' => 'form-control','data-toggle'=>'select','required'=>'required')) !!}
    </div>
    <div class="form-group  col-md-6">
        {{ Form::label('cost', __('Cost')) }}
        {{ Form::number('cost', null, array('class' => 'form-control','required'=>'required','stage'=>'0.01')) }}
    </div>
    <div class="form-group  col-md-6">
        {{ Form::label('due_date', __('Due Date')) }}
        {{ Form::date('due_date', null, array('class' => 'form-control','required'=>'required')) }}
    </div>
</div>
<div class="row">
    <div class="form-group  col-md-12">
        {{ Form::label('description', __('Description')) }}
        {!! Form::textarea('description', null, ['class'=>'form-control','rows'=>'2']) !!}
    </div>
</div>
<div class="modal-footer pr-0">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
    {{Form::submit(__('Update'),array('class'=>'btn  btn-primary'))}}
</div>
{{ Form::close() }}


