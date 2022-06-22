{{ Form::model($task, array('route' => array('project.task.update', $task->id), 'method' => 'post')) }}
<div class="row">
    <div class="form-group col-md-6">
        {{ Form::label('title', __('Title'),['class' => 'col-form-label']) }}
        {{ Form::text('title', null, array('class' => 'form-control','required'=>'required')) }}
    </div>
    <div class="form-group col-md-6">
        {{ Form::label('priority', __('Priority'),['class' => 'col-form-label']) }}
        {{ Form::select('priority', $priority,null, array('class' => 'form-control multi-select','required'=>'required')) }}
    </div>
    <div class="form-group col-md-6">
        {{ Form::label('start_date', __('Start Date'),['class' => 'col-form-label']) }}
        {{Form::date('start_date',null,array('class'=>'form-control'))}}
    </div>
    <div class="form-group col-md-6">
        {{ Form::label('due_date', __('Due Date'),['class' => 'col-form-label']) }}
        {{Form::date('due_date',null,array('class'=>'form-control'))}}
    </div>
    <div class="form-group col-md-6">
        {{ Form::label('assign_to', __('Assign To'),['class' => 'col-form-label']) }}
        {!! Form::select('assign_to', $users, null,array('class' => 'form-control multi-select','required'=>'required')) !!}
    </div>

    <div class="form-group col-md-6">
        {{ Form::label('milestone_id', __('Milestone'),['class' => 'col-form-label']) }}
        {!! Form::select('milestone_id', $milestones, null,array('class' => 'form-control multi-select')) !!}
    </div>

    <div class="form-group col-md-12">
        {{ Form::label('description', __('Description'),['class' => 'col-form-label']) }}
        {{ Form::textarea('description',null, array('class' => 'form-control','rows'=>'3')) }}
    </div>
</div>
<div class="modal-footer pr-0">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
    {{Form::submit(__('Update'),array('class'=>'btn  btn-primary'))}}
</div>
{{ Form::close() }}


<script src="{{asset('assets/js/plugins/choices.min.js')}}"></script>

<script>
    if ($(".multi-select").length > 0) {
              $( $(".multi-select") ).each(function( index,element ) {
                  var id = $(element).attr('id');
                     var multipleCancelButton = new Choices(
                          '#'+id, {
                              removeItemButton: true,
                          }
                      );
              });
         }
</script>