{{ Form::model($event,array('route' => array('event.update',$event),'method'=>'PUT')) }}
<div class="row">
    <div class="form-group col-md-6">
        {{ Form::label('name', __('Event title'),['class' => 'col-form-label']) }}
        {{ Form::text('name', null, array('class' => 'form-control','required'=>'required')) }}
    </div>
    <div class="form-group col-md-6">
        {{ Form::label('where', __('Where'),['class' => 'col-form-label']) }}
        {{ Form::text('where', null, array('class' => 'form-control','required'=>'required')) }}
    </div>

    <div class="form-group col-md-6">
        {{ Form::label('start_date', __('Start Date'),['class' => 'col-form-label']) }}
        {{ Form::date('start_date', null, array('class' => 'form-control','required'=>'required')) }}
    </div>
    <div class="form-group col-md-6">
        {{ Form::label('start_time', __('Start Time'),['class' => 'col-form-label']) }}
        {{ Form::time('start_time', null, array('class' => 'form-control','required'=>'required')) }}
    </div>
    <div class="form-group col-md-6">
        {{ Form::label('end_date', __('End Date'),['class' => 'col-form-label']) }}
        {{ Form::date('end_date', null, array('class' => 'form-control','required'=>'required')) }}
    </div>
    <div class="form-group col-md-6">
        {{ Form::label('end_time', __('End Time'),['class' => 'col-form-label']) }}
        {{ Form::time('end_time', null, array('class' => 'form-control','required'=>'required')) }}
    </div>

    <div class="form-group col-md-12">
        <label class="form-control-label d-block mb-3">{{__('Status color')}}</label>
        <div class="btn-group btn-group-toggle btn-group-colors event-tag mb-0" data-toggle="buttons">
            <label class="btn bg-info mr-2 {{($event->color=='bg-info')?'active':''}}">
                <input type="radio" name="color" value="bg-info" autocomplete="off" style="display: none; ">
            </label>
            <label class="btn bg-warning mr-2 {{($event->color=='bg-warning')?'active':''}}">
                <input type="radio" name="color" value="bg-warning" autocomplete="off" style="display: none; ">
            </label>
            <label class="btn bg-danger mr-2 {{($event->color=='bg-danger')?'active':''}}">
                <input type="radio" name="color" value="bg-danger" autocomplete="off" style="display: none; ">
            </label>
            <label class="btn bg-success mr-2 {{($event->color=='bg-success')?'active':''}}">
                <input type="radio" name="color" value="bg-success" autocomplete="off" style="display: none; ">
            </label>
            <label class="btn bg-secondary mr-2 {{($event->color=='bg-secondary')?'active':''}}">
                <input type="radio" name="color" value="bg-secondary" autocomplete="off" style="display: none; ">
            </label>
            <label class="btn bg-primary mr-2 {{($event->color=='bg-primary')?'active':''}}">
                <input type="radio" name="color" value="bg-primary" autocomplete="off" style="display: none; ">
            </label>
        </div>
    </div>
    <div class="form-group col-md-12">
        {{ Form::label('description', __('Description'),['class' => 'col-form-label']) }}
        {!! Form::textarea('description', null, ['class'=>'form-control','rows'=>'3']) !!}
    </div>
    <div class="modal-footer pr-0">
        <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
        {{Form::submit(__('Update'),array('class'=>'btn  btn-primary'))}}
    </div>
</div>
{{ Form::close() }}


