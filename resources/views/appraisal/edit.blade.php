{{Form::model($appraisal,array('route' => array('appraisal.update', $appraisal->id), 'method' => 'PUT')) }}
<div class="card-body p-0">
    <div class="row">
        <div class="form-group col-md-6">
            {{Form::label('employee',__('Employee'),['class' => 'col-form-label'])}}
            {{Form::select('employee',$employees,null,array('class'=>'form-control','data-toggle="select"','required'=>'required'))}}
        </div>
        <div class="form-group col-md-6">
            {{Form::label('appraisal_date',__('Select Month'),['class' => 'col-form-label'])}}
            {{ Form::month('appraisal_date',null, array('class' => 'form-control')) }}
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {{Form::label('remark',__('Remarks'),['class' => 'col-form-label'])}}
                {{Form::textarea('remark',null,array('class'=>'form-control','rows'=>2))}}
            </div>
        </div>
    </div>

    @foreach($performance as $performances)

    <div class="row">
        <div class="col-md-12 mt-3">
            <h6>{{$performances->name}}</h6>
            <hr class="mt-0">
        </div>
    @foreach($performances->types as $types )

    <div class="col-6">
        {{$types->name}}
    </div>
    <div class="col-6">
        <fieldset id='demo1' class="rating">
            <input class="stars" type="radio" id="technical-5-{{$types->id}}" name="rating[{{$types->id}}]" value="5" {{ (isset($ratings[$types->id]) && $ratings[$types->id] == 5)? 'checked':''}}>
            <label class="full" for="technical-5-{{$types->id}}" title="Awesome - 5 stars"></label>
            <input class="stars" type="radio" id="technical-4-{{$types->id}}" name="rating[{{$types->id}}]" value="4" {{ (isset($ratings[$types->id]) && $ratings[$types->id] == 4)? 'checked':''}}>
            <label class="full" for="technical-4-{{$types->id}}" title="Pretty good - 4 stars"></label>
            <input class="stars" type="radio" id="technical-3-{{$types->id}}" name="rating[{{$types->id}}]" value="3" {{ (isset($ratings[$types->id]) && $ratings[$types->id] == 3)? 'checked':''}}>
            <label class="full" for="technical-3-{{$types->id}}" title="Meh - 3 stars"></label>
            <input class="stars" type="radio" id="technical-2-{{$types->id}}" name="rating[{{$types->id}}]" value="2" {{ (isset($ratings[$types->id]) && $ratings[$types->id] == 2)? 'checked':''}}>
            <label class="full" for="technical-2-{{$types->id}}" title="Kinda bad - 2 stars"></label>
            <input class="stars" type="radio" id="technical-1-{{$types->id}}" name="rating[{{$types->id}}]" value="1" {{ (isset($ratings[$types->id]) && $ratings[$types->id] == 1)? 'checked':''}}>
            <label class="full" for="technical-1-{{$types->id}}" title="Sucks big time - 1 star"></label>
        </fieldset>
    </div>
@endforeach
</div>
@endforeach

</div>
<div class="modal-footer pr-0">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
    {{ Form::submit(__('Update'), ['class' => 'btn  btn-primary']) }}
</div>
{{Form::close()}}



