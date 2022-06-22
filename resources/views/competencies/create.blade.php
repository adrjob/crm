{{Form::open(array('url'=>'competencies','method'=>'post'))}}
    <div class="card-body p-0">
    <div class="row ">
        <div class="col-md-12">
            <div class="form-group">
                {{Form::label('name',__('Name') ,['class' => 'col-form-label'])}}
                {{Form::text('name',null,array('class'=>'form-control'))}}
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                {{Form::label('type',__('Type') ,['class' => 'col-form-label'])}}
                {{Form::select('type',$performance,null,array('class'=>'form-control multi-select'))}}
            </div>
        </div>

    </div>
    <div class="modal-footer">
        <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
        {{Form::submit(__('Create'),array('class'=>'btn  btn-primary'))}}
    </div>
    {{Form::close()}}

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