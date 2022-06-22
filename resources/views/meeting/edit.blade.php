{{ Form::model($meeting, array('route' => array('meeting.update', $meeting->id), 'method' => 'PUT')) }}
<div class="row">
    <div class="form-group col-md-6">
        {{ Form::label('department', __('Department'),['class' => 'col-form-label']) }}
        {{ Form::select('department', $departments,null, array('class' => 'form-control multi-select')) }}
    </div>
    <div class="form-group col-md-6">
        {{ Form::label('designation', __('Designation'),['class' => 'col-form-label']) }}
        {{ Form::select('designation', $designations,null, array('class' => 'form-control multi-select')) }}
    </div>

    <div class="form-group col-md-12">
        {{Form::label('title',__('Title'),['class' => 'col-form-label'])}}
        {{Form::text('title',null,array('class'=>'form-control'))}}
    </div>
    <div class="form-group col-md-6">
        {{Form::label('date',__('Date'),['class' => 'col-form-label'])}}
        {{Form::date('date',null,array('class'=>'form-control'))}}
    </div>
    <div class="form-group col-md-6">
        {{Form::label('time',__('Time'),['class' => 'col-form-label'])}}
        {{Form::time('time',null,array('class'=>'form-control'))}}
    </div>
    <div class="form-group col-md-12">
        {{Form::label('notes',__('Notes'),['class' => 'col-form-label'])}}
        {{Form::textarea('notes',null,array('class'=>'form-control','rows'=>'2'))}}
    </div>
</div>
<div class="modal-footer">
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