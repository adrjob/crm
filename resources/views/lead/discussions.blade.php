{{ Form::model($lead, array('route' => array('lead.discussion.store', $lead->id), 'method' => 'POST')) }}
<div class="form-label">
    {{ Form::label('comment', __('Message'),['class' => 'col-form-label']) }}
    {{ Form::textarea('comment', null, array('class' => 'form-control','rows'=>3)) }}
</div>
<div class="modal-footer pr-0">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
    {{Form::submit(__('Add'),array('class'=>'btn  btn-primary'))}}
</div>
{{ Form::close() }}
