{{ Form::open(array('route' => array('project.comment.store',$project_id,$comment_id),'enctype'=>"multipart/form-data")) }}
<div class="row">
    <input type="hidden" name="parent" value="{{$comment_id}}">
    <div class="form-group  col-md-12">
        {{ Form::label('comment', __('Comment')) }}
        {!! Form::textarea('comment', null, ['class'=>'form-control','required','rows'=>'3']) !!}
    </div>
    <div class="form-group  col-md-12">
        {{ Form::label('file', __('File')) }}
        {{ Form::file('file', array('class' => 'form-control')) }}
    </div>
</div>

<div class="modal-footer pr-0">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
    {{Form::submit(__('Post'),array('class'=>'btn  btn-primary'))}}
</div>
{{ Form::close() }}


