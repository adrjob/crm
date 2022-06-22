{{ Form::open(array('url' => 'lead')) }}
<div class="row">
    <div class="form-group col-md-6">
        {{ Form::label('subject', __('Subject'),['class' => 'col-form-label']) }}
        {{ Form::text('subject', null, array('class' => 'form-control','required'=>'required')) }}
    </div>
    <div class="form-group col-md-6">
        {{ Form::label('user_id', __('Employee'),['class' => 'col-form-label']) }}
        {{ Form::select('user_id', $employees,'', array('class' => 'form-control multi-select','required'=>'required')) }}
    </div>
    <div class="form-group col-md-6">
        {{ Form::label('name', __('Name'),['class' => 'col-form-label']) }}
        {{ Form::text('name', null, array('class' => 'form-control','required'=>'required')) }}
    </div>
    <div class="form-group col-md-6">
        {{ Form::label('email', __('Email'),['class' => 'col-form-label']) }}
        {{ Form::text('email', null, array('class' => 'form-control','required'=>'required')) }}
    </div>
    <div class="form-group col-md-6">
        {{ Form::label('phone_no', __('Phone No'),['class' => 'col-form-label']) }}
        {{ Form::text('phone_no', null, array('class' => 'form-control','required'=>'required')) }}
    </div>
</div>
<div class="modal-footer pr-0">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
    {{ Form::submit(__('Create'), ['class' => 'btn  btn-primary']) }}
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