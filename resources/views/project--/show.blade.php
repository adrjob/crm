@extends('layouts.admin')
@php
    $profile=asset(Storage::url('uploads/avatar/'));
@endphp
@section('page-title')
    {{__('Project Detail')}}
@endsection
@push('css-page')
    <link rel="stylesheet" href="{{asset('css/frappe-gantt.css')}}"/>
@endpush
@push('script-page')
<script>
    var scrollSpy = new bootstrap.ScrollSpy(document.body, {
        target: '#useradd-sidenav',
        offset: 300
    })
</script>

    <script>
        const month_names = {
            "en": [
                'January',
                'February',
                'March',
                'April',
                'May',
                'June',
                'July',
                'August',
                'September',
                'October',
                'November',
                'December'
            ],
            "en": [
                'January',
                'February',
                'March',
                'April',
                'May',
                'June',
                'July',
                'August',
                'September',
                'October',
                'November',
                'December'
            ],
        };
    </script>
    <script src="{{asset('assets/libs/dragula/dist/dragula.min.js')}}"></script>
    <script src="{{asset('assets/libs/autosize/dist/autosize.min.js')}}"></script>
    <script src="{{asset('js/frappe-gantt.js')}}"></script>
    <script>
        !function (a) {
            "use strict";
            var t = function () {
                this.$body = a("body")
            };
            t.prototype.init = function () {
                a('[data-toggle="dragula"]').each(function () {

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
                                toastrs('Error', data.error, 'error')
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

    </script>
    <script>

        var tasks = JSON.parse('{!! addslashes(json_encode($ganttTasks)) !!}');

        var gantt = new Gantt('#gantt', tasks, {

            custom_popup_html: function (task) {
                var status_class = 'success';
                if (task.custom_class == 'medium') {
                    status_class = 'info'
                } else if (task.custom_class == 'high') {
                    status_class = 'danger'
                }
                return `<div class="details-container">
                                <div class="title">${task.name} <span class="badge badge-${status_class} float-right">${task.extra.priority}</span></div>
                                <div class="subtitle">

                                    <b>{{ __('Stage')}} : </b> ${task.extra.stage}<br>
                                    <b>{{ __('Duration')}} : </b> ${task.extra.duration}<br>
                                    <b>{{ __('Description')}} : </b> ${task.extra.description}

                                </div>
                            </div>
                          `;
            },
            on_click: function (task) {
            },
            on_date_change: function (task, start, end) {
                task_id = task.id;
                start = moment(start);
                end = moment(end);
                $.ajax({
                    url: "{{route('project.gantt.post',$project->id)}}",
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        start: start.format('YYYY-MM-DD HH:mm:ss'),
                        end: end.format('YYYY-MM-DD HH:mm:ss'),
                        task_id: task_id,
                    },
                    type: 'POST',
                    success: function (data) {

                    },
                    error: function (data) {
                        toastrs('Error', '{{ __("Some Thing Is Wrong!")}}', 'error');
                    }
                });
            },
        });

        gantt.change_view_mode('Week');

        $(document).on("click", ".gantt-chart-mode", function () {

            var mode = $(this).data('value');
            $('.gantt-chart-mode').removeClass('active');
            $(this).addClass('active');
            gantt.change_view_mode(mode)
        });

    </script>
@endpush
@section('title')
    <div class="d-inline-block">
        <h5 class="h4 d-inline-block font-weight-400 mb-0">{{__('Project Detail')}}</h5>
    </div>
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item"><a href="{{route('project.index')}}">{{__('Project')}}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{$project->title}}</li>
@endsection
@section('action-btn')

    @if(\Auth::user()->type=='company')
        @if($projectStatus)
            <div class="btn-group">
                <button class="btn btn-sm bg-white btn-icon rounded-pill dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{\App\Models\Project::$projectStatus[$project->status]}}
                </button>
                <div class="dropdown-menu">
                    @foreach($projectStatus as $k=>$status)
                        <a class="dropdown-item status" data-id="{{$k}}" data-url="{{route('project.status',$project->id)}}" href="#">{{$status}}</a>
                    @endforeach
                </div>
            </div>
        @endif
    @endif
    @if(\Auth::user()->type=='company')
        <a href="{{ route('project.edit',\Crypt::encrypt($project->id)) }}" class="btn btn-sm btn-white btn-icon-only rounded-circle" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
            <i class="far fa-edit"></i>
        </a>
        <a href="#!" class="btn btn-sm btn-white btn-icon-only rounded-circle" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('project-delete-form-{{$project->id}}').submit();">
            <i class="fas fa-trash"></i>
        </a>
        {!! Form::open(['method' => 'DELETE', 'route' => ['project.destroy', $project->id],'id'=>'project-delete-form-'.$project->id]) !!}
        {!! Form::close() !!}
    @endif
@endsection
@section('content')
    {{-- <div class="row">
        <div class="col-lg-4 order-lg-2">
            <div class="card">
                <div class="list-group list-group-flush" id="tabs">
                    <div data-href="#overview" class="list-group-item custom-list-group-item text-primary">
                        <div class="media">
                            <i class="fas fa-history pt-1"></i>
                            <div class="media-body ml-3">
                                <a href="#" class="stretched-link h6 mb-1">{{__('Overview')}}</a>
                                <p class="mb-0 text-sm">{{__('Details about project overview')}}</p>
                            </div>
                        </div>
                    </div>
                    <div data-href="#taskList" class="list-group-item custom-list-group-item text-primary">
                        <div class="media">
                            <i class="fas fa-tasks pt-1"></i>
                            <div class="media-body ml-3">
                                <a href="#" class="stretched-link h6 mb-1">{{__('Task List')}}</a>
                                <p class="mb-0 text-sm">{{__('Details about project task')}}</p>
                            </div>
                        </div>
                    </div>
                    <div data-href="#taskKanban" class="list-group-item custom-list-group-item text-primary">
                        <div class="media">
                            <i class="fas fa-tasks pt-1"></i>
                            <div class="media-body ml-3">
                                <a href="#" class="stretched-link h6 mb-1">{{__('Task Kanban')}}</a>
                                <p class="mb-0 text-sm">{{__('Details about project task')}}</p>
                            </div>
                        </div>
                    </div>
                    <div data-href="#ganttChart" class="list-group-item custom-list-group-item text-primary">
                        <div class="media">
                            <i class="fas fa-tasks pt-1"></i>
                            <div class="media-body ml-3">
                                <a href="#" class="stretched-link h6 mb-1">{{__('Gantt Chart')}}</a>
                                <p class="mb-0 text-sm">{{__('Chart about project task')}}</p>
                            </div>
                        </div>
                    </div>
                    <div data-href="#milestone" class="list-group-item custom-list-group-item text-primary">
                        <div class="media">
                            <i class="fas fa-cubes pt-1"></i>
                            <div class="media-body ml-3">
                                <a href="#" class="stretched-link h6 mb-1">{{__('Milestone')}}</a>
                                <p class="mb-0 text-sm">{{__('Details about project milestone')}}</p>
                            </div>
                        </div>
                    </div>
                    <div data-href="#notes" class="list-group-item custom-list-group-item text-primary">
                        <div class="media">
                            <i class="fas fa-sticky-note pt-1"></i>
                            <div class="media-body ml-3">
                                <a href="#" class="stretched-link h6 mb-1">{{__('Notes')}}</a>
                                <p class="mb-0 text-sm">{{__('Details about project notes')}}</p>
                            </div>
                        </div>
                    </div>
                    <div data-href="#files" class="list-group-item custom-list-group-item text-primary">
                        <div class="media">
                            <i class="fas fa-file pt-1"></i>
                            <div class="media-body ml-3">
                                <a href="#" class="stretched-link h6 mb-1">{{__('Files')}}</a>
                                <p class="mb-0 text-sm">{{__('Details about project files')}}</p>
                            </div>
                        </div>
                    </div>
                    <div data-href="#comments" class="list-group-item custom-list-group-item text-primary">
                        <div class="media">
                            <i class="fas fa-comment pt-1"></i>
                            <div class="media-body ml-3">
                                <a href="#" class="stretched-link h6 mb-1">{{__('Comments')}}</a>
                                <p class="mb-0 text-sm">{{__('Details about project comments')}}</p>
                            </div>
                        </div>
                    </div>
                    <div data-href="#clientFeedback" class="list-group-item custom-list-group-item text-primary">
                        <div class="media">
                            <i class="fas fa-comment-alt pt-1"></i>
                            <div class="media-body ml-3">
                                <a href="#" class="stretched-link h6 mb-1">{{__('Client Feedback')}}</a>
                                <p class="mb-0 text-sm">{{__('Details about project client feedback')}}</p>
                            </div>
                        </div>
                    </div>
                    @if(\Auth::user()->type=='company' || \Auth::user()->type=='client')
                        <div data-href="#invoice" class="list-group-item custom-list-group-item text-primary">
                            <div class="media">
                                <i class="fas fa-file-invoice pt-1"></i>
                                <div class="media-body ml-3">
                                    <a href="#" class="stretched-link h6 mb-1">{{__('Invoice')}}</a>
                                    <p class="mb-0 text-sm">{{__('Details about project invoice')}}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if(\Auth::user()->type=='company' || \Auth::user()->type=='employee')
                        <div data-href="#timesheets" class="list-group-item custom-list-group-item text-primary">
                            <div class="media">
                                <i class="fas fa-clock pt-1"></i>
                                <div class="media-body ml-3">
                                    <a href="#" class="stretched-link h6 mb-1">{{__('Timesheets')}}</a>
                                    <p class="mb-0 text-sm">{{__('Details about project timesheets')}}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if(\Auth::user()->type=='company' || \Auth::user()->type=='client')
                        <div data-href="#payment" class="list-group-item custom-list-group-item text-primary">
                            <div class="media">
                                <i class="fas fa-money-bill pt-1"></i>
                                <div class="media-body ml-3">
                                    <a href="#" class="stretched-link h6 mb-1">{{__('Payment')}}</a>
                                    <p class="mb-0 text-sm">{{__('Details about project payment')}}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if(\Auth::user()->type=='company')
                        <div data-href="#expense" class="list-group-item custom-list-group-item text-primary">
                            <div class="media">
                                <i class="fas fas fa-money-bill-wave pt-1"></i>
                                <div class="media-body ml-3">
                                    <a href="#" class="stretched-link h6 mb-1">{{__('Expense')}}</a>
                                    <p class="mb-0 text-sm">{{__('Details about project expense')}}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @php
            $percentages = 0;
            $total=count($project->tasks);
            if($total != 0){
                $percentages= $project->completedTask() / ($total /100);
            }
        @endphp
        <div class="col-lg-8 order-lg-1">
            <div class="row">
                <div class="col-md-12">
                    <div class="card project-detail-box">
                        <div class="card-header pb-1">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="mb-0">{{$project->title}}</h6>
                                </div>
                                <div class="col-md-2">
                                    <span class="progress-percentage">
                                        <small class="font-weight-bold">{{__('Completed')}}: </small><b>{{$percentages}}%</b>
                                    </span>
                                </div>
                                <div class="col-md-4">
                                    <div class="progress-wrapper">
                                        <div class="progress progress-xs">
                                            <div class="progress-bar bg-success" role="progressbar" aria-valuenow="{{$percentages}}" aria-valuemin="0" aria-valuemax="100" style="width: {{$percentages}}%;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body py-3 flex-grow-1">
                            <!-- Progress -->
                            <p class="text-sm mb-0">
                                {{$project->description}}
                            </p>
                        </div>
                        <div class="card-footer py-0">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item px-0">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <small>{{__('Start Date')}}:</small>
                                            <div class="h6 mb-0">{{\Auth::user()->dateFormat($project->start_date)}}</div>
                                        </div>
                                        <div class="col">
                                            <small>{{__('Due Date')}}:</small>
                                            <div class="h6 mb-0">{{\Auth::user()->dateFormat($project->due_date)}}</div>
                                        </div>
                                        <div class="col-auto">
                                            <small>{{__('Comments')}}:</small>
                                            <div class="h6 mb-0 text-center">{{count($comments)}}</div>
                                        </div>
                                        <div class="col-auto">
                                            <small>{{__('Members')}}:</small>
                                            <div class="h6 mb-0 text-center">{{count($project->projectUser())}}</div>
                                        </div>
                                        <div class="col-auto">
                                            <small>{{__('Days Left')}}:</small>
                                            <div class="h6 mb-0 text-center">{{$daysleft}}</div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-stats">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h6 class="text-muted mb-1">{{__('Budget')}}</h6>
                                    <span class="h6 font-weight-bold mb-0 ">{{\Auth::user()->priceFormat($project->price)}}</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon bg-gradient-success text-white rounded-circle icon-shape">
                                        <i class="fas fa-money-bill-alt"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-stats">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h6 class="text-muted mb-1">{{__('Expense')}}</h6>
                                    <span class="h6 font-weight-bold mb-0 ">{{\Auth::user()->priceFormat($totalExpense)}}</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon bg-gradient-danger text-white rounded-circle icon-shape">
                                        <i class="fas fa-money-bill-wave"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-stats">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h6 class="text-muted mb-1">{{__('Client')}}</h6>
                                    <span class="h6 font-weight-bold mb-0 ">{{!empty($project->clients)?$project->clients->name:''}}</span>
                                </div>
                                <div class="col-auto">
                                    <img alt="" src="" class="icon bg-gradient-danger text-white rounded-circle icon-shape" avatar="{{!empty($project->clients)?$project->clients->name:''}}">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="overview" class="tabs-card">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card ">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-0">{{__('Project members')}}</h6>
                                    </div>
                                    <div class="text-right">
                                        <div class="actions">
                                            <a href="#" data-url="{{ route('project.user',$project->id) }}" data-ajax-popup="true" data-title="{{__('Add User')}}" class="action-item">
                                                <i class="ti ti-plus"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="scrollbar-inner">
                                <div class="list-group list-group-flush project-detail-common-box">
                                    @foreach($project->projectUser() as $user)
                                        @php $totalTask= $project->user_project_total_task($user->project_id,$user->user_id) @endphp
                                        @php $completeTask= $project->user_project_complete_task($user->project_id,$user->user_id,($project->project_last_stage())?$project->project_last_stage()->id:'' ) @endphp
                                        <div class="list-group-item">
                                            <div class="row align-items-center">
                                                <div class="col-auto">
                                                    <!-- Avatar -->
                                                    <a href="#" class="avatar rounded-circle">
                                                        <img alt="Image placeholder" @if(!empty($user->avatar)) src="{{$profile.'/'.$user->avatar}}" @else  avatar="{{$user->name}}" @endif>
                                                    </a>
                                                </div>
                                                <div class="col ml-n2">
                                                    <a href="#!" class="d-block h6 mb-0">{{$user->name}}</a>
                                                    <small>{{$user->email}}</small>
                                                </div>
                                                <div class="col-auto">
                                                    <a href="{{ route('employee.show',\Crypt::encrypt($user->user_id)) }}" class="action-item" data-toggle="tooltip" data-original-title="{{__('View')}}">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="#!" class="action-item" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('project-user-delete-form-{{$user->id}}').submit();">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['project.user.destroy', $project->id,$user->user_id],'id'=>'project-user-delete-form-'.$user->id]) !!}
                                                    {!! Form::close() !!}
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-0">{{__('Activity')}}</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="scrollbar-inner">
                                <div class="card-body project-detail-common-box">
                                    <div class="timeline timeline-one-side" data-timeline-content="axis" data-timeline-axis-style="dashed">
                                        @foreach($project->activities as $activity)
                                            @if($activity->log_type == 'Upload File')
                                                <div class="timeline-block">
                                                    <span class="timeline-step timeline-step-sm bg-primary border-primary text-white"> <i class="fas fa-file"></i></span>
                                                    <div class="timeline-content">
                                                        <span class="text-muted text-sm">{{$activity->log_type}}</span>
                                                        <a href="#" class="d-block h6 text-sm mb-0">{!! $activity->getRemark() !!}</a>
                                                        <small><i class="fas fa-clock mr-1"></i>{{$activity->created_at->diffForHumans()}}</small>
                                                    </div>
                                                </div>
                                            @elseif($activity->log_type == 'Create Milestone')
                                                <div class="timeline-block">
                                                    <span class="timeline-step timeline-step-sm bg-info border-info text-white"> <i class="fas fa-cubes"></i></span>
                                                    <div class="timeline-content">
                                                        <span class="text-muted text-sm">{{$activity->log_type}}</span>
                                                        <a href="#" class="d-block h6 text-sm mb-0">{!! $activity->getRemark() !!}</a>
                                                        <small><i class="fas fa-clock mr-1"></i>{{$activity->created_at->diffForHumans()}}</small>
                                                    </div>
                                                </div>
                                            @elseif($activity->log_type == 'Create Task')
                                                <div class="timeline-block">
                                                    <span class="timeline-step timeline-step-sm bg-success border-success text-white"> <i class="fas fa-tasks"></i></span>
                                                    <div class="timeline-content">
                                                        <span class="text-muted text-sm">{{$activity->log_type}}</span>
                                                        <a href="#" class="d-block h6 text-sm mb-0">{!! $activity->getRemark() !!}</a>
                                                        <small><i class="fas fa-clock mr-1"></i>{{$activity->created_at->diffForHumans()}}</small>
                                                    </div>
                                                </div>

                                            @elseif($activity->log_type == 'Create Bug')
                                                <div class="timeline-block">
                                                    <span class="timeline-step timeline-step-sm bg-warning border-warning text-white"> <i class="fas fa-bug"></i></span>
                                                    <div class="timeline-content">
                                                        <span class="text-muted text-sm">{{$activity->log_type}}</span>
                                                        <a href="#" class="d-block h6 text-sm mb-0">{!! $activity->getRemark() !!}</a>
                                                        <small><i class="fas fa-clock mr-1"></i>{{$activity->created_at->diffForHumans()}}</small>
                                                    </div>
                                                </div>
                                            @elseif($activity->log_type == 'Move')
                                                <div class="timeline-block">
                                                    <span class="timeline-step timeline-step-sm bg-danger border-danger text-white"> <i class="fas fa-align-justify"></i></span>
                                                    <div class="timeline-content">
                                                        <span class="text-muted text-sm">{{$activity->log_type}}</span>
                                                        <a href="#" class="d-block h6 text-sm mb-0">{!! $activity->getRemark() !!}</a>
                                                        <small><i class="fas fa-clock mr-1"></i>{{$activity->created_at->diffForHumans()}}</small>
                                                    </div>
                                                </div>
                                            @elseif($activity->log_type == 'Create Invoice')
                                                <div class="timeline-block">
                                                    <span class="timeline-step timeline-step-sm bg-dark border-bg-dark text-white"> <i class="fas fa-file-invoice"></i></span>
                                                    <div class="timeline-content">
                                                        <span class="text-muted text-sm">{{$activity->log_type}}</span>
                                                        <a href="#" class="d-block h6 text-sm mb-0">{!! $activity->getRemark() !!}</a>
                                                        <small><i class="fas fa-clock mr-1"></i>{{$activity->created_at->diffForHumans()}}</small>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div id="taskList" class="tabs-card d-none">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header actions-toolbar">
                                <div class="row justify-content-between align-items-center">
                                    <div class="col">
                                        <h6 class="d-inline-block mb-0">{{__('Tasks')}}</h6>
                                    </div>
                                    @if(\Auth::user()->type=='company')
                                        <div class="col text-right">
                                            <div class="actions">
                                                <a href="#" data-size="lg" data-url="{{ route('project.task.create',$project->id) }}" data-ajax-popup="true" data-title="{{__('Create New Task')}}" class="action-item">
                                                    <i class="ti ti-plus"></i>
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="scrollbar-inner">
                                <div class="card-body project-detail-common-box">
                                    @php
                                        $json = [];
                                        foreach ($stages as $stage){
                                            $json[] = 'task-list-'.$stage->id;
                                        }
                                    @endphp
                                    @foreach($stages as $stage)
                                        @php $tasks =$stage->tasks($project->id) @endphp
                                        <h6 class="mb-1">
                                            {{$stage->name}}
                                        </h6>
                                        <div class="mb-4" id="card-list-1">
                                            @foreach($tasks as $task)
                                                <div class="card card-progress border shadow-none draggable-item">
                                                    @if($task->priority =='low')
                                                        <div class="progress">
                                                            <div class="progress-bar bg-success" role="progressbar" style="width: 100%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="50"></div>
                                                        </div>
                                                    @elseif($task->priority =='medium')
                                                        <div class="progress">
                                                            <div class="progress-bar bg-warning" role="progressbar" style="width: 100%" aria-valuenow="80" aria-valuemin="0" aria-valuemax="80"></div>
                                                        </div>
                                                    @elseif($task->priority =='high')
                                                        <div class="progress">
                                                            <div class="progress-bar bg-danger" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                    @endif


                                                    <div class="card-body row align-items-center">
                                                        <div class="col-auto">
                                                           <span class="avatar avatar-sm rounded-circle mr-2">
                                                               <img alt="image" data-toggle="tooltip" data-original-title="{{!empty($task->taskUser)?$task->taskUser->name:''}}" @if($task->taskUser && !empty($task->taskUser->avatar)) src="{{$profile.'/'.$task->taskUser->avatar}}" @else avatar="{{!empty($task->taskUser)?$task->taskUser->name:''}}" @endif class="">
                                                           </span>
                                                            <a href="#" data-size="lg" data-url="{{ route('project.task.show',$task->id) }}" data-ajax-popup="true" data-title="{{__('Task Detail')}}" class="h6" data-toggle="tooltip">
                                                                {{$task->title}}
                                                            </a>
                                                            <br>
                                                            <span>
                                                                @if($task->priority =='low')
                                                                    <div class="badge badge-success font-style"> {{ $task->priority }}</div>
                                                                @elseif($task->priority =='medium')
                                                                    <div class="badge badge-warning font-style"> {{ $task->priority }}</div>
                                                                @elseif($task->priority =='high')
                                                                    <div class="badge badge-danger font-style"> {{ $task->priority }}</div>
                                                                @endif
                                                            </span>
                                                            <div class="actions d-inline-block float-right float-sm-none">
                                                                <div class="action-item ml-4">
                                                                    <i class="far fa-calendar"></i> {{\Auth::user()->dateFormat($task->start_date)}}
                                                                </div>
                                                            </div>
                                                            <div class="actions d-inline-block float-right float-sm-none">
                                                                <div class="action-item ml-4">
                                                                    <i class="far fa-calendar"></i> {{\Auth::user()->dateFormat($task->due_date)}}
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-auto card-meta d-inline-flex align-items-center ml-sm-auto">
                                                            <div class="media  align-items-center d-inline-flex ">
                                                                @if(\Auth::user()->type=='company')
                                                                    <a class="action-item" href="#" data-size="lg" data-url="{{ route('project.task.edit',$task->id) }}" data-ajax-popup="true" data-toggle="tooltip" data-original-title="{{__('Edit')}}" data-title="{{__('Edit Task')}}"> <i class="fas fa-edit"></i></a>
                                                                @endif
                                                                <a class="dropdown-item" data-size="lg" data-url="{{ route('project.task.show',$task->id) }}" data-ajax-popup="true" data-title="{{__('View')}}" class="h6" data-toggle="tooltip"> <i class="fas fa-eye"></i></a>
                                                                @if(\Auth::user()->type=='company')
                                                                    <a class="action-item" href="#" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('task-delete-form-{{$task->id}}').submit();" data-toggle="tooltip" data-original-title="{{__('Delete')}}"> <i class="fas fa-trash"></i></a>

                                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['project.task.destroy', $task->id],'id'=>'task-delete-form-'.$task->id]) !!}
                                                                    {!! Form::close() !!}
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                            <span class="empty-container" data-placeholder="Empty"></span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="taskKanban" class="tabs-card d-none">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card overflow-hidden ">
                            <div class="card-header actions-toolbar">
                                <div class="row justify-content-between align-items-center">
                                    <div class="col">
                                        <h6 class="d-inline-block mb-0">{{__('Tasks')}}</h6>
                                    </div>
                                    @if(\Auth::user()->type=='company')
                                        <div class="col text-right">
                                            <div class="actions">
                                                <a href="#" data-size="lg" data-url="{{ route('project.task.create',$project->id) }}" data-ajax-popup="true" data-title="{{__('Create New Task')}}" class="action-item">
                                                    <i class="ti ti-plus"></i>
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="container-kanban ">
                                @php
                                    $json = [];
                                    foreach ($stages as $stage){
                                        $json[] = 'kanban-blacklist-'.$stage->id;
                                    }
                                @endphp
                                <div class="kanban-board project-task-kanban-box" data-toggle="dragula" data-containers='{!! json_encode($json) !!}'>
                                    @foreach($stages as $stage)
                                        @php $tasks =$stage->tasks($project->id) @endphp
                                        <div class="kanban-col px-0">
                                            <div class="card-list card-list-flush">
                                                <div class="card-list-title row align-items-center mb-3">
                                                    <div class="col">
                                                        <h6 class="mb-0 text-white">{{$stage->name}}</h6>
                                                    </div>
                                                    <div class="col text-right">
                                                        <span class="badge badge-secondary rounded-pill">{{count($tasks)}}</span>
                                                    </div>
                                                </div>
                                                <div data-id="{{$stage->id}}" class="card-list-body scrollbar-inner" id="kanban-blacklist-{{$stage->id}}">
                                                    @foreach($tasks as $task)
                                                        <div class="card card-progress draggable-item border shadow-none" data-id="{{$task->id}}">
                                                            <div class="card-body">
                                                                <div class="row align-items-center">
                                                                    <div class="col-6">
                                                                        @if($task->priority =='low')
                                                                            <div class="badge badge-pill badge-xs badge-success"> {{ $task->priority }}</div>
                                                                        @elseif($task->priority =='medium')
                                                                            <div class="badge badge-pill badge-xs badge-warning"> {{ $task->priority }}</div>
                                                                        @elseif($task->priority =='high')
                                                                            <div class="badge badge-pill badge-xs badge-danger"> {{ $task->priority }}</div>
                                                                        @endif
                                                                    </div>
                                                                    <div class="col-6 text-right">
                                                                        <div class="actions">
                                                                            <div class="dropdown">
                                                                                <a href="#" class="action-item" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                    <i class="fas fa-ellipsis-h"></i>
                                                                                </a>
                                                                                <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(22px, 31px, 0px);">
                                                                                    @if(\Auth::user()->type=='company')
                                                                                        <a class="dropdown-item" data-size="lg" href="#" data-url="{{ route('project.task.edit',$task->id) }}" data-ajax-popup="true" data-title="{{__('Edit Task')}}"> {{__('Edit')}}</a>
                                                                                    @endif
                                                                                    <a class="dropdown-item" data-size="lg" data-url="{{ route('project.task.show',$task->id) }}" data-ajax-popup="true" data-title="{{__('Task Detail')}}" class="h6" data-toggle="tooltip"> {{__('View')}}</a>
                                                                                    @if(\Auth::user()->type=='company')
                                                                                        <a class="dropdown-item" href="#" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('task-delete-form-{{$task->id}}').submit();"> {{__('Delete')}}</a>

                                                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['project.task.destroy', $task->id],'id'=>'task-delete-form-'.$task->id]) !!}
                                                                                        {!! Form::close() !!}
                                                                                    @endif

                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <a href="#" data-size="lg" data-url="{{ route('project.task.show',$task->id) }}" data-ajax-popup="true" data-title="{{__('Task Detail')}}" class="h6" data-toggle="tooltip">{{$task->title}}</a>
                                                                <div class="row">
                                                                    <div class="col">
                                                                        <div class="action-item">
                                                                            {{\Auth::user()->dateFormat($task->start_date)}}
                                                                        </div>
                                                                    </div>
                                                                    <div class="col text-right">
                                                                        <div class="action-item">
                                                                            {{\Auth::user()->dateFormat($task->due_date)}}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col">
                                                                        <div class="action-item">
                                                                            {{$task->taskCompleteCheckListCount()}}/{{$task->taskTotalCheckListCount()}}
                                                                        </div>
                                                                    </div>
                                                                    <div class="col text-right">
                                                                        <a href="#" class="avatar avatar-sm rounded-circle m-0">
                                                                            <img alt="image" data-toggle="tooltip" data-original-title="{{!empty($task->taskUser)?$task->taskUser->name:''}}" @if($task->taskUser && !empty($task->taskUser->avatar)) src="{{$profile.'/'.$task->taskUser->avatar}}" @else avatar="{{!empty($task->taskUser)?$task->taskUser->name:''}}" @endif class="">
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                    <span class="empty-container" data-placeholder="Empty"></span>
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
            <div id="ganttChart" class="tabs-card d-none">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card overflow-hidden ">
                            <div class="card-header actions-toolbar">
                                <div class="row justify-content-between align-items-center">
                                    <div class="col">
                                        <h6 class="d-inline-block mb-0">{{__('Gantt Chart')}}</h6>
                                    </div>
                                    <div class="col">
                                        <a href="#" class="btn btn-xs btn-info gantt-chart-mode  @if($duration == 'Quarter Day')active @endif" data-value="Quarter Day">{{__('Quarter Day')}}</a>
                                        <a href="#" class="btn btn-xs btn-info gantt-chart-mode @if($duration == 'Half Day')active @endif" data-value="Half Day">{{__('Half Day')}}</a>
                                        <a href="#" class="btn btn-xs btn-info gantt-chart-mode @if($duration == 'Day')active @endif" data-value="Day">{{__('Day')}}</a>
                                        <a href="#" class="btn btn-xs btn-info gantt-chart-mode @if($duration == 'Week')active @endif" data-value="Week">{{__('Week')}}</a>
                                        <a href="#" class="btn btn-xs btn-info gantt-chart-mode @if($duration == 'Month')active @endif" data-value="Month">{{__('Month')}}</a>
                                    </div>
                                </div>

                            </div>
                            <div class="card-body">
                                <svg id="gantt"></svg>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div id="milestone" class="tabs-card d-none">
                <div class="card">
                    <div class="card-header actions-toolbar">
                        <div class="row justify-content-between align-items-center">
                            <div class="col">
                                <h6 class="d-inline-block mb-0">{{__('Milestone')}}</h6>
                            </div>
                            @if(\Auth::user()->type=='company')
                                <div class="col text-right">
                                    <div class="actions">
                                        <a href="#" data-url="{{ route('project.milestone.create',$project->id) }}" data-ajax-popup="true" data-title="{{__('Create New Milestone')}}" class="action-item">
                                            <i class="ti ti-plus"></i>
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    @foreach($milestones as $milestone)
                        <div class="col-md-4">
                            <div class="card card-fluid">
                                <div class="card-header">
                                    <div class="row align-items-center">
                                        <div class="col ml-md-n2">
                                            <a href="#!" class="d-block h6 mb-0">{{$milestone->title}}</a>
                                            <small class="d-block text-muted text-justify">{{$milestone->description}}</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <span class="h6 mb-0">{{\Auth::user()->dateFormat($milestone->due_date)}}</span>
                                            <span class="d-block text-sm">{{__('Due Date')}}</span>
                                        </div>
                                        <div class="col text-right">
                                            <span class="h6 mb-0">{{\Auth::user()->priceFormat($milestone->cost)}}</span>
                                            <span class="d-block text-sm">{{__('Cost')}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="row align-items-center">
                                        <div class="col-6">
                                            @if($milestone->status =='complete')
                                                <div class="badge badge-success rounded-pill"> {{ $milestone->status }}</div>
                                            @elseif($milestone->status =='incomplete')
                                                <div class="badge badge-danger rounded-pill"> {{ $milestone->status }}</div>
                                            @endif
                                        </div>
                                        <div class="col-6 text-right">
                                            <a href="#" data-url="{{ route('project.milestone.edit',$milestone->id) }}" data-ajax-popup="true" data-title="{{__('Edit Milestone')}}" class="action-item" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
                                                <i class="far fa-edit"></i>
                                            </a>
                                            <a href="#!" class="action-item" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('milestone-delete-form-{{$milestone->id}}').submit();">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['project.milestone.destroy', $milestone->id],'id'=>'milestone-delete-form-'.$milestone->id]) !!}
                                            {!! Form::close() !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div id="notes" class="tabs-card d-none">
                <div class="card">
                    <div class="card-header actions-toolbar">
                        <div class="row justify-content-between align-items-center">
                            <div class="col">
                                <h6 class="d-inline-block mb-0">{{__('Notes')}}</h6>
                            </div>
                            @if(\Auth::user()->type=='company')
                                <div class="col text-right">
                                    <div class="actions">
                                        <a href="#" data-url="{{ route('project.note.create',$project->id) }}" data-ajax-popup="true" data-title="{{__('Create New Notes')}}" class="action-item">
                                            <i class="ti ti-plus"></i>
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    @foreach($notes as $note)
                        <div class="col-md-4">
                            <div class="card card-fluid">
                                <div class="card-header">
                                    <h6 class="mb-0">{{$note->title}}</h6>
                                </div>
                                <div class="card-body py-3 flex-grow-1">
                                    <p class="text-sm mb-0">
                                        {{$note->description}}
                                    </p>
                                </div>
                                <div class="card-footer py-0">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item px-0">
                                            <div class="row align-items-center">
                                                <div class="col-6">
                                                    <small>{{__('Created Date')}}</small>
                                                    <div class="h6 mb-0">{{\Auth::user()->dateFormat($note->created_at)}}</div>
                                                </div>
                                                @if(\Auth::user()->type=='company')
                                                    <div class="col-6 text-right">
                                                        <a href="#" data-url="{{ route('project.note.edit',[$project->id,$note->id]) }}" data-ajax-popup="true" data-title="{{__('Edit Note')}}" class="action-item" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
                                                            <i class="far fa-edit"></i>
                                                        </a>
                                                        <a href="#!" class="action-item" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('note-delete-form-{{$note->id}}').submit();">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['project.note.destroy', $project->id,$note->id],'id'=>'note-delete-form-'.$note->id]) !!}
                                                        {!! Form::close() !!}
                                                    </div>
                                                @endif
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div id="files" class="tabs-card d-none">
                <div class="card">
                    <div class="card-header actions-toolbar">
                        <div class="row justify-content-between align-items-center">
                            <div class="col">
                                <h6 class="d-inline-block mb-0">{{__('File')}}</h6>
                            </div>
                            @if(\Auth::user()->type=='company' || \Auth::user()->type == 'client')
                                <div class="col text-right">
                                    <div class="actions">
                                        <a href="#" data-url="{{ route('project.file.create',$project->id) }}" data-ajax-popup="true" data-title="{{__('Create New File')}}" class="action-item">
                                            <i class="ti ti-plus"></i>
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    @foreach($files as $file)
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-parent-child">
                                            <img alt="Image placeholder" src="{{asset(Storage::url('uploads/files')).'/'.$file->file}}" class="avatar  rounded-circle">
                                        </div>
                                        <div class="avatar-content ml-3">
                                            <h6 class="mb-0">{{$file->file}}</h6>
                                            <span class="text-sm text-muted"><i class="fas fa-calendar mr-2"></i>{{\Auth::user()->dateFormat($file->created_at)}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <p>{{$file->description}}</p>
                                    </div>

                                </div>
                                <div class="card-footer">
                                    <div class="actions text-right">
                                        <a href="{{asset(Storage::url('uploads/files')).'/'.$file->file}}" class="action-item" data-toggle="tooltip" download="" data-original-title="{{__('Download')}}">
                                            <i class="ti ti-download"></i>
                                        </a>
                                        <a href="#" data-url="{{ route('project.file.edit',[$project->id,$file->id]) }}" data-ajax-popup="true" data-title="{{__('Edit File')}}" class="action-item" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
                                            <i class="far fa-edit"></i>
                                        </a>
                                        <a href="#!" class="action-item" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('file-delete-form-{{$file->id}}').submit();">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                        {!! Form::open(['method' => 'DELETE', 'route' => ['project.file.destroy', $project->id,$file->id],'id'=>'file-delete-form-'.$file->id]) !!}
                                        {!! Form::close() !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div id="comments" class="tabs-card d-none">

                <div class="card">
                    <div class="card-header actions-toolbar">
                        <div class="row justify-content-between align-items-center">
                            <div class="col">
                                <h6 class="d-inline-block mb-0">{{__('Comments')}}</h6>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @foreach($comments as $comment)
                            <div class="media mb-2">
                                <a class="pr-2" href="#">
                                    <img @if(!empty($comment->commentUser && !empty($comment->commentUser->avatar))) src="{{$profile.'/'.$comment->commentUser->avatar}}" @else avatar="{{!empty($comment->commentUser)?$comment->commentUser->name:''}}" @endif class="rounded-circle" alt="" height="32">
                                </a>
                                <div class="media-body">
                                    <h6 class="mt-0">{{!empty($comment->commentUser)?$comment->commentUser->name:''}} <small class="text-muted float-right">{{$comment->created_at}}</small></h6>

                                    <p class="text-sm mb-0">
                                        {{$comment->comment}}
                                    </p>

                                    @if(!empty($comment->file))
                                        <a href="#" class="like active">
                                            <i class="ni ni-cloud-download-95"></i>
                                            <a href="{{asset(Storage::url('uploads/files')).'/'.$comment->file}}" download="" class="action-item" data-toggle="tooltip" data-original-title="{{__('Download')}}"> <i class="ti ti-download"></i> </a>
                                        </a>
                                    @endif
                                    <a href="#" data-url="{{route('project.comment.reply',[$project->id,$comment->id])}}" data-ajax-popup="true" data-title="{{__('Create Comment Reply')}}" class="action-item" data-toggle="tooltip" data-original-title="{{__('Reply')}}">
                                        <i class="fas fa-reply"></i>
                                    </a>

                                    @foreach($comment->subComment as $subComment)
                                        <div class="media mt-3">
                                            <a class="pr-2" href="#">
                                                <img @if(!empty($subComment->commentUser && !empty($subComment->commentUser->avatar))) src="{{$profile.'/'.$subComment->commentUser->avatar}}" @else  avatar="{{!empty($subComment->commentUser)?$subComment->commentUser->name:''}}" @endif class="rounded-circle" alt="" height="32">
                                            </a>
                                            <div class="media-body">
                                                <h6 class="mt-0">{{!empty($subComment->commentUser)?$subComment->commentUser->name:''}} <small class="text-muted float-right">{{$subComment->created_at}}</small></h6>
                                                <p class="text-sm mb-0">
                                                    {{$subComment->comment}}
                                                </p>

                                                @if(!empty($subComment->file))
                                                    <a href="{{asset(Storage::url('uploads/files')).'/'.$subComment->file}}" download="" class="text-muted"><i class="ti ti-download"></i></a>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach

                        <div class="border rounded mt-4">
                            {{ Form::open(array('route' => array('project.comment.store',$project->id),'enctype'=>"multipart/form-data")) }}
                            <textarea rows="3" class="form-control border-0 resize-none" name="comment" placeholder="Your comment..." required></textarea>
                            <div class="p-2 bg-light d-flex justify-content-between align-items-center">
                                <div>
                                    {{ Form::file('file', null, array('class' => 'form-control','required'=>'required')) }}
                                </div>
                                <button type="submit" class="btn btn-sm btn-primary rounded-pill"><i class='uil uil-message mr-1'></i>{{__('Post')}}</button>
                            </div>
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>
            </div>
            <div id="clientFeedback" class="tabs-card d-none">
                <div class="card">
                    <div class="card-header actions-toolbar">
                        <div class="row justify-content-between align-items-center">
                            <div class="col">
                                <h6 class="d-inline-block mb-0">{{__('Client Feedback')}}</h6>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @foreach($feedbacks as $feedback)
                            <div class="media mb-2">
                                <a class="pr-2" href="#">
                                    <img @if(!empty($feedback->feedbackUser) && !empty($feedback->feedbackUser->avatar)) src="{{$profile.'/'.$feedback->feedbackUser->avatar}}" @else  avatar="{{!empty($feedback->feedbackUser)?$feedback->feedbackUser->name:''}}" @endif class="rounded-circle" alt="" height="32">
                                </a>
                                <div class="media-body">
                                    <h6 class="mt-0">{{!empty($feedback->feedbackUser)?$feedback->feedbackUser->name:''}} <small class="text-muted float-right">{{$feedback->created_at}}</small></h6>

                                    <p class="text-sm mb-0">
                                        {{$feedback->feedback}}
                                    </p>

                                    @if(!empty($feedback->file))
                                        <a href="#" class="like active">
                                            <i class="ni ni-cloud-download-95"></i>
                                            <a href="{{asset(Storage::url('uploads/files')).'/'.$feedback->file}}" download="" class="action-item" data-toggle="tooltip" data-original-title="{{__('Download')}}"> <i class="ti ti-download"></i> </a>
                                        </a>
                                    @endif
                                    <a href="#" data-url="{{route('project.client.feedback.reply',[$project->id,$feedback->id])}}" data-ajax-popup="true" data-title="{{__('Create Comment Reply')}}" class="action-item" data-toggle="tooltip" data-original-title="{{__('Reply')}}">
                                        <i class="fas fa-reply"></i>
                                    </a>

                                    @foreach($feedback->subFeedback as $subComment)
                                        <div class="media mt-3">
                                            <a class="pr-2" href="#">
                                                <img @if(!empty($subComment->feedbackUser) && !empty($subComment->feedbackUser->avatar)) src="{{$profile.'/'.$subComment->feedbackUser->avatar}}" @else  avatar="{{!empty($subComment->feedbackUser)?$subComment->feedbackUser->name:''}}" @endif class="rounded-circle" alt="" height="32">
                                            </a>
                                            <div class="media-body">
                                                <h6 class="mt-0">{{!empty($subComment->feedbackUser)?$subComment->feedbackUser->name:''}} <small class="text-muted float-right">{{$subComment->created_at}}</small></h6>
                                                <p class="text-sm mb-0">
                                                    {{$subComment->feedback}}
                                                </p>

                                                @if(!empty($subComment->file))
                                                    <a href="{{asset(Storage::url('uploads/files')).'/'.$subFeedback->file}}" download="" class="text-muted"><i class="ti ti-download"></i></a>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach

                        <div class="border rounded mt-4">
                            {{ Form::open(array('route' => array('project.client.feedback.store',$project->id),'enctype'=>"multipart/form-data")) }}
                            <textarea rows="3" class="form-control border-0 resize-none" name="feedback" placeholder="Your feedback..." required></textarea>
                            <div class="p-2 bg-light d-flex justify-content-between align-items-center">
                                <div>
                                    {{ Form::file('file', null, array('class' => 'form-control','required'=>'required')) }}
                                </div>
                                <button type="submit" class="btn btn-sm btn-primary rounded-pill"><i class='uil uil-message mr-1'></i>{{__('Post')}}</button>
                            </div>
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>
            </div>
            <div id="invoice" class="tabs-card d-none">
                <div class="card">
                    <div class="card-header actions-toolbar">
                        <div class="row justify-content-between align-items-center">
                            <div class="col">
                                <h6 class="d-inline-block mb-0">{{__('Invoices')}}</h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    @foreach($invoices as $invoice)

                        <div class="col-md-6">
                            <div class="card hover-shadow-lg">
                                <div class="card-header border-0">
                                    <div class="row align-items-center">
                                        <div class="col-10">
                                            <h6 class="mb-0">
                                                <a href="{{route('invoice.show',\Crypt::encrypt($invoice->id))}}">{{\Auth::user()->invoiceNumberFormat($invoice->invoice_id)}}</a>
                                            </h6>
                                        </div>
                                        <div class="col-2 text-right">
                                            <div class="actions">
                                                <div class="dropdown">
                                                    <a href="#" class="action-item" data-toggle="dropdown"><i class="fas fa-ellipsis-v"></i></a>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        @if(\Auth::user()->type=='company' || \Auth::user()->type=='client')
                                                            <a href="{{route('invoice.show',\Crypt::encrypt($invoice->id))}}" class="dropdown-item">
                                                                {{__('View')}}
                                                            </a>
                                                        @endif

                                                        @if(\Auth::user()->type=='company')
                                                            <a href="#!" data-url="{{ route('invoice.edit',$invoice->id) }}" class="dropdown-item" data-toggle="tooltip" data-original-title="{{__('Edit')}}" data-ajax-popup="true" data-title="{{__('Edit Invoice')}}">
                                                                {{__('Edit')}}
                                                            </a>

                                                            <a href="#!" class="dropdown-item" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('invoice-delete-form-{{$invoice->id}}').submit();">
                                                                {{__('Delete')}}
                                                            </a>
                                                            {!! Form::open(['method' => 'DELETE', 'route' => ['invoice.destroy', $invoice->id],'id'=>'invoice-delete-form-'.$invoice->id]) !!}
                                                            {!! Form::close() !!}
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="p-3 border border-dashed">
                                        @if($invoice->status == 0)
                                            <span class="badge badge-primary">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                        @elseif($invoice->status == 1)
                                            <span class="badge badge-warning">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                        @elseif($invoice->status == 2)
                                            <span class="badge badge-danger">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                        @elseif($invoice->status == 3)
                                            <span class="badge badge-info">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                        @elseif($invoice->status == 4)
                                            <span class="badge badge-success">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                        @endif
                                        <div class="row align-items-center mt-3">
                                            <div class="col-6">
                                                <h6 class="mb-0">{{\Auth::user()->priceFormat($invoice->getTotal())}}</h6>
                                                <span class="text-sm text-muted">{{__('Total Amount')}}</span>
                                            </div>
                                            <div class="col-6">
                                                <h6 class="mb-0">{{\Auth::user()->priceFormat($invoice->getDue())}}</h6>
                                                <span class="text-sm text-muted">{{__('Due Amount')}}</span>
                                            </div>
                                        </div>
                                        <div class="row align-items-center mt-3">
                                            <div class="col-6">
                                                <h6 class="mb-0">{{\Auth::user()->dateFormat($invoice->issue_date)}}</h6>
                                                <span class="text-sm text-muted">{{__('Issue Date')}}</span>
                                            </div>
                                            <div class="col-6">
                                                <h6 class="mb-0">{{\Auth::user()->dateFormat($invoice->due_date)}}</h6>
                                                <span class="text-sm text-muted">{{__('Due Date')}}</span>
                                            </div>
                                        </div>

                                    </div>
                                    @if(\Auth::user()->type != 'client')
                                        @php $client=$invoice->clients @endphp
                                        <div class="media mt-4 align-items-center">
                                            <img @if(!empty($client->avatar)) src="{{$profile.'/'.$client->avatar}}" @else avatar="{{$invoice->clients->name}}" @endif class="avatar rounded-circle avatar-custom" data-toggle="tooltip" data-original-title="{{__('Client')}}">
                                            <div class="media-body pl-3">
                                                <div class="text-sm my-0">{{!empty($invoice->clients)?$invoice->clients->name:''}}</div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div id="timesheets" class="tabs-card d-none">
                <div class="card">
                    <div class="card-header actions-toolbar border-0">
                        <div class="row justify-content-between align-items-center">
                            <div class="col">
                                <h6 class="d-inline-block mb-0">{{__('Timesheets')}}</h6>
                            </div>
                            <div class="col text-right">
                                <div class="actions">
                                    @if(\Auth::user()->type=='company')
                                        <a href="#" data-url="{{ route('project.timesheet.create',$project->id) }}" data-ajax-popup="true" data-title="{{__('Create New Timesheet')}}" class="action-item">
                                            <i class="ti ti-plus"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-items-center">
                            <thead>
                            <tr>
                                <th>{{__('Member')}}</th>
                                <th>{{__('Task')}}</th>
                                <th>{{__('Start Date')}}</th>
                                <th>{{__('Start Time')}}</th>
                                <th>{{__('End Date')}}</th>
                                <th>{{__('End Time')}}</th>
                                <th>{{__('Notes')}}</th>
                                @if(\Auth::user()->type=='company')
                                    <th class="text-right">{{__('Action')}}</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody class="list">
                            @foreach($timesheets as $timesheet)
                                <tr>
                                    <td>{{!empty($timesheet->users)?$timesheet->users->name:''}}</td>
                                    <td> {{!empty($timesheet->tasks)?$timesheet->tasks->title:''}}</td>
                                    <td>{{\Auth::user()->dateFormat($timesheet->start_date)}}</td>
                                    <td>{{\Auth::user()->timeFormat($timesheet->start_time)}}</td>
                                    <td>{{\Auth::user()->dateFormat($timesheet->end_date)}}</td>
                                    <td>{{\Auth::user()->timeFormat($timesheet->end_time)}}</td>
                                    <td><a href="#" data-url="{{ route('project.timesheet.note',[$project->id,$timesheet->id]) }}" data-ajax-popup="true" data-toggle="tooltip" data-title="{{__('Timesheet Notes')}}" class="action-item"><i class="fa fa-comment"></i></a></td>
                                    @if(\Auth::user()->type=='company')
                                        <td class="table-actions text-right">
                                            <a href="#" data-url="{{ route('project.timesheet.edit',[$project->id,$timesheet->id]) }}" data-ajax-popup="true" data-title="{{__('Edit Timesheet')}}" class="action-item" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
                                                <i class="far fa-edit"></i>
                                            </a>
                                            <a href="#!" class="action-item" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('timesheet-delete-form-{{$timesheet->id}}').submit();">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['project.timesheet.destroy', $project->id,$timesheet->id],'id'=>'timesheet-delete-form-'.$timesheet->id]) !!}
                                            {!! Form::close() !!}
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div id="payment" class="tabs-card d-none">
                <div class="card">
                    <div class="card-header actions-toolbar border-0">
                        <div class="row justify-content-between align-items-center">
                            <div class="col">
                                <h6 class="d-inline-block mb-0">{{__('Payment')}}</h6>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-items-center">
                            <thead>
                            <tr>
                                <th>{{__('Transaction ID')}}</th>
                                <th>{{__('Invoice ID')}}</th>
                                <th>{{__('Payment Date')}}</th>
                                <th>{{__('Payment Method')}}</th>
                                <th>{{__('Payment Type')}}</th>
                                <th>{{__('Notes')}}</th>
                                <th>{{__('Amount')}}</th>
                                <th>{{__('Action')}}</th>
                            </tr>
                            </thead>
                            <tbody class="list">
                            @foreach($invoices as $invoice)
                                @foreach($invoice->payments as $payment)
                                    <tr>
                                        <td>{{\Auth::user()->invoiceNumberFormat($invoice->invoice_id)}} </td>
                                        <td>{{$payment->transaction}} </td>
                                        <td>{{\Auth::user()->dateFormat($payment->date)}} </td>
                                        <td>{{!empty($payment->payments)?$payment->payments->name:''}} </td>
                                        <td>{{$payment->payment_type}} </td>
                                        <td>{{$payment->notes}} </td>
                                        <td> {{\Auth::user()->priceFormat(($payment->amount))}}</td>
                                        <td width="7%" class="text-right">
                                            <a href="{{route('invoice.show',\Crypt::encrypt($invoice->id))}}" class="action-item" data-toggle="tooltip" data-original-title="{{__('View')}}">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div id="expense" class="tabs-card d-none">
                <div class="card">
                    <div class="card-header actions-toolbar border-0">
                        <div class="row justify-content-between align-items-center">
                            <div class="col">
                                <h6 class="d-inline-block mb-0">{{__('Expenses')}}</h6>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-items-center">
                            <thead>
                            <tr>
                                <th> {{__('Date')}}</th>
                                <th> {{__('Amount')}}</th>
                                <th> {{__('User')}}</th>
                                <th> {{__('Attachment')}}</th>
                                <th> {{__('Description')}}</th>
                            </tr>
                            </thead>
                            <tbody class="list">
                            @foreach ($project->expenses as $expense)
                                <tr class="font-style">
                                    <td>{{  Auth::user()->dateFormat($expense->date)}}</td>
                                    <td>{{  Auth::user()->priceFormat($expense->amount)}}</td>
                                    <td>{{  (!empty($expense->users)?$expense->users->name:'')}}</td>
                                    <td>
                                        @if(!empty($expense->attachment))
                                            <a href="{{asset(Storage::url('uploads/attachment/'. $expense->attachment))}}" target="_blank">{{  $expense->attachment}}</a>
                                        @else
                                            --
                                        @endif
                                    </td>
                                    <td>{{  $expense->description}}</td>

                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

    <div class="row">
        <!-- [ sample-page ] start -->
        <div class="col-sm-12">
            <div class="row">
                <div class="col-xl-3">
                    <div class="card sticky-top" style="top:30px">
                        <div class="list-group list-group-flush" id="useradd-sidenav">
                            <a href="#useradd-1" class="list-group-item list-group-item-action">{{ __('Overview') }} <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                            <a href="#useradd-2" class="list-group-item list-group-item-action">{{__('Task List')}} <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                            <a href="#useradd-3" class="list-group-item list-group-item-action">{{__('Task Kanban')}} <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                            <a href="#useradd-4" class="list-group-item list-group-item-action">{{__('Gantt Chart')}} <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                            <a href="#useradd-5" class="list-group-item list-group-item-action">{{__('Milestone')}} <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                            <a href="#useradd-6" class="list-group-item list-group-item-action">{{__('Notes')}} <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                            <a href="#useradd-7" class="list-group-item list-group-item-action">{{__('Files')}} <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                            <a href="#useradd-8" class="list-group-item list-group-item-action">{{__('Comments')}} <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                            <a href="#useradd-9" class="list-group-item list-group-item-action">{{__('Client Feedback')}} <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                            @if(\Auth::user()->type=='company' || \Auth::user()->type=='client')
                                <a href="#useradd-10" class="list-group-item list-group-item-action">{{__('Invoice')}} <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                            @endif
                            @if(\Auth::user()->type=='company' || \Auth::user()->type=='employee')
                                <a href="#useradd-11" class="list-group-item list-group-item-action">{{__('Timesheets')}} <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                            @endif
                            @if(\Auth::user()->type=='company' || \Auth::user()->type=='client')
                                <a href="#useradd-12" class="list-group-item list-group-item-action">{{__('Payment')}} <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                            @endif
                            @if(\Auth::user()->type=='company')
                                <a href="#useradd-13" class="list-group-item list-group-item-action">{{__('Expense')}} <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                            @endif
                        </div>
                    </div>
                </div>

                
                <div class="col-xl-9">
                    @php
                        $percentages = 0;
                        $total=count($project->tasks);
                        if($total != 0){
                            $percentages= $project->completedTask() / ($total /100);
                        }
                    @endphp

                    <div id="useradd-1">
                        <div class="row">
                            <div class="col-xxl-6">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6 col-sm-6">
                                                <h5>{{$project->title}}</h5>
                                            </div>
                                            <div class="col-md-6 col-sm-6">
                                                <div class="progress mb-0">
                                                    <div class="progress-bar bg-success"  style="width: {{$percentages}}%;"></div>
                                                    <h6 class="mb-0  mt-2">{{__('Completed')}}: <b> {{$percentages}}%</b></h6>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-4">
                                            <div class="col-sm-12">
                                                <p class="text-sm text-muted mb-2">{{$project->description}}</p>
                                            </div>
                                        </div>

                                        <div class="row  mt-4">
                                            <div class="col-md-4 col-sm-6">
                                                <div class="d-flex align-items-start">
                                                    <div class="theme-avtar bg-success">
                                                        <i class="ti ti-calendar"></i>
                                                    </div>
                                                    <div class="ms-2">
                                                        <p class="text-muted text-sm mb-0">{{ __('Start Date') }}:</p>
                                                        <p class="mb-0 text-success">{{\Auth::user()->dateFormat($project->start_date)}}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-6 my-3 my-sm-0">
                                                <div class="d-flex align-items-start">
                                                    <div class="theme-avtar bg-info">
                                                        <i class="ti ti-calendar-time"></i>
                                                    </div>
                                                    <div class="ms-2">
                                                        <p class="text-muted text-sm mb-0">{{__('Due Date')}}:</p>
                                                        <p class="mb-0 text-info">{{\Auth::user()->dateFormat($project->due_date)}}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-6">
                                                <div class="d-flex align-items-start">
                                                    <div class="theme-avtar bg-danger">
                                                        <i class="ti ti-brand-hipchat"></i>
                                                    </div>
                                                    <div class="ms-2">
                                                        <p class="text-muted text-sm mb-0">{{__('Comments')}}:</p>
                                                        <p class="mb-0 text-danger">{{count($comments)}}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row  mt-4">
                                            <div class="col-md-4 col-sm-6">
                                                <div class="d-flex align-items-start">
                                                    <div class="theme-avtar bg-warning">
                                                        <i class="ti ti-user"></i>
                                                    </div>
                                                    <div class="ms-2">
                                                        <p class="text-muted text-sm mb-0">{{__('Members')}}:</p>
                                                        <p class="mb-0 text-warning">{{count($project->projectUser())}}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-6 my-3 my-sm-0">
                                                <div class="d-flex align-items-start">
                                                    <div class="theme-avtar bg-dark">
                                                        <i class="ti ti-calendar-event"></i>
                                                    </div>
                                                    <div class="ms-2">
                                                        <p class="text-muted text-sm mb-0">{{__('Days Left')}}:</p>
                                                        <p class="mb-0 text-dark">{{$daysleft}}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-header">
                                        <div class="float-end">
                                            <p class="text-muted d-none d-sm-flex align-items-center mb-0">
                                                <a href="#" class="btn btn-sm btn-primary btn-icon" data-bs-toggle="modal" 
                                                    data-bs-target="#exampleModal" data-url="{{ route('project.user',$project->id) }}"
                                                    data-bs-whatever="{{__('Add User')}}"data-bs-toggle="tooltip" title="{{ __('Add User') }}" 
                                                    data-bs-original-title="{{__('Add User')}}"> <span class="text-white"> 
                                                        <i class="ti ti-plus"></i></span>
                                                    </a>
                                                </p>
                                        </div>
                                        <h5 class="mb-0">{{__('Project members')}}</h5>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-group list-group-flush">
                                            @foreach($project->projectUser() as $user)
                                                @php $totalTask= $project->user_project_total_task($user->project_id,$user->user_id) @endphp
                                                @php $completeTask= $project->user_project_complete_task($user->project_id,$user->user_id,($project->project_last_stage())?$project->project_last_stage()->id:'' ) @endphp
                                                <div class="list-group-item">
                                                    <div class="row align-items-center">
                                                        <div class="col-auto">
                                                            <!-- Avatar -->
                                                            <a href="#" class="avatar rounded-circle user-group1">
                                                                <img alt="Image placeholder" class="" @if(!empty($user->avatar)) src="{{$profile.'/'.$user->avatar}}" @else  avatar="{{$user->name}}" @endif>
                                                            </a>
                                                        </div>
                                                        <div class="col ml-n2">
                                                            <a href="#!" class="d-block h6 mb-0">{{$user->name}}</a>
                                                            <small>{{$user->email}}</small>
                                                        </div>
                                                        <div class="col-auto">
                                                            <div class="action-btn bg-warning ms-2">
                                                                <a href="{{ route('employee.show',\Crypt::encrypt($user->user_id)) }}" class="action-item" data-toggle="tooltip" data-original-title="{{__('View')}}">
                                                                    <i class="ti ti-eye text-white"></i>
                                                                </a>
                                                            </div>

                                                            <div class="action-btn bg-danger ms-2">
                                                                {!! Form::open(['method' => 'DELETE',  'route' => ['project.user.destroy', $project->id,$user->user_id],'id'=>'project-user-delete-form-'.$user->id]) !!}
                                                                    @csrf
                                                                    <input name="_method" type="hidden" value="DELETE">
                                                                    <button type="submit" class="mx-3 btn btn-sm d-inline-flex align-items-center show_confirm" data-toggle="tooltip"
                                                                    title='Delete'>
                                                                    <span class="text-white"> <i
                                                                        class="ti ti-trash"></i></span>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                            
                                                           
                                                            {{-- <a href="#!" class="action-item" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('project-user-delete-form-{{$user->id}}').submit();">
                                                                <i class="fas fa-trash"></i>
                                                            </a>
                                                            {!! Form::open(['method' => 'DELETE', 'route' => ['project.user.destroy', $project->id,$user->user_id],'id'=>'project-user-delete-form-'.$user->id]) !!}
                                                            {!! Form::close() !!} --}}
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6">
                                <div class="row">
                                    <div class="col-lg-4 col-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="theme-avtar bg-success">
                                                    <i class="ti ti-report-money"></i>
                                                </div>
                                                <h6 class="mb-3 mt-2">{{__('Budget')}}</h6>
                                                <h3 class="mb-0">{{\Auth::user()->priceFormat($project->price)}} </h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="theme-avtar bg-info">
                                                    <i class="ti ti-click"></i>
                                                </div>
                                                <h6 class="mb-3 mt-2">{{__('Expense')}}</h6>
                                                <h3 class="mb-0">{{\Auth::user()->priceFormat($totalExpense)}} </h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="theme-avtar bg-warning">
                                                    <i class="ti ti-user-plus"></i>
                                                </div>
                                                <h6 class="mb-3 mt-2">{{__('Client')}}</h6>
                                                <h6 class="mb-0">{{!empty($project->clients)?$project->clients->name:''}} </h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>

                    <!--Milestone-->
                    <div id="useradd-5">
                        <div class="card">
                            <div class="card-header">
                                <div class="float-end">
                                    @if(\Auth::user()->type=='company')
                                        <p class="text-muted d-none d-sm-flex align-items-center mb-0">
                                            <a href="#" class="btn btn-sm btn-primary btn-icon m-1" data-bs-toggle="modal" 
                                            data-bs-target="#exampleModal" data-url="{{ route('project.milestone.create',$project->id) }}"
                                            data-bs-whatever="{{__('Create New Milestone')}}"data-bs-toggle="tooltip" title="{{ __('Create') }}" 
                                            data-bs-original-title="{{__('Create New Milestone')}}"> <span class="text-white"> 
                                                <i class="ti ti-plus text-white"></i></span>
                                            </a>
                                        </p>
                                    @endif
                                </div>
                                <h5 class="mb-0">{{ __('Milestone') }}</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table" id="pc-dt-simple">
                                        <thead>
                                            <tr>
                                                <th scope="col">{{__('Title')}}</th>
                                                <th scope="col">{{__('Description')}}</th>
                                                <th scope="col">{{__('Due Date')}}</th>
                                                <th scope="col">{{__('Cost')}}</th>
                                                <th scope="col">{{__('Status')}}</th>
                                                @if(\Auth::user()->type=='company')
                                                    <th scope="col" class="text-right">{{__('Action')}}</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($milestones as $milestone)
                                                <tr>
                                                    <th scope="row">
                                                        <div class="media align-items-center">
                                                            <div class="media-body">
                                                                <a href="#" class="name h6 mb-0 text-sm">{{$milestone->title}}</a><br>
                            
                                                            </div>
                                                        </div>
                                                    </th>
                                                    <td>{{ $milestone->description }}</td>
                                                    <td>{{\Auth::user()->dateFormat($milestone->due_date)}}</td>
                                                    <td>{{\Auth::user()->priceFormat($milestone->cost)}}</td>
                                                    <td><span class="badge bg-info p-2 px-3 rounded">{{ $milestone->status }}</span></td>
                                                   
                                                    
                                                    
                                                    <td class="text-right">
                                                        <div class="action-btn bg-info ms-2">
                                                            <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-bs-toggle="modal"
                                                            data-bs-target="#exampleModal" data-url="{{ route('project.milestone.edit',$milestone->id) }}"
                                                            data-bs-whatever="{{__('Edit Milestone')}}" data-bs-toggle="tooltip" title="Edit Milestone    " 
                                                            data-bs-original-title="{{__('Edit Milestone ')}}"> <span class="text-white"> <i
                                                                    class="ti ti-edit"></i></span></a>
                                                        </div>
                
                                                        <div class="action-btn bg-danger ms-2">
                                                                {!! Form::open(['method' => 'DELETE', 'route' => ['project.milestone.destroy', $milestone->id],'id'=>'milestone-delete-form-'.$milestone->id]) !!}
                                                                @csrf
                                                                <input name="_method" type="hidden" value="DELETE">
                                                                <button type="submit" class="mx-3 btn btn-sm d-inline-flex align-items-center show_confirm" data-toggle="tooltip"
                                                                title='Delete'>
                                                                <span class="text-white"> <i
                                                                    class="ti ti-trash"></i></span>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--Notes-->
                    <div id="useradd-6">
                        <div class="card">
                            <div class="card-header">
                                <div class="float-end">
                                    @if(\Auth::user()->type=='company')
                                        <p class="text-muted d-none d-sm-flex align-items-center mb-0">
                                            <a href="#" class="btn btn-sm btn-primary btn-icon m-1" data-bs-toggle="modal" 
                                            data-bs-target="#exampleModal" data-url="{{ route('project.note.create',$project->id) }}"
                                            data-bs-whatever="{{__('Create New Notes')}}"data-bs-toggle="tooltip" title="{{ __('Create') }}" 
                                            data-bs-original-title="{{__('Create New Notes')}}"> <span class="text-white"> 
                                                <i class="ti ti-plus text-white"></i></span>
                                            </a>
                                        </p>
                                    @endif
                                </div>
                                <h5 class="mb-0">{{ __('Notes') }}</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table" id="pc-dt-simple">
                                        <thead>
                                            <tr>
                                                <th scope="col">{{__('Title')}}</th>
                                                <th scope="col">{{__('Description')}}</th>
                                                <th scope="col">{{__('Created Date')}}</th>
                                                <th scope="col" class="text-end">{{__('Action')}}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($notes as $note)
                                                <tr>
                                                    <th scope="row">
                                                        <div class="media align-items-center">
                                                            <div class="media-body">
                                                                <a href="#" class="name h6 mb-0 text-sm">{{$note->title}}</a><br>
                                                            </div>
                                                        </div>
                                                    </th>
                                                    <td>{{ $note->description }}</td>
                                                    <td>{{\Auth::user()->dateFormat($note->created_at)}}</td>
                                                    <td class="text-end">
                                                        <div class="action-btn bg-info ms-2">
                                                            <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-bs-toggle="modal"
                                                            data-bs-target="#exampleModal" data-url="{{ route('project.note.edit',[$project->id,$note->id]) }}"
                                                            data-bs-whatever="{{__('Edit Notes')}}" data-bs-toggle="tooltip" title="Edit Notes    " 
                                                            data-bs-original-title="{{__('Edit Notes ')}}"> <span class="text-white"> <i
                                                                    class="ti ti-edit"></i></span></a>
                                                        </div>
                
                                                        <div class="action-btn bg-danger ms-2">
                                                            {!! Form::open(['method' => 'DELETE', 'route' => ['project.note.destroy', $project->id,$note->id],'id'=>'note-delete-form-'.$note->id]) !!}
                                                                @csrf
                                                                <input name="_method" type="hidden" value="DELETE">
                                                                <button type="submit" class="mx-3 btn btn-sm d-inline-flex align-items-center show_confirm" data-toggle="tooltip"
                                                                title='Delete'>
                                                                <span class="text-white"> <i
                                                                    class="ti ti-trash"></i></span>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--Files-->
                    <div id="useradd-7">
                        <div class="card">
                            <div class="card-header">
                                <div class="float-end">
                                    @if(\Auth::user()->type=='company' || \Auth::user()->type == 'client')
                                        <p class="text-muted d-none d-sm-flex align-items-center mb-0">
                                            <a href="#" class="btn btn-sm btn-primary btn-icon m-1" data-bs-toggle="modal" 
                                            data-bs-target="#exampleModal" data-url="{{ route('project.file.create',$project->id) }}"
                                            data-bs-whatever="{{__('Create New Files')}}"data-bs-toggle="tooltip" title="{{ __('Create') }}" 
                                            data-bs-original-title="{{__('Create New Files')}}"> <span class="text-white"> 
                                                <i class="ti ti-plus text-white"></i></span>
                                            </a>
                                        </p>
                                    @endif
                                </div>
                                <h5 class="mb-0">{{ __('Files') }}</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table" id="pc-dt-simple">
                                        <thead>
                                            <tr>
                                                <th scope="col">{{__('#')}}</th>
                                                <th scope="col">{{__('Title')}}</th>
                                                <th scope="col">{{__('Created Date')}}</th>
                                                <th scope="col" class="text-end">{{__('Action')}}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($files as $file)
                                                <tr>
                                                    <th scope="row">
                                                        <div class="media align-items-center">
                                                            <div class="media-body user-group1">
                                                                <img alt="Image placeholder" src="{{asset(Storage::url('uploads/files')).'/'.$file->file}}" class=""><br>
                                                            </div>
                                                        </div>
                                                    </th>
                                                    <td>{{$file->file}}</td>
                                                    <td>{{\Auth::user()->dateFormat($file->created_at)}}</td>
                                                    <td class="text-end">
                                                        <div class="action-btn bg-warning ms-2">
                                                            <a href="{{asset(Storage::url('uploads/files')).'/'.$file->file}}" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-bs-toggle="tooltip" download="" data-original-title="{{__('Download')}}">
                                                                <i class="ti ti-arrow-bar-to-down text-white"></i>
                                                            </a>
                                                        </div>

                                                        <div class="action-btn bg-info ms-2">
                                                            <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-bs-toggle="modal"
                                                            data-bs-target="#exampleModal" data-url="{{ route('project.file.edit',[$project->id,$file->id]) }}"
                                                            data-bs-whatever="{{__('Edit Files')}}" data-bs-toggle="tooltip" title="{{ __('Edit Files') }}" 
                                                            data-bs-original-title="{{__('Edit Files ')}}"> <span class="text-white"> <i
                                                                    class="ti ti-edit"></i></span></a>
                                                        </div>
                
                                                        <div class="action-btn bg-danger ms-2">
                                                            {!! Form::open(['method' => 'DELETE', 'route' => ['project.file.destroy', $project->id,$file->id],'id'=>'file-delete-form-'.$file->id]) !!}
                                                                @csrf
                                                                <input name="_method" type="hidden" value="DELETE">
                                                                <button type="submit" class="mx-3 btn btn-sm d-inline-flex align-items-center show_confirm" data-toggle="tooltip"
                                                                title='Delete'>
                                                                <span class="text-white"> <i
                                                                    class="ti ti-trash"></i></span>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--Comments-->
                    <div id="useradd-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">{{ __('Comments') }}</h5>
                            </div>
                            <div class="card-body">
                                @foreach($comments as $comment)
                                    <div class="media mb-2">
                                        <a class="pr-2" href="#">
                                            <img @if(!empty($comment->commentUser && !empty($comment->commentUser->avatar))) src="{{$profile.'/'.$comment->commentUser->avatar}}" @else avatar="{{!empty($comment->commentUser)?$comment->commentUser->name:''}}" @endif class="rounded-circle" alt="" height="32">
                                        </a>
                                        <div class="media-body">
                                            <h6 class="mt-0 ms-2">{{!empty($comment->commentUser)?$comment->commentUser->name:''}} <small class="text-muted float-right">{{$comment->created_at}}</small></h6>

                                            <p class="text-sm mb-0 ms-2">
                                                {{$comment->comment}}
                                            </p>
                                            <div class="text-end">
                                                    @if(!empty($comment->file))
                                                    <div class="action-btn bg-warning ms-2">
                                                        <a href="#" class="like active">
                                                            <i class="ni ni-cloud-download-95"></i>
                                                            <a href="{{asset(Storage::url('uploads/files')).'/'.$comment->file}}" download="" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-bs-toggle="tooltip" title="{{__('Download')}}"> <i class="ti ti-download text-white"></i> </a>
                                                        </a>
                                                    </div>
                                                    @endif
                                                <div class="action-btn bg-info ms-2">
                                                    <a href="#" data-url="{{route('project.comment.reply',[$project->id,$comment->id])}}"data-bs-toggle="modal"
                                                        data-bs-target="#exampleModal"  data-bs-whatever="{{__('Create Comment Reply')}}"
                                                        class="mx-3 btn btn-sm d-inline-flex align-items-center" data-bs-toggle="tooltip" title="{{__('Reply')}}">
                                                        <i class="ti ti-send text-white"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            @foreach($comment->subComment as $subComment)
                                                <div class="media mt-3">
                                                    <a class="pr-2" href="#">
                                                        <img @if(!empty($subComment->commentUser && !empty($subComment->commentUser->avatar))) src="{{$profile.'/'.$subComment->commentUser->avatar}}" @else  avatar="{{!empty($subComment->commentUser)?$subComment->commentUser->name:''}}" @endif class="rounded-circle" alt="" height="32">
                                                    </a>
                                                    <div class="media-body">
                                                        <h6 class="mt-0 ms-2">{{!empty($subComment->commentUser)?$subComment->commentUser->name:''}} <small class="text-muted float-right">{{$subComment->created_at}}</small></h6>
                                                        <p class="text-sm mb-0 ms-2">
                                                            {{$subComment->comment}}
                                                        </p>

                                                        @if(!empty($subComment->file))
                                                            <div class="action-btn bg-warning ms-2">
                                                                <a href="{{asset(Storage::url('uploads/files')).'/'.$subComment->file}}" download="" data-bs-toggle="tooptip" title="{{ __('Download') }}" class="mx-3 btn btn-sm d-inline-flex align-items-center"><i class="ti ti-download text-white"></i></a>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach

                                <div class="border rounded mt-4">

                                    {{ Form::open(array('route' => array('project.comment.store',$project->id),'enctype'=>"multipart/form-data")) }}
                                    <textarea rows="3" class="form-control border-0 resize-none" name="comment" placeholder="Your comment..." required></textarea>
                                    <div class="p-2 bg-light d-flex justify-content-between align-items-center">
                                        <div>
                                            {{ Form::file('file', null, array('class' => 'form-control','required'=>'required')) }}
                                        </div>
                                        <button type="submit" class="btn btn-primary"><i class='uil uil-message mr-1'></i>{{__('Post')}}</button>
                                    </div>
                                    {{ Form::close() }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--Client Feedback-->
                    <div class="useradd-9">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">{{ __('Client Feedback') }}</h5>
                            </div>
                            <div class="card-body">
                                @foreach($feedbacks as $feedback)
                                    <div class="media mb-2">
                                        <a class="pr-2" href="#">
                                            <img @if(!empty($feedback->feedbackUser) && !empty($feedback->feedbackUser->avatar)) src="{{$profile.'/'.$feedback->feedbackUser->avatar}}" @else  avatar="{{!empty($feedback->feedbackUser)?$feedback->feedbackUser->name:''}}" @endif class="rounded-circle" alt="" height="32">
                                        </a>
                                        <div class="media-body">
                                            <h6 class="mt-0 ms-2">{{!empty($feedback->feedbackUser)?$feedback->feedbackUser->name:''}} <small class="text-muted float-right">{{$feedback->created_at}}</small></h6>

                                            <p class="text-sm mb-0 ms-2">
                                                {{$feedback->feedback}}
                                            </p>
                                            <div class="text-end">
                                                    @if(!empty($feedback->file))
                                                    <div class="action-btn bg-warning ms-2">
                                                        <a href="#" class="like active">
                                                            <i class="ni ni-cloud-download-95"></i>
                                                            <a href="{{asset(Storage::url('uploads/files')).'/'.$feedback->file}}" download="" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-bs-toggle="tooltip" title="{{__('Download')}}"> <i class="ti ti-download text-white"></i> </a>
                                                        </a>
                                                    </div>
                                                    @endif
                                                <div class="action-btn bg-info ms-2">
                                                    <a href="#" data-url="{{route('project.client.feedback.reply',[$project->id,$feedback->id])}}"data-bs-toggle="modal"
                                                        data-bs-target="#exampleModal"  data-bs-whatever="{{__('Create feedback Reply')}}"
                                                        class="mx-3 btn btn-sm d-inline-flex align-items-center" data-bs-toggle="tooltip" title="{{__('Reply')}}">
                                                        <i class="ti ti-send text-white"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            @foreach($feedback->subfeedback as $subfeedback)
                                                <div class="media mt-3">
                                                    <a class="pr-2" href="#">
                                                        <img @if(!empty($subfeedback->feedbackUser && !empty($subfeedback->feedbackUser->avatar))) src="{{$profile.'/'.$subfeedback->feedbackUser->avatar}}" @else  avatar="{{!empty($subfeedback->feedbackUser)?$subfeedback->feedbackUser->name:''}}" @endif class="rounded-circle" alt="" height="32">
                                                    </a>
                                                    <div class="media-body">
                                                        <h6 class="mt-0 ms-2">{{!empty($subfeedback->feedbackUser)?$subfeedback->feedbackUser->name:''}} <small class="text-muted float-right">{{$subfeedback->created_at}}</small></h6>
                                                        <p class="text-sm mb-0 ms-2">
                                                            {{$subfeedback->feedback}}
                                                        </p>

                                                        @if(!empty($subfeedback->file))
                                                            <div class="action-btn bg-warning ms-2">
                                                                <a href="{{asset(Storage::url('uploads/files')).'/'.$subfeedback->file}}" download="" data-bs-toggle="tooptip" title="{{ __('Download') }}" class="mx-3 btn btn-sm d-inline-flex align-items-center"><i class="ti ti-download text-white"></i></a>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach

                                <div class="border rounded mt-4">

                                    {{ Form::open(array('route' => array('project.client.feedback.store',$project->id),'enctype'=>"multipart/form-data")) }}
                                    <textarea rows="3" class="form-control border-0 resize-none" name="feedback" placeholder="Your feedback..." required></textarea>
                                    <div class="p-2 bg-light d-flex justify-content-between align-items-center">
                                        <div>
                                            {{ Form::file('file', null, array('class' => 'form-control','required'=>'required')) }}
                                        </div>
                                        <button type="submit" class="btn btn-primary"><i class='uil uil-message mr-1'></i>{{__('Post')}}</button>
                                    </div>
                                    {{ Form::close() }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--Invoice-->
                    <div class="useradd-10">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">{{ __('Invoice') }}</h5>
                            </div>
                        </div>
                        <div class="card-body">
                            @foreach($invoices as $invoice)
                                <div class="col-md-6">
                                    <div class="card hover-shadow-lg">
                                        <div class="card-header border-0">
                                            <div class="row align-items-center">
                                                <div class="col-10">
                                                    <h6 class="mb-0">
                                                        <a href="{{route('invoice.show',\Crypt::encrypt($invoice->id))}}">{{\Auth::user()->invoiceNumberFormat($invoice->invoice_id)}}</a>
                                                    </h6>
                                                </div>
                                                <div class="col-2 text-end">
                                                    <div class="actions">
                                                        <div class="dropdown">
                                                            <a href="#" class="action-item" data-toggle="dropdown"><i class="fas fa-ellipsis-v"></i></a>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                @if(\Auth::user()->type=='company' || \Auth::user()->type=='client')
                                                                    <a href="{{route('invoice.show',\Crypt::encrypt($invoice->id))}}" class="dropdown-item">
                                                                        {{__('View')}}
                                                                    </a>
                                                                @endif
        
                                                                @if(\Auth::user()->type=='company')
                                                                    <a href="#!" data-url="{{ route('invoice.edit',$invoice->id) }}" class="dropdown-item" data-toggle="tooltip" data-original-title="{{__('Edit')}}" data-ajax-popup="true" data-title="{{__('Edit Invoice')}}">
                                                                        {{__('Edit')}}
                                                                    </a>
        
                                                                    <a href="#!" class="dropdown-item" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('invoice-delete-form-{{$invoice->id}}').submit();">
                                                                        {{__('Delete')}}
                                                                    </a>
                                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['invoice.destroy', $invoice->id],'id'=>'invoice-delete-form-'.$invoice->id]) !!}
                                                                    {!! Form::close() !!}
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body pt-0">
                                            <div class="p-3 border border-dashed">
                                                @if($invoice->status == 0)
                                                    <span class="badge badge-primary">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                                @elseif($invoice->status == 1)
                                                    <span class="badge badge-warning">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                                @elseif($invoice->status == 2)
                                                    <span class="badge badge-danger">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                                @elseif($invoice->status == 3)
                                                    <span class="badge badge-info">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                                @elseif($invoice->status == 4)
                                                    <span class="badge badge-success">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                                @endif
                                                <div class="row align-items-center mt-3">
                                                    <div class="col-6">
                                                        <h6 class="mb-0">{{\Auth::user()->priceFormat($invoice->getTotal())}}</h6>
                                                        <span class="text-sm text-muted">{{__('Total Amount')}}</span>
                                                    </div>
                                                    <div class="col-6">
                                                        <h6 class="mb-0">{{\Auth::user()->priceFormat($invoice->getDue())}}</h6>
                                                        <span class="text-sm text-muted">{{__('Due Amount')}}</span>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center mt-3">
                                                    <div class="col-6">
                                                        <h6 class="mb-0">{{\Auth::user()->dateFormat($invoice->issue_date)}}</h6>
                                                        <span class="text-sm text-muted">{{__('Issue Date')}}</span>
                                                    </div>
                                                    <div class="col-6">
                                                        <h6 class="mb-0">{{\Auth::user()->dateFormat($invoice->due_date)}}</h6>
                                                        <span class="text-sm text-muted">{{__('Due Date')}}</span>
                                                    </div>
                                                </div>
        
                                            </div>
                                            @if(\Auth::user()->type != 'client')
                                                @php $client=$invoice->clients @endphp
                                                <div class="media mt-4 align-items-center">
                                                    <img @if(!empty($client->avatar)) src="{{$profile.'/'.$client->avatar}}" @else avatar="{{$invoice->clients->name}}" @endif class="avatar rounded-circle avatar-custom" data-toggle="tooltip" data-original-title="{{__('Client')}}">
                                                    <div class="media-body pl-3">
                                                        <div class="text-sm my-0">{{!empty($invoice->clients)?$invoice->clients->name:''}}</div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!--Timesheets-->
                    <div id="useradd-11">
                        <div class="card">
                            <div class="card-header">
                                <div class="float-end">
                                    @if(\Auth::user()->type=='company')
                                        <p class="text-muted d-none d-sm-flex align-items-center mb-0">
                                            <a href="#" class="btn btn-sm btn-primary btn-icon m-1" data-bs-toggle="modal" 
                                            data-bs-target="#exampleModal" data-url="{{ route('project.timesheet.create',$project->id) }}"
                                            data-bs-whatever="{{__('Create New Timesheet')}}"data-bs-toggle="tooltip" title="{{ __('Create') }}" 
                                            data-bs-original-title="{{__('Create New Timesheet')}}"> <span class="text-white"> 
                                                <i class="ti ti-plus text-white"></i></span>
                                            </a>
                                        </p>
                                    @endif
                                </div>
                                <h5 class="mb-0">{{ __('Timesheet') }}</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table" id="pc-dt-simple">
                                        <thead>
                                            <tr>
                                                <th scope="col">{{__('Member')}}</th>
                                                <th scope="col">{{__('Task')}}</th>
                                                <th scope="col">{{__('Start Date')}}</th>
                                                <th scope="col">{{__('Start Time')}}</th>
                                                <th scope="col">{{__('End Date')}}</th>
                                                <th scope="col">{{__('End Time')}}</th>
                                                <th scope="col">{{__('Notes')}}</th>
                                                <th scope="col" class="text-end">{{__('Action')}}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($timesheets as $timesheet)
                                                <tr>
                                                    <td>{{!empty($timesheet->users)?$timesheet->users->name:'-'}}</td>
                                                    <td> {{!empty($timesheet->tasks)?$timesheet->tasks->title:'-'}}</td>
                                                    <td>{{\Auth::user()->dateFormat($timesheet->start_date)}}</td>
                                                    <td>{{\Auth::user()->timeFormat($timesheet->start_time)}}</td>
                                                    <td>{{\Auth::user()->dateFormat($timesheet->end_date)}}</td>
                                                    <td>{{\Auth::user()->timeFormat($timesheet->end_time)}}</td>
                                                    <td>
                                                        <div class="action-btn bg-warning ms-2">
                                                            <a href="#" data-url="{{ route('project.timesheet.note',[$project->id,$timesheet->id]) }}" 
                                                            data-bs-toggle="modal"  data-bs-target="#exampleModal" data-bs-toggle="tooltip"
                                                            title="{{__('Timesheet Notes')}}" class="mx-3 btn btn-sm d-inline-flex align-items-center">
                                                            <i class="ti ti-brand-hipchat text-white"></i></a>
                                                        </div>
                                                    </td>
                                                    @if(\Auth::user()->type=='company')
                                                        <td class="table-actions text-end">
                                                            <div class="action-btn bg-info ms-2">
                                                                <a href="#" data-url="{{ route('project.timesheet.edit',[$project->id,$timesheet->id]) }}" 
                                                                    data-bs-toggle="modal"  data-bs-target="#exampleModal" title="{{__('Edit Timesheet')}}" 
                                                                    class="mx-3 btn btn-sm d-inline-flex align-items-center" data-bs-toggle="tooltip" data-bs-whatever="{{__('Edit Timesheet')}}">
                                                                    <i class="ti ti-edit text-white"></i>
                                                                </a>
                                                            </div>

                                                            <div class="action-btn bg-danger ms-2">
                                                                {!! Form::open(['method' => 'DELETE', 'route' => ['project.timesheet.destroy', $project->id,$timesheet->id],'id'=>'timesheet-delete-form-'.$timesheet->id]) !!}
                                                                    @csrf
                                                                    <input name="_method" type="hidden" value="DELETE">
                                                                    <button type="submit" class="mx-3 btn btn-sm d-inline-flex align-items-center show_confirm" data-toggle="tooltip"
                                                                    title='Delete'>
                                                                    <span class="text-white"> <i
                                                                        class="ti ti-trash"></i></span>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                            {{-- <a href="#!" class="action-item" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('timesheet-delete-form-{{$timesheet->id}}').submit();">
                                                                <i class="fas fa-trash"></i>
                                                            </a>
                                                            {!! Form::open(['method' => 'DELETE', 'route' => ['project.timesheet.destroy', $project->id,$timesheet->id],'id'=>'timesheet-delete-form-'.$timesheet->id]) !!}
                                                            {!! Form::close() !!} --}}
                                                        </td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--payments-->
                    <div id="useradd-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">{{ __('Payment') }}</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table" id="pc-dt-simple">
                                        <thead>
                                            <tr>
                                                <th>{{__('Transaction ID')}}</th>
                                                <th>{{__('Invoice ID')}}</th>
                                                <th>{{__('Payment Date')}}</th>
                                                <th>{{__('Payment Method')}}</th>
                                                <th>{{__('Payment Type')}}</th>
                                                <th>{{__('Notes')}}</th>
                                                <th>{{__('Amount')}}</th>
                                                <th>{{__('Action')}}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($invoices as $invoice)
                                                @foreach($invoice->payments as $payment)
                                                    <tr>
                                                        <td>{{\Auth::user()->invoiceNumberFormat($invoice->invoice_id)}} </td>
                                                        <td>{{$payment->transaction}} </td>
                                                        <td>{{\Auth::user()->dateFormat($payment->date)}} </td>
                                                        <td>{{!empty($payment->payments)?$payment->payments->name:''}} </td>
                                                        <td>{{$payment->payment_type}} </td>
                                                        <td>{{$payment->notes}} </td>
                                                        <td> {{\Auth::user()->priceFormat(($payment->amount))}}</td>
                                                        <td width="7%" class="text-end">
                                                            <div class="action-btn bg-warning ms-2">
                                                                <a href="{{route('invoice.show',\Crypt::encrypt($invoice->id))}}" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-bs-toggle="tooltip" title="{{__('View')}}">
                                                                    <i class="ti ti-eye"></i>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                     <!--Expense-->
                     <div id="useradd-13">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">{{ __('Expenses') }}</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table" id="pc-dt-simple">
                                        <thead>
                                            <tr>
                                                <th> {{__('Date')}}</th>
                                                <th> {{__('Amount')}}</th>
                                                <th> {{__('User')}}</th>
                                                <th> {{__('Attachment')}}</th>
                                                <th> {{__('Description')}}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($project->expenses as $expense)
                                                <tr class="font-style">
                                                    <td>{{  Auth::user()->dateFormat($expense->date)}}</td>
                                                    <td>{{  Auth::user()->priceFormat($expense->amount)}}</td>
                                                    <td>{{  (!empty($expense->users)?$expense->users->name:'')}}</td>
                                                    <td>
                                                        @if(!empty($expense->attachment))
                                                            <a href="{{asset(Storage::url('uploads/attachment/'. $expense->attachment))}}" target="_blank">{{  $expense->attachment}}</a>
                                                        @else
                                                            --
                                                        @endif
                                                    </td>
                                                    <td>{{  $expense->description}}</td>

                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>

@endsection

