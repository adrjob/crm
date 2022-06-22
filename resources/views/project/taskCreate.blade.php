@if($project_id==0)
    {{ Form::open(array('route' => array('project.task.store',0))) }}
@else
    {{ Form::open(array('route' => array('project.task.store',$project_id))) }}
@endif
<div class="row">

    @if($project_id==0)
        <div class="form-group col-md-12">
            {{ Form::label('title', __('Title'),['class' => 'col-form-label']) }}
            {{ Form::text('title', null, array('class' => 'form-control','required'=>'required')) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('project', __('Project'),['class' => 'col-form-label']) }}
            {{ Form::select('project', $projects,'', array('class' => 'form-control multi-select','required'=>'required')) }}
        </div>
    @else
        <div class="form-group col-md-6">
            {{ Form::label('title', __('Title'),['class' => 'col-form-label']) }}
            {{ Form::text('title', null, array('class' => 'form-control','required'=>'required')) }}
        </div>
    @endif
    <div class="form-group col-md-6">
        {{ Form::label('priority', __('Priority'),['class' => 'col-form-label']) }}
        {{ Form::select('priority', $priority,'', array('class' => 'form-control multi-select','required'=>'required')) }}
    </div>
    <div class="form-group col-md-6">
        {{ Form::label('start_date', __('Start Date'),['class' => 'col-form-label']) }}
        {{Form::date('start_date',null,array('class'=>'form-control'))}}
    </div>
    <div class="form-group col-md-6">
        {{ Form::label('due_date', __('Due Date'),['class' => 'col-form-label']) }}
        {{Form::date('due_date',null,array('class'=>'form-control'))}}
    </div>

    @if($project_id==0)
        <div class="form-group col-md-6">
            {{ Form::label('assign_to', __('Assign To'),['class' => 'col-form-label']) }}
            <select class="form-control" name="assign_to" id="assign_to" data-toggle="select" required>

            </select>
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('milestone_id', __('Milestone'),['class' => 'col-form-label']) }}
            <select class="form-control" name="milestone_id" id="milestone_id" data-toggle="select">

            </select>
        </div>
    @else

        <div class="form-group col-md-6">
            {{ Form::label('assign_to', __('Assign To'),['class' => 'col-form-label']) }}
            {!! Form::select('assign_to', $users, null,array('class' => 'form-control multi-select','required'=>'required')) !!}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('milestone_id', __('Milestone'),['class' => 'col-form-label']) }}
            {!! Form::select('milestone_id', $milestones, null,array('class' => 'form-control multi-select')) !!}
        </div>
    @endif
    <div class="form-group col-md-12">
        {{ Form::label('description', __('Description'),['class' => 'col-form-label']) }}
        {{ Form::textarea('description','', array('class' => 'form-control','rows'=>'3')) }}
    </div>

</div>
<div class="modal-footer pr-0">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
    {{Form::submit(__('Create'),array('class'=>'btn  btn-primary'))}}
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