@extends('layouts.admin')
@php
    $profile=asset(Storage::url('uploads/avatar'));
@endphp
@push('script-page')
    <script src="{{ asset('assets/js/plugins/dragula.min.js') }}"></script>
    <script>
        !function (a) {
            "use strict";
            var t = function () {
                this.$body = a("body")
            };
            t.prototype.init = function () {
                a('[data-plugin="dragula"]').each(function () {
                    var t = a(this).data("containers"), n = [];
                    if (t) for (var i = 0; i < t.length; i++) n.push(a("#" + t[i])[0]); else n = [a(this)[0]];
                    var r = a(this).data("handleclass");
                    r ? dragula(n, {
                        moves: function (a, t, n) {
                            return n.classList.contains(r)
                        }
                    }) : dragula(n).on('drop', function (el, target, source, sibling) {

                        var id = $(el).attr('data-id');
                        var order = [];
                        $("#" + target.id).each(function () {
                            order[$(this).index()] = $(this).attr('data-id');
                        });


                        var old_status = $("#" + source.id).data('status');
                        var new_status = $("#" + target.id).data('status');
                        var stage_id = $(target).attr('data-id');

                        $("#" + source.id).parent().find('.count').text($("#" + source.id + " > div").length);
                        $("#" + target.id).parent().find('.count').text($("#" + target.id + " > div").length);

                        $.ajax({
                            url: '{{route('project.task.order')}}',
                            type: 'POST',
                            data: {task_id: id, stage_id: stage_id, order: order, old_status: old_status, new_status: new_status, "_token": $('meta[name="csrf-token"]').attr('content')},
                            success: function (data) {
                                toastrs('Success', 'Task successfully updated', 'success');
                            },
                            error: function (data) {
                                data = data.responseJSON;
                                toastrs('{{__("Error")}}', data.error, 'error')
                            }
                        });
                    });
                })
            }, a.Dragula = new t, a.Dragula.Constructor = t
        }(window.jQuery), function (a) {
            "use strict";

            a.Dragula.init()

        }(window.jQuery);
    </script>

    <script>

        $(document).on("click", ".status", function () {
            var status = $(this).attr('data-id');
            var url = $(this).attr('data-url');

            $.ajax({
                url: url,
                type: 'POST',
                data: {status: status, "_token": $('meta[name="csrf-token"]').attr('content')},
                success: function (data) {
                    $('#change-project-status').submit();
                    location.reload();
                }
            });
        });
    </script>
    <script>
        $(document).on('click', '.form-checklist', function (e) {
            e.preventDefault();
            $.ajax({
                url: $("#form-checklist").data('action'),
                type: 'POST',
                data: $('#form-checklist').serialize(),
                dataType: 'JSON',
                success: function (data) {
                    toastrs('Success', '{{ __("Checklist successfully created.")}}', 'success');

                    var html = '<div class="card border draggable-item shadow-none">\n' +
                        '                                    <div class="px-3 py-2 row align-items-center">\n' +
                        '                                        <div class="col-10">\n' +
                        '                                            <div class="custom-control custom-checkbox">\n' +
                        '                                                <input type="checkbox" id="checklist-' + data.id + '" class="custom-control-input taskCheck"  value="' + data.id + '" data-url="' + data.updateUrl + '">\n' +
                        '                                                <label class="custom-control-label h6 text-sm" for="checklist-' + data.id + '">' + data.name + '</label>\n' +
                        '                                            </div>\n' +
                        '                                        </div>\n' +
                        '                                        <div class="col-auto card-meta d-inline-flex align-items-center ml-sm-auto">\n' +
                        '                                            <a href="#" class="action-item delete-checklist" data-url="' + data.deleteUrl + '">\n' +
                        '                                                <i class="ti ti-trash"></i>\n' +
                        '                                            </a>\n' +
                        '                                        </div>\n' +
                        '                                    </div>\n' +
                        '                                </div>';


                    $("#check-list").prepend(html);
                    $("#form-checklist input[name=name]").val('');
                    $("#form-checklist").collapse('toggle');
                },
            });
        });
        $(document).on("click", ".delete-checklist", function () {
            if (confirm('Are You Sure ?')) {
                var checklist = $(this).parent().parent().parent();

                $.ajax({
                    url: $(this).attr('data-url'),
                    type: 'DELETE',
                    data: {_token: $('meta[name="csrf-token"]').attr('content')},
                    dataType: 'JSON',
                    success: function (data) {
                        toastrs('Success', '{{ __("Checklist successfully deleted.")}}', 'success');
                        checklist.remove();
                    },
                    error: function (data) {
                        data = data.responseJSON;
                        if (data.message) {
                            toastrs('Error', data.message, 'error');
                        } else {
                            toastrs('Error', '{{ __("Some Thing Is Wrong!")}}', 'error');
                        }
                    }
                });
            }
        });
        var checked = 0;
        var count = 0;
        var percentage = 0;
        $(document).on("change", "#check-list input[type=checkbox]", function () {
            $.ajax({
                url: $(this).attr('data-url'),
                type: 'post',
                data: {_token: $('meta[name="csrf-token"]').attr('content')},
                success: function (data) {
                    toastrs('Success', '{{ __("Checklist successfully updated.")}}', 'success');
                },
                error: function (data) {
                    data = data.responseJSON;
                    toastrs('Error', '{{ __("Something is wrong.")}}', 'error');
                }
            });
            taskCheckbox();
        });


        $(document).on('click', '#form-comment button', function (e) {
            var comment = $.trim($("#form-comment textarea[name='comment']").val());
            var name = '{{\Auth::user()->name}}';
            if (comment != '') {
                $.ajax({
                    url: $("#form-comment").data('action'),
                    data: {comment: comment, "_token": $('meta[name="csrf-token"]').attr('content')},
                    type: 'POST',
                    success: function (data) {
                        
                        data = JSON.parse(data);
                        console.log(data);
                        var html = '<div class="list-group-item">\n' +
                            '                            <div class="row">\n' +
                            '                                <div class="col ml-n2">\n' +
                            '                                    <a href="#!" class="d-block h6 mb-0">' + name + '</a>\n' +
                            '                                    <div>\n' +
                            '                                        <small>' + data.comment + '</small>\n' +
                            '                                    </div>\n' +
                            '                                </div>\n' +
                            '                                <div class="col-auto">\n' +
                            '                                    <a href="#" class="action-item  delete-comment" data-url="' + data.deleteUrl + '">\n' +
                            '                                        <i class="ti ti-trash"></i>\n' +
                            '                                    </a>\n' +
                            '                                </div>\n' +
                            '                            </div>\n' +
                            '                        </div>';


                        $("#comments").prepend(html);
                        $("#form-comment textarea[name='comment']").val('');
                        toastrs('Success', '{{ __("Comment successfully created.")}}', 'success');
                    },
                    error: function (data) {
                        toastrs('Error', '{{ __("Some thing is wrong.")}}', 'error');
                    }
                });
            } else {
                toastrs('Error', '{{ __("Please write comment.")}}', 'error');
            }
        });
        $(document).on("click", ".delete-comment", function () {
            if (confirm('Are You Sure ?')) {
                var comment = $(this).parent().parent().parent();


                $.ajax({
                    url: $(this).attr('data-url'),
                    type: 'DELETE',
                    data: {_token: $('meta[name="csrf-token"]').attr('content')},
                    dataType: 'JSON',
                    success: function (data) {
                        toastrs('Success', '{{ __("Comment Deleted Successfully!")}}', 'success');
                        comment.remove();
                    },
                    error: function (data) {
                        data = data.responseJSON;
                        if (data.message) {
                            toastrs('Error', data.message, 'error');
                        } else {
                            toastrs('Error', '{{ __("Some Thing Is Wrong!")}}', 'error');
                        }
                    }
                });
            }
        });

        $(document).on('submit', '#form-file', function (e) {
            e.preventDefault();
            $.ajax({
                url: $("#form-file").data('url'),
                type: 'POST',
                data: new FormData(this),
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    toastrs('Success', '{{ __("Comment successfully created.")}}', 'success');

                    var html = '<div class="card mb-3 border shadow-none">\n' +
                        '                            <div class="px-3 py-3">\n' +
                        '                                <div class="row align-items-center">\n' +
                        '                                    <div class="col ml-n2">\n' +
                        '                                        <h6 class="text-sm mb-0">\n' +
                        '                                            <a href="#!">' + data.name + '</a>\n' +
                        '                                        </h6>\n' +
                        '                                        <p class="card-text small text-muted">\n' +
                        '                                            ' + data.file_size + '\n' +
                        '                                        </p>\n' +
                        '                                    </div>\n' +
                        '                                    <div class="col-auto actions">\n' +
                        '                                        <a download href="{{asset(Storage::url('tasks'))}}' + data.file + '" class="action-item">\n' +
                        '                                            <i class="ti ti-trash"></i>\n' +
                        '                                        </a>\n' +
                        '                                        <a href="#" class="action-item delete-comment-file" data-url="' + data.deleteUrl + '">\n' +
                        '                                            <i class="ti ti-trash"></i>\n' +
                        '                                        </a>\n' +
                        '\n' +
                        '                                    </div>\n' +
                        '                                </div>\n' +
                        '                            </div>\n' +
                        '                        </div>';
                    $("#comments-file").prepend(html);
                },
                error: function (data) {
                    data = data.responseJSON;
                    if (data.message) {
                        toastrs('Error', data.message, 'error');
                        $('#file-error').text(data.errors.file[0]).show();
                    } else {
                        toastrs('Error', '{{ __("Some Thing Is Wrong!")}}', 'error');
                    }
                }
            });
        });
        $(document).on("click", ".delete-comment-file", function () {

            if (confirm('Are You Sure ?')) {
                var div = $(this).parent().parent().parent().parent();


                $.ajax({
                    url: $(this).attr('data-url'),
                    type: 'DELETE',
                    data: {_token: $('meta[name="csrf-token"]').attr('content')},
                    dataType: 'JSON',
                    success: function (data) {
                        toastrs('Success', '{{ __("File successfully deleted.")}}', 'success');
                        div.remove();
                    },
                    error: function (data) {
                        data = data.responseJSON;
                        if (data.message) {
                            toastrs('Error', data.message, 'error');
                        } else {
                            toastrs('Error', '{{ __("Some thing is wrong.")}}', 'error');
                        }
                    }
                });
            }
        });

        $(document).on('change', '#project', function () {
            var project_id = $(this).val();

            $.ajax({
                url: '{{route('project.getMilestone')}}',
                type: 'POST',
                data: {
                    "project_id": project_id, "_token": "{{ csrf_token() }}",
                },
                success: function (data) {
                    $('#milestone_id').empty();
                    $('#milestone_id').append('<option value="0"> -- </option>');
                    $.each(data, function (key, value) {
                        $('#milestone_id').append('<option value="' + key + '">' + value + '</option>');
                    });
                }
            });

            $.ajax({
                url: '{{route('project.getUser')}}',
                type: 'POST',
                data: {
                    "project_id": project_id, "_token": "{{ csrf_token() }}",
                },
                success: function (data) {
                    $('#assign_to').empty();
                    $.each(data, function (key, value) {
                        $('#assign_to').append('<option value="' + key + '">' + value + '</option>');
                    });
                }
            });

        });
    </script>
@endpush
@section('page-title')
    {{__('Task')}}
@endsection
@section('title')
    <div class="d-inline-block">
        <h5 class="h4 d-inline-block font-weight-400 mb-0">{{__('Task')}}</h5>
    </div>
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{__('Project')}}</li>
    <li class="breadcrumb-item active" aria-current="page">{{__('Task')}}</li>
@endsection
@section('action-btn')
    <a href="{{ route('project.all.task.gantt.chart') }}" class="btn btn-sm btn-primary btn-icon m-1" data-bs-toggle="tooltip"  data-bs-original-title="{{__('Gnatt Chart')}}">
        <i class="ti ti-chart-line"></i>
    </a>


    <a href="{{ route('project.all.task') }}" class="btn btn-sm btn-primary btn-icon m-1">
        <i  data-bs-toggle="tooltip"  data-bs-original-title="{{__('List View')}}" class="ti ti-list"></i>
    </a>

    <a class="btn btn-sm btn-primary btn-icon m-1" data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
        <i  data-bs-toggle="tooltip"  data-bs-original-title="{{__('Filter')}}" class="ti ti-filter"></i>
    </a>

    <a href="#" class="btn btn-sm btn-primary btn-icon m-1" data-bs-toggle="modal" 
    data-bs-target="#exampleModal" data-url="{{ route('project.task.create',0) }}" data-size="lg"
    data-bs-whatever="{{__('Create New Task')}}" >
        <i data-bs-toggle="tooltip"  data-bs-original-title="{{__('Create')}}" class="ti ti-plus text-white"></i>
    </a>


@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="collapse {{isset($_GET['project'])?'show':''}}" id="collapseExample">
                <div class="card card-body">
                    {{ Form::open(array('route' => array('project.all.task.kanban'),'method'=>'get')) }}
                    <div class="row filter-css">
                        @if(\Auth::user()->type=='company')
                            <div class="col-md-3">
                                {{ Form::select('project', $projectList,!empty($_GET['project'])?$_GET['project']:'', array('class' => 'form-control','data-toggle'=>'select')) }}
                            </div>
                        @endif
                        <div class="col-md-2">
                            <select class="form-control" data-toggle="select" name="status">
                                <option value="">{{__('All')}}</option>
                                @foreach($stageList as $k=>$val)
                                    <option value="{{$k}}" {{isset($_GET['status']) && $_GET['status']==$k?'selected':''}}> {{$val}} </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-control" data-toggle="select" name="priority">
                                <option value="">{{__('All')}}</option>
                                @foreach($priority as $val)
                                    <option value="{{$val}}" {{isset($_GET['priority']) && $_GET['priority']==$val?'selected':''}}> {{$val}} </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-auto">
                            {{Form::date('due_date',isset($_GET['due_date'])?$_GET['due_date']:'',array('class'=>'form-control'))}}
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-xs btn-primary btn-icon-only rounded-circle" data-toggle="tooltip" data-title="{{__('Apply')}}"><i class="ti ti-search"></i></button>
                        </div>
                        <div class="col-auto">
                            <a href="{{route('project.all.task.kanban')}}" data-toggle="tooltip" data-title="{{__('Reset')}}" class="btn btn-xs btn-danger btn-icon-only rounded-circle"><i class="ti ti-trash"></i></a>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            @php
                $json = [];
                foreach ($stages as $stage){
                    $json[] = 'kanban-blacklist-'.$stage->id;
                }
            @endphp
            <div class="row kanban-wrapper horizontal-scroll-cards kanban-board" data-containers='{!! json_encode($json) !!}' data-plugin="dragula">
                @foreach($stages as $stage)
                    @php
                        if(empty($_GET['project']) && empty($_GET['priority']) && empty($_GET['due_date'])){
                        $tasks = $stage->allTask;
                        }else{
                            $tasks=$stage->allTaskFilter($_GET['project'] , $_GET['priority'],$_GET['due_date']);
                        }
                    @endphp
                        <div class="col">
                            <div class="card">
                                <div class="card-header">
                                    <div class="float-end">
                                        <button class="btn btn-sm btn-primary btn-icon task-header">
                                            <span class="count text-white">{{count($tasks)}}</span>
                                        </button>
                                    </div>
                                    <h4 class="mb-0">{{$stage->name}}</h4>
                                </div>
                                <div class="card-body kanban-box" data-id="{{$stage->id}}"  id="kanban-blacklist-{{$stage->id}}">
                                    @foreach($tasks as $task)
                                        <div class="card" data-id="{{$task->id}}">
                                            <div class="pt-3 ps-3">
                                                @if($task->priority =='low')
                                                        <div class="badge bg-success p-2 px-3 rounded"> {{ ucfirst($task->priority) }}</div>
                                                @elseif($task->priority =='medium')
                                                    <div class="badge bg-warning p-2 px-3 rounded"> {{ ucfirst($task->priority) }}</div>
                                                @elseif($task->priority =='high')
                                                    <div class="badge bg-danger p-2 px-3 rounded"> {{ucfirst($task->priority)  }}</div>
                                                @endif
                                                <div class="card-header border-0 pb-0 position-relative">
                                                    <h5> 
                                                        <a href="#" data-size="lg" data-url="{{ route('project.task.show',$task->id) }}" data-bs-toggle="modal"
                                                        data-bs-target="#exampleModal" data-bs-whatever="{{__('View Task Details')}}" 
                                                        data-bs-toggle="tooltip"  title data-bs-original-title="{{__('Task Detail')}}" >{{$task->title}}</a></h5>
                                                        <div class="card-header-right">
                                                            <div class="btn-group card-option">
                                                                <button type="button" class="btn dropdown-toggle"
                                                                    data-bs-toggle="dropdown" aria-haspopup="true"
                                                                    aria-expanded="false">
                                                                    <i class="ti ti-dots-vertical"></i>
                                                                </button>
                                                                <div class="dropdown-menu dropdown-menu-end">
                                                                    @if(\Auth::user()->type=='company')
                                                                        <a href="#!" class="dropdown-item" data-size="lg" data-url="{{ route('project.task.edit',$task->id) }}" 
                                                                            data-bs-toggle="modal" data-bs-target="#exampleModal"  data-bs-whatever="{{__('Edit Task')}}">
                                                                            <i class="ti ti-edit"></i>
                                                                            <span>{{__('Edit')}}</span>
                                                                        </a>
                                                                    @endif
                                                                    <a href="#!" class="dropdown-item"  data-size="lg" data-url="{{ route('project.task.show',$task->id) }}" 
                                                                        data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-whatever="{{__('View Task Details')}}">
                                                                        <i class="ti ti-eye"></i>
                                                                        <span>{{__('View')}}</span>
                                                                    </a>
                                                                    <span class="dropdown-item">
                                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['project.task.destroy', $task->id],'id'=>'task-delete-form-'.$task->id]) !!}
                                                                        <a href="#!" class="mx-3 btn btn-sm  align-items-center show_confirm ">
                                                                            <i class="ti ti-trash"></i>{{ __('Delete') }}
                                                                        </a>
                                                                        {!! Form::close() !!}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                </div>
                                                <div class="card-body">
                                                    <p class="text-muted text-sm" data-bs-toggle="tooltip" data-bs-original-title="{{ __('Description') }}">{{ $task->description }}</p>
                                                    <p class="text-muted text-sm">{{$task->taskCompleteCheckListCount()}}/{{$task->taskTotalCheckListCount()}}</p>
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <ul class="list-inline mb-0">
                                                            
                                                            <li class="list-inline-item d-inline-flex align-items-center"><i
                                                                    class="f-16 text-primary ti ti-message-2"></i>{{\Auth::user()->dateFormat($task->start_date)}}</li>
                                                            
                                                            <li class="list-inline-item d-inline-flex align-items-center"><i
                                                                    class="f-16 text-primary ti ti-link"></i>{{\Auth::user()->dateFormat($task->due_date)}}</li>
                                                        </ul>
                                                        <div class="user-group">
                                                            <img alt="image" data-toggle="tooltip" data-original-title="{{!empty($task->taskUser)?$task->taskUser->name:''}}" @if($task->taskUser && !empty($task->taskUser->avatar)) src="{{$profile.'/'.$task->taskUser->avatar}}" @else avatar="{{!empty($task->taskUser)?$task->taskUser->name:''}}" @endif class="">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <!-- [ sample-page ] end -->
        </div>
    </div>
@endsection



