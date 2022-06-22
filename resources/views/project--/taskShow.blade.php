<script>
    // For task timer start
    $(document).on("click", ".start_timer", function() {
        var main_div = $(this).parent().parent().parent();
        var current = $(this);
        var type = $(this).attr('data-type');
        var id = $(this).attr('data-id');
        $.ajax({
            url: "{{ route('project.task.timer') }}",
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                type: type,
                id: id
            },
            dataType: 'JSON',
            success: function(data) {
                clearInterval(timer);
                $('.timer-counter').removeClass('d-block');
                if (data.status == 'success') {
                    main_div.find('.start-div').html(
                        '<div class="timer-counter"></div> <a href="#" class="stop-task finish_timer" data-type="stop" data-id="' +
                        id + '"><i class="far fa-clock"></i> {{ __('Stop Tracking') }}</a>');
                    TrackerTimer(data.start_time);

                }
                toastrs(data.class, data.msg, data.status);
            }
        });
    });

    // For task timer finished
    $(document).on("click", ".finish_timer", function() {
        var main_div = $(this).parent().parent().parent();
        var current = $(this);
        var type = $(this).attr('data-type');
        var id = $(this).attr('data-id');
        $.ajax({
            url: "{{ route('project.task.timer') }}",
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                type: type,
                id: id
            },
            dataType: 'JSON',
            success: function(data) {
                clearInterval(timer);
                $('.timer-counter').removeClass('d-block');
                if (data.status == 'success') {
                    main_div.find('.start-div').html(
                        ' <a href="#" class="start-task start_timer" data-type="start" data-id="' +
                        id + '"><i class="far fa-clock"></i> {{ __('Start Tracking') }} </a>');
                    $('.timer-counter').addClass('d-none');
                    setInterval(function() {
                        location.reload();
                    }, 1000);
                }
                toastrs(data.class, data.msg, data.status);
            }
        });
    });

    @if (!empty($lastTime))
        TrackerTimer("{{ $lastTime->start_time }}");
    @endif
</script>
<div class="row timer_div">
    <div class="col-auto">
        <h5 class="h5">{{ $task->title }}</h5>
    </div>
    <div class="col text-end start-div">
        @if ($task->time_tracking == 0)
            <a href="#" class="start-task start_timer" data-type="start" data-id="{{ $task->id }}"><i
                    class="far fa-clock"></i> {{ __('Start Tracking') }} </a>
        @else
            <div class="timer-counter"></div> <a href="#" class="stop-task finish_timer" data-type="stop"
                data-id="{{ $task->id }}"><i class="far fa-clock"></i> {{ __('Stop Tracking') }}</a>
        @endif
    </div>

</div>


<div class="py-3 my-2 border-top border-bottom">
    <h6 class="text-sm">{{ __('Description') }}:
        @if ($task->priority == 'low')
            <div class="badge badge-pill badge-sm badge-success float-right"> {{ ucfirst($task->priority) }}</div>
        @elseif($task->priority == 'medium')
            <div class="badge badge-pill badge-sm badge-warning float-right"> {{ ucfirst($task->priority) }}</div>
        @elseif($task->priority == 'high')
            <div class="badge badge-pill badge-sm badge-danger float-right"> {{ ucfirst($task->priority) }}</div>
        @endif
    </h6>
    <p class="text-sm mb-0">{{ $task->description }}</p>
</div>

<dl class="row">
    <dt class="col-sm-3"><span class="h6 text-sm mb-0">{{ __('Start Date') }}</span></dt>
    <dd class="col-sm-9"><span class="text-sm">{{ \Auth::user()->dateFormat($task->start_date) }}</span>
    </dd>
    <dt class="col-sm-3"><span class="h6 text-sm mb-0">{{ __('Due Date') }}</span></dt>
    <dd class="col-sm-9"><span class="text-sm">{{ \Auth::user()->dateFormat($task->due_date) }}</span>
    </dd>
    <dt class="col-sm-3"><span class="h6 text-sm mb-0">{{ __('Milestone') }}</span></dt>
    <dd class="col-sm-9"><span
            class="text-sm">{{ !empty($task->milestone) ? $task->milestone->title : '' }}</span></dd>

</dl>
{{-- <ul class="nav nav-pills" id="myTab" role="tablist">
    <li class="nav-item">
        <a class="btn-sm nav-link  active" id="home-tab" data-toggle="tab" href="#checklist-data" role="tab" aria-controls="home" 
        aria-selected="true"> {{__('Checklist')}} </a>
    </li>
    <li class="nav-item">
        <a class="btn-sm nav-link " id="profile-tab" data-toggle="tab" href="#comment-data" role="tab" 
        aria-controls="profile" aria-selected="false"> {{__('Comments')}} </a>
    </li>
    <li class="nav-item">
        <a class="btn-sm nav-link " id="contact-tab" data-toggle="tab" href="#file-data" role="tab" 
        aria-controls="contact" aria-selected="false"> {{__('Files')}} </a>
    </li>
    <li class="nav-item">
        <a class="btn-sm nav-link " id="time-tab" data-toggle="tab" href="#time-tracking" role="tab" 
        aria-controls="contact" aria-selected="false"> {{__('Time Tracking')}} </a>
    </li>
</ul> --}}
<div class="row justify-content-center">
    <!-- [ sample-page ] start -->
    <div class="col-12">
        <div class="p-3 card">
            <ul class="nav nav-pills nav-fill" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="pills-user-tab-1" data-bs-toggle="pill"
                        data-bs-target="#pills-user-1" type="button">{{ __('Checklist') }}</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pills-user-tab-2" data-bs-toggle="pill"
                        data-bs-target="#pills-user-2" type="button">{{ __('Comments') }}</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pills-user-tab-3" data-bs-toggle="pill"
                        data-bs-target="#pills-user-3" type="button">{{ __('Files') }}</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pills-user-tab-4" data-bs-toggle="pill"
                        data-bs-target="#pills-user-4" type="button">{{ __('Time Tracking') }}</button>
                </li>
            </ul>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-user-1" role="tabpanel"
                        aria-labelledby="pills-user-tab-1">
                        <h3 class="mb-0">{{ __('Checklist') }}</h3>
                        <div class="row mt-3">
                            <div class="col-md-11">
                                <div class="row">
                                    <div class="col-md-6 form-label">
                                        <b>{{ __('Progress') }}</b>
                                    </div>
                                    <div class="col-md-6 text-end">
                                        <b>
                                            <span class="progressbar-label custom-label"
                                                style="margin-top: -9px !important;margin-left: .7rem">
                                                0%
                                            </span>
                                        </b>
                                    </div>
                                </div>
                                <div class="text-left">
                                    <div class="custom-widget__item flex-fill">
                                        <div class="custom-widget__progress d-flex  align-items-center">
                                            <div class="progress" style="height: 5px;width: 100%;">
                                                <div class="progress-bar" role="progressbar" aria-valuenow="100"
                                                    aria-valuemin="0" aria-valuemax="100" id="taskProgress"></div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="text-right mb-1">
                                    <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                        data-bs-toggle="collapse" data-bs-target="#form-checklist" aria-expanded="false" 
                                        aria-controls="collapseExample"><i
                                            class="ti ti-plus"></i></a>
                                </div>
                            </div>

                            <form method="POST" id="form-checklist" class="collapse col-md-12" data-action="{{ route('project.task.checklist.store',[$task->id]) }}">
                                <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                                <div class="form-group">
                                    <label class="form-label text-start">{{__('Name')}}</label>
                                    <input type="text" name="name" class="form-control" required placeholder="{{__('Checklist Name')}}">
                                </div>
                                <div class="text-end">
                                    <div class="btn-group mb-2 ml-2 d-none d-sm-inline-block">
                                        <button type="button" class="btn btn-primary form-checklist">{{ __('Create')}}</button>
                                    </div>
                                </div>
                            </form> 

                            <div class="row mt-2">
                                <div class="col-md-11">
                                    <div class="checklist" id="check-list">
                                        @foreach($task->taskCheckList as $checkList)
                                            <div class="card border checklist-div">
                                                <div class="px-3 py-2 row align-items-center">
                                                    <div class="col-10">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" id="checklist-{{$checkList->id}}" class="custom-control-input taskCheck" {{($checkList->status==1)?'checked':''}} value="{{$checkList->id}}" data-url="{{route('project.task.checklist.update',[$checkList->task_id,$checkList->id])}}">
                                                            <label class="custom-control-label h6 text-sm" for="checklist-{{$checkList->id}}">{{$checkList->name}}</label>
                                                        </div>
                                                    </div>
                                                    <div class="action-btn bg-danger ms-2">
                                                        <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center delete-checklist" data-url="{{route('project.task.checklist.destroy',[$checkList->task_id,$checkList->id])}}">
                                                            <i class="ti ti-trash text-white"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="tab-pane fade" id="pills-user-2" role="tabpanel" aria-labelledby="pills-user-tab-2">
                        <h3 class="mb-0">{{ __('Comment') }}</h3>

                        <div class="comment-holder">
                            <div class="list-group list-group-flush" id="comments">
                                @foreach($task->comments as $comment)
                                    <div class="list-group-item comment-div">
                                        <div class="row">
                                            <div class="col ml-n2">
                                                <a href="#!" class="d-block h6 mb-0">{{(!empty($comment->user)?$comment->user->name:'')}}</a>
                                                <div>
                                                    <small>{{$comment->comment}}</small>
                                                </div>
                                            </div>
                                            <div class="action-btn bg-danger ms-2">
                                                <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center  delete-comment" data-url="{{route('project.task.comment.destroy',[$comment->id])}}">
                                                    <i class="ti ti-trash text-white"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <li class="col-12 border-0 ">
                            <form   id="form-comment" data-action="{{route('project.task.comment.store',[$task->project_id,$task->id])}}">
                                <div class="form-group mb-0 form-send w-100">
                                    <input type="text" class="form-control" name="comment" placeholder="Write your comment..." >
                                    <button class="btn btn-send"><i class="f-16 text-primary ti ti-brand-telegram"></i></button>
                                </div>
                            </form>
                        </li>

                        

                            {{-- <form method="post" id="form-comment" data-action="{{route('project.task.comment.store',[$task->project_id,$task->id])}}">
                                <textarea class="form-control" name="comment" placeholder="{{ __('Write message')}}" id="example-textarea" rows="3" required></textarea>
                                <div class="text-right">
                                    <div class="btn-group mt-2 d-none d-sm-inline-block">
                                        <button type="button" class="btn btn-sm btn-primary rounded-pill">{{ __('Save')}}</button>
                                    </div>
                                </div>
                            </form> --}}
                    </div>

                    
                    <div class="tab-pane fade" id="pills-user-3" role="tabpanel" aria-labelledby="pills-user-tab-3">
                        <h3 class="mb-0">{{ __('Files') }}</h3>
                        <div class="row mt-3">
                            <form method="post" id="form-file" enctype="multipart/form-data" data-url="{{ route('project.task.comment.file.store',$task->id) }}">
                                @csrf
                                <input type="file" class="form-control mb-2" name="file" id="file">
                                <span class="invalid-feedback" id="file-error" role="alert">
                                        <strong></strong>
                                    </span>
                                <div class="text-end">
                                    <div class="btn-group mb-2 ml-2 d-none d-sm-inline-block">
                                        <button type="submit" class="btn btn-primary">{{ __('Upload')}}</button>
                                    </div>
                                </div>
                            </form>

                            <div class="row">
                                <div class="col-md-12">
                                    @foreach($task->taskFiles as $file)
                                        <div class="card mb-3 border shadow-none" id="comments-file">
                                            <div class="px-3 py-3">
                                                <div class="row align-items-center">
                                                    <div class="col ml-n2">
                                                        <h6 class="text-sm mb-0">
                                                            <a href="#!">{{$file->name}}</a>
                                                        </h6>
                                                        <p class="card-text small text-muted">
                                                            {{$file->file_size}}
                                                        </p>
                                                    </div>
                                                    <div class="action-btn bg-info ms-2">
                                                        <a download href="{{asset(Storage::url('uploads/tasks/'.$file->file))}}" class="mx-3 btn btn-sm d-inline-flex align-items-center">
                                                            <i class="ti ti-download text-white"></i>
                                                        </a>
                                                    </div>
                                                    <div class="action-btn bg-danger ms-2">
                                                        <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center delete-comment-file" data-url="{{route('project.task.comment.file.destroy',[$file->id])}}">
                                                            <i class="ti ti-trash text-white"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                        </div>
                      
                    </div>
                    <div class="tab-pane fade" id="pills-user-4" role="tabpanel" aria-labelledby="pills-user-tab-4">
                        <h3 class="mb-0">{{ __('Time Tracking') }}</h3>
                        <div class="row mt-3">
                            <div class="col-xl-12">
                                <div class="card">
                                    <div class="card-header card-body table-border-style" id="comments">
                                        <h5></h5>
                                        <div class="table-responsive">
                                            <table class="table" id="pc-dt-simple">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">{{__('Start Time')}}</th>
                                                        <th scope="col">{{__('End Time')}}</th>
                                                        <th scope="col">{{__('Time')}}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($task->taskTimer as $time)
                                                        <tr>
                                                            <td>{{ $time->start_time }}</td>
                                                            <td>{{ $time->end_time }}</td>
                                                            <td>{{ $task->taskTime($time->start_time,$time->end_time) }}</td>
                                                        </tr>
                                                    @endforeach
                                                    <tr>
                                                        <td colspan="2" class="text-right">{{__('Total Time')}} :</td>
                                                        <td>{{ $task->totalTime() }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                      
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->
</div>
{{-- <div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active mt-3" id="checklist-data" role="tabpanel" aria-labelledby="home-tab">
        <div class="progress-wrap">
            <div class="tab-pane fad active" id="tab_1_3">
                <div class="row">
                    <div class="col-md-11">
                        <div class="row">
                            <div class="col-md-6">
                                <b>{{__('Progress')}}</b>
                            </div>
                            <div class="col-md-6 text-end">
                                <b>
                                <span class="progressbar-label custom-label" style="margin-top: -9px !important;margin-left: .7rem">
                                    0%
                                </span>
                                </b>
                            </div>
                        </div>
                        <div class="text-left">
                            <div class="custom-widget__item flex-fill">
                                <div class="custom-widget__progress d-flex  align-items-center">
                                    <div class="progress" style="height: 5px;width: 100%;">
                                        <div class="progress-bar" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" id="taskProgress"></div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-1">
                        <div class="text-right mb-1">
                            <a href="#" class="action-item" data-toggle="collapse" data-target="#form-checklist"><i class="ti ti-plus"></i></a>
                        </div>
                    </div>

                    <form method="POST" id="form-checklist" class="collapse col-md-12" data-action="{{ route('project.task.checklist.store',[$task->id]) }}">
                        <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                        <div class="form-group">
                            <label>{{__('Name')}}</label>
                            <input type="text" name="name" class="form-control" required placeholder="{{__('Checklist Name')}}">
                        </div>
                        <div class="text-right">
                            <div class="btn-group mb-2 ml-2 d-none d-sm-inline-block">
                                <button type="button" class="btn btn-sm btn-primary rounded-pill form-checklist">{{ __('Create')}}</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="row mt-2">
                    <div class="col-md-11">
                        <div class="checklist" id="check-list">
                            @foreach ($task->taskCheckList as $checkList)
                                <div class="card border checklist-div">
                                    <div class="px-3 py-2 row align-items-center">
                                        <div class="col-10">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" id="checklist-{{$checkList->id}}" class="custom-control-input taskCheck" {{($checkList->status==1)?'checked':''}} value="{{$checkList->id}}" data-url="{{route('project.task.checklist.update',[$checkList->task_id,$checkList->id])}}">
                                                <label class="custom-control-label h6 text-sm" for="checklist-{{$checkList->id}}">{{$checkList->name}}</label>
                                            </div>
                                        </div>
                                        <div class="col-auto card-meta d-inline-flex align-items-center ml-sm-auto">
                                            <a href="#" class="action-item delete-checklist" data-url="{{route('project.task.checklist.destroy',[$checkList->task_id,$checkList->id])}}">
                                                <i class="ti ti-trash"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-pane fade mt-3" id="comment-data" role="tabpanel" aria-labelledby="profile-tab">
        <div class="form-group m-0">
            <form method="post" id="form-comment" data-action="{{route('project.task.comment.store',[$task->project_id,$task->id])}}">
                <textarea class="form-control" name="comment" placeholder="{{ __('Write message')}}" id="example-textarea" rows="3" required></textarea>
                <div class="text-right">
                    <div class="btn-group mt-2 d-none d-sm-inline-block">
                        <button type="button" class="btn btn-sm btn-primary rounded-pill">{{ __('Save')}}</button>
                    </div>
                </div>
            </form>
            <div class="comment-holder">
                <div class="list-group list-group-flush" id="comments">
                    @foreach ($task->comments as $comment)
                        <div class="list-group-item comment-div">
                            <div class="row">
                                <div class="col ml-n2">
                                    <a href="#!" class="d-block h6 mb-0">{{(!empty($comment->user)?$comment->user->name:'')}}</a>
                                    <div>
                                        <small>{{$comment->comment}}</small>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <a href="#" class="action-item  delete-comment" data-url="{{route('project.task.comment.destroy',[$comment->id])}}">
                                        <i class="ti ti-trash"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="tab-pane fade mt-3" id="file-data" role="tabpanel" aria-labelledby="contact-tab">
        <div class="form-group m-0">
            <form method="post" id="form-file" enctype="multipart/form-data" data-url="{{ route('project.task.comment.file.store',$task->id) }}">
                @csrf
                <input type="file" class="form-control mb-2" name="file" id="file">
                <span class="invalid-feedback" id="file-error" role="alert">
                        <strong></strong>
                    </span>
                <div class="text-right">
                    <div class="btn-group mb-2 ml-2 d-none d-sm-inline-block">
                        <button type="submit" class="btn btn-sm btn-primary rounded-pill">{{ __('Upload')}}</button>
                    </div>
                </div>
            </form>
            <div class="row">
                <div class="col-md-12">
                    @foreach ($task->taskFiles as $file)
                        <div class="card mb-3 border shadow-none" id="comments-file">
                            <div class="px-3 py-3">
                                <div class="row align-items-center">
                                    <div class="col ml-n2">
                                        <h6 class="text-sm mb-0">
                                            <a href="#!">{{$file->name}}</a>
                                        </h6>
                                        <p class="card-text small text-muted">
                                            {{$file->file_size}}
                                        </p>
                                    </div>
                                    <div class="col-auto actions">
                                        <a download href="{{asset(Storage::url('uploads/tasks/'.$file->file))}}" class="action-item">
                                            <i class="ti ti-trash"></i>
                                        </a>
                                        <a href="#" class="action-item delete-comment-file" data-url="{{route('project.task.comment.file.destroy',[$file->id])}}">
                                            <i class="ti ti-trash"></i>
                                        </a>

                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="tab-pane fade mt-3" id="time-tracking" role="tabpanel" aria-labelledby="profile-tab">
        <div class="list-group list-group-flush" id="comments">
            <div class="table-responsive">
                <table class="table align-items-center" id="myTable">
                    <thead>
                    <tr>
                        <th scope="col">{{__('Start Time')}}</th>
                        <th scope="col">{{__('End Time')}}</th>
                        <th scope="col">{{__('Time')}}</th>
                    </tr>
                    </thead>
                    <tbody class="list">

                    @foreach ($task->taskTimer as $time)
                        <tr>
                            <td>{{ $time->start_time }}</td>
                            <td>{{ $time->end_time }}</td>
                            <td>{{ $task->taskTime($time->start_time,$time->end_time) }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="2" class="text-right">{{__('Total Time')}} :</td>
                        <td>{{ $task->totalTime() }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div> --}}
