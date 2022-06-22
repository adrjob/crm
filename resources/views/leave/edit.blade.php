{{ Form::model($leave, array('route' => array('leave.update', $leave->id), 'method' => 'PUT')) }}
<div class="row">
    @if(\Auth::user()->type=='company')
        <div class="form-group col-md-12">
            {{ Form::label('employee_id', __('Employee'),['class' => 'col-form-label']) }}
            {{ Form::select('employee_id', $employees,null, array('class' => 'form-control multi-select','required'=>'required')) }}
        </div>
    @endif
    <div class="form-group col-md-12">
        {{Form::label('leave_type',__('Leave Type'),['class' => 'col-form-label'])}}
        <select name="leave_type" id="leave_type" class="form-control" data-toggle="select" required>
            @foreach($leaveTypes as $type)
                <option value="{{ $type->id }}">{{ $type->title }} (<p class="float-right pr-5">{{ $type->days }}</p>)</option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-md-6">
        {{Form::label('start_date',__('Start Date'),['class' => 'col-form-label'])}}
        {{Form::date('start_date',null,array('class'=>'form-control','required'=>'required'))}}
    </div>
    <div class="form-group col-md-6">
        {{Form::label('end_date',__('End Date'),['class' => 'col-form-label'])}}
        {{Form::date('end_date',null,array('class'=>'form-control','required'=>'required'))}}
    </div>
    <div class="form-group col-md-12">
        {{Form::label('leave_reason',__('Leave Reason'),['class' => 'col-form-label'])}}
        {{Form::textarea('leave_reason',null,array('class'=>'form-control','rows'=>'3','required'=>'required'))}}
    </div>
    <div class="form-group col-md-12">
        {{Form::label('remark',__('Remark'),['class' => 'col-form-label'])}}
        {{Form::textarea('remark',null,array('class'=>'form-control','rows'=>'3'))}}
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