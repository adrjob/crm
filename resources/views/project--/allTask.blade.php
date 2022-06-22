@extends('layouts.admin')
@php
$profile = asset(Storage::url('uploads/avatar'));
@endphp
@push('script-page')
    <script>
        $(document).on("click", ".status", function() {
            var status = $(this).attr('data-id');
            var url = $(this).attr('data-url');

            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    status: status,
                    "_token": $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    $('#change-project-status').submit();
                    location.reload();
                }
            });
        });
    </script>
    <script>
        $(document).on('click', '.form-checklist', function(e) {
            e.preventDefault();
            $.ajax({
                url: $("#form-checklist").data('action'),
                type: 'POST',
                data: $('#form-checklist').serialize(),
                dataType: 'JSON',
                success: function(data) {
                    toastrs('Success', '{{ __('Checklist successfully created.') }}', 'success');

                    var html = '<div class="card border draggable-item shadow-none">\n' +
                        '                                    <div class="px-3 py-2 row align-items-center">\n' +
                        '                                        <div class="col-10">\n' +
                        '                                            <div class="custom-control custom-checkbox">\n' +
                        '                                                <input type="checkbox" id="checklist-' +
                        data.id + '" class="custom-control-input taskCheck"  value="' + data.id +
                        '" data-url="' + data.updateUrl + '">\n' +
                        '                                                <label class="custom-control-label h6 text-sm" for="checklist-' +
                        data.id + '">' + data.name + '</label>\n' +
                        '                                            </div>\n' +
                        '                                        </div>\n' +
                        '                                        <div class="col-auto card-meta d-inline-flex align-items-center ml-sm-auto">\n' +
                        '                                            <a href="#" class="action-item delete-checklist" data-url="' +
                        data.deleteUrl + '">\n' +
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
        $(document).on("click", ".delete-checklist", function() {
            if (confirm('Are You Sure ?')) {
                var checklist = $(this).parent().parent().parent();

                $.ajax({
                    url: $(this).attr('data-url'),
                    type: 'DELETE',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: 'JSON',
                    success: function(data) {
                        toastrs('Success', '{{ __('Checklist successfully deleted.') }}', 'success');
                        checklist.remove();
                    },
                    error: function(data) {
                        data = data.responseJSON;
                        if (data.message) {
                            toastrs('Error', data.message, 'error');
                        } else {
                            toastrs('Error', '{{ __('Some Thing Is Wrong!') }}', 'error');
                        }
                    }
                });
            }
        });
        var checked = 0;
        var count = 0;
        var percentage = 0;
        $(document).on("change", "#check-list input[type=checkbox]", function() {
            $.ajax({
                url: $(this).attr('data-url'),
                type: 'post',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    toastrs('Success', '{{ __('Checklist successfully updated.') }}', 'success');
                },
                error: function(data) {
                    data = data.responseJSON;
                    toastrs('Error', '{{ __('Something is wrong.') }}', 'error');
                }
            });
            taskCheckbox();
        });


        $(document).on('click', '#form-comment', function(e) {
            var comment = $.trim($("#form-comment input[name='comment']").val());
            var name = '{{ \Auth::user()->name }}';
            if (comment != '') {
                $.ajax({
                    url: $("#form-comment").data('action'),
                    data: {
                        comment: comment,
                        "_token": $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'POST',
                    success: function(data) {
                        data = JSON.parse(data);
                        console.log(data);
                        var html = '<div class="list-group-item">\n' +
                            '                            <div class="row">\n' +
                            '                                <div class="col ml-n2">\n' +
                            '                                    <a href="#!" class="d-block h6 mb-0">' +
                            name + '</a>\n' +
                            '                                    <div>\n' +
                            '                                        <small>' + data.comment +
                            '</small>\n' +
                            '                                    </div>\n' +
                            '                                </div>\n' +
                            '                                <div class="col-auto">\n' +
                            '                                    <a href="#" class="action-item  delete-comment" data-url="' +
                            data.deleteUrl + '">\n' +
                            '                                        <i class="ti ti-trash"></i>\n' +
                            '                                    </a>\n' +
                            '                                </div>\n' +
                            '                            </div>\n' +
                            '                        </div>';


                        $("#comments").prepend(html);
                        $("#form-comment textarea[name='comment']").val('');
                        toastrs('Success', '{{ __('Comment successfully created.') }}', 'success');
                    },
                    error: function(data) {
                        toastrs('Error', '{{ __('Some thing is wrong.') }}', 'error');
                    }
                });
            } else {
                toastrs('Error', '{{ __('Please write comment.') }}', 'error');
            }
        });
        $(document).on("click", ".delete-comment", function() {
            if (confirm('Are You Sure ?')) {
                var comment = $(this).parent().parent().parent();


                $.ajax({
                    url: $(this).attr('data-url'),
                    type: 'DELETE',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: 'JSON',
                    success: function(data) {
                        toastrs('Success', '{{ __('Comment Deleted Successfully!') }}', 'success');
                        comment.remove();
                    },
                    error: function(data) {
                        data = data.responseJSON;
                        if (data.message) {
                            toastrs('Error', data.message, 'error');
                        } else {
                            toastrs('Error', '{{ __('Some Thing Is Wrong!') }}', 'error');
                        }
                    }
                });
            }
        });

        $(document).on('submit', '#form-file', function(e) {
            e.preventDefault();
            $.ajax({
                url: $("#form-file").data('url'),
                type: 'POST',
                data: new FormData(this),
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    toastrs('Success', '{{ __('Comment successfully created.') }}', 'success');

                    var html = '<div class="card mb-3 border shadow-none">\n' +
                        '                            <div class="px-3 py-3">\n' +
                        '                                <div class="row align-items-center">\n' +
                        '                                    <div class="col ml-n2">\n' +
                        '                                        <h6 class="text-sm mb-0">\n' +
                        '                                            <a href="#!">' + data.name +
                        '</a>\n' +
                        '                                        </h6>\n' +
                        '                                        <p class="card-text small text-muted">\n' +
                        '                                            ' + data.file_size + '\n' +
                        '                                        </p>\n' +
                        '                                    </div>\n' +
                        '                                    <div class="col-auto actions">\n' +
                        '                                        <a download href="{{ asset(Storage::url('tasks')) }}' +
                        data.file + '" class="action-item">\n' +
                        '                                            <i class="ti ti-trash"></i>\n' +
                        '                                        </a>\n' +
                        '                                        <a href="#" class="action-item delete-comment-file" data-url="' +
                        data.deleteUrl + '">\n' +
                        '                                            <i class="ti ti-trash"></i>\n' +
                        '                                        </a>\n' +
                        '\n' +
                        '                                    </div>\n' +
                        '                                </div>\n' +
                        '                            </div>\n' +
                        '                        </div>';
                    $("#comments-file").prepend(html);
                },
                error: function(data) {
                    data = data.responseJSON;
                    if (data.message) {
                        toastrs('Error', data.message, 'error');
                        $('#file-error').text(data.errors.file[0]).show();
                    } else {
                        toastrs('Error', '{{ __('Some Thing Is Wrong!') }}', 'error');
                    }
                }
            });
        });
        $(document).on("click", ".delete-comment-file", function() {

            if (confirm('Are You Sure ?')) {
                var div = $(this).parent().parent().parent().parent();


                $.ajax({
                    url: $(this).attr('data-url'),
                    type: 'DELETE',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: 'JSON',
                    success: function(data) {
                        toastrs('Success', '{{ __('File successfully deleted.') }}', 'success');
                        div.remove();
                    },
                    error: function(data) {
                        data = data.responseJSON;
                        if (data.message) {
                            toastrs('Error', data.message, 'error');
                        } else {
                            toastrs('Error', '{{ __('Some thing is wrong.') }}', 'error');
                        }
                    }
                });
            }
        });

        $(document).on('change', '#project', function() {
            var project_id = $(this).val();

            $.ajax({
                url: '{{ route('project.getMilestone') }}',
                type: 'POST',
                data: {
                    "project_id": project_id,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(data) {
                    $('#milestone_id').empty();
                    $('#milestone_id').append('<option value="0"> -- </option>');
                    $.each(data, function(key, value) {
                        $('#milestone_id').append('<option value="' + key + '">' + value +
                            '</option>');
                    });
                }
            });

            $.ajax({
                url: '{{ route('project.getUser') }}',
                type: 'POST',
                data: {
                    "project_id": project_id,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(data) {
                    $('#assign_to').empty();
                    $.each(data, function(key, value) {
                        $('#assign_to').append('<option value="' + key + '">' + value +
                            '</option>');
                    });

                }
            });

        });
    </script>
@endpush
@section('page-title')
    {{ __('Task') }}
@endsection
@section('title')
    <div class="d-inline-block">
        <h5 class="h4 d-inline-block font-weight-400 mb-0">{{ __('Task') }}</h5>
    </div>
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ __('Project') }}</li>
    <li class="breadcrumb-item active" aria-current="page">{{ __('Task') }}</li>
@endsection
@section('action-btn')
    <a href="{{ route('project.all.task.gantt.chart') }}" class="btn btn-sm btn-primary btn-icon m-1" data-bs-toggle="tooltip" title="{{ __('Gantt Chart') }}">
        <i class="ti ti-chart-bar text-white"></i>
        {{-- <span class="btn-inner--text text-dark">{{ __('Gantt Chart') }}</span> --}}
    </a>

    <a href="{{ route('project.all.task.kanban') }}" class="btn btn-sm btn-primary btn-icon m-1" data-bs-toggle="tooltip" title="{{ __('Task Kanban') }}">
        <i class="ti ti-layout-kanban text-white"></i>
    </a>
    <a class="btn btn-sm btn-primary btn-icon m-1" data-bs-toggle="collapse" href="#collapseExample" role="button"
        aria-expanded="false" aria-controls="collapseExample">
        <i data-bs-toggle="tooltip" title="{{ __('Filter') }}" class="ti ti-filter text-white"></i>
    </a>
    <a href="#" data-size="lg" data-url="{{ route('project.task.create', 0) }}" data-bs-toggle="modal" data-bs-whatever="{{__('Create New Task')}}"
    data-bs-target="#exampleModal" title="{{ __('Create New Task') }}" class="btn btn-sm btn-primary btn-icon m-1">
        <i class="ti ti-plus text-white"></i>
    </a>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="collapse {{ isset($_GET['status']) ? 'show' : '' }}" id="collapseExample">
                <div class="card card-body">
                    {{ Form::open(['route' => ['project.all.task'], 'method' => 'get']) }}
                    <div class="row filter-css">
                        @if (\Auth::user()->type == 'company')
                            <div class="col-md-3">
                                {{ Form::select('project', $projectList, !empty($_GET['project']) ? $_GET['project'] : '', ['class' => 'form-control','data-toggle' => 'select']) }}
                            </div>
                        @endif
                        <div class="col-md-2">
                            <select class="form-control" data-toggle="select" name="status">
                                <option value="">{{ __('All') }}</option>
                                @foreach ($stageList as $k => $val)
                                    <option value="{{ $k }}"
                                        {{ isset($_GET['status']) && $_GET['status'] == $k ? 'selected' : '' }}>
                                        {{ $val }} </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-control" data-toggle="select" name="priority">
                                <option value="">{{ __('All') }}</option>
                                @foreach ($priority as $val)
                                    <option value="{{ $val }}"
                                        {{ isset($_GET['priority']) && $_GET['priority'] == $val ? 'selected' : '' }}>
                                        {{ $val }} </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-auto">
                            {{ Form::date('due_date', isset($_GET['due_date']) ? $_GET['due_date'] : '', ['class' => 'form-control']) }}
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-xs btn-primary btn-icon-only rounded-circle"
                                data-toggle="tooltip" data-title="{{ __('Apply') }}"><i
                                    class="ti ti-search"></i></button>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('project.all.task') }}" data-toggle="tooltip"
                                data-title="{{ __('Reset') }}"
                                class="btn btn-xs btn-danger btn-icon-only rounded-circle"><i
                                    class="ti ti-trash"></i></a>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-12">
        <div class="card">
            <div class="card-header card-body table-border-style">
                <h5></h5>
                <div class="table-responsive">
                    <table class="table" id="pc-dt-simple">
                        <thead>
                            <tr>
                                <th scope="col" class="sort">{{ __('Project') }}</th>
                                <th scope="col" class="sort">{{ __('Title') }}</th>
                                <th scope="col" class="sort">{{ __('Start date') }}</th>
                                <th scope="col" class="sort">{{ __('Due date') }}</th>
                                <th scope="col" class="sort">{{ __('Assigned to') }}</th>
                                <th scope="col" class="sort">{{ __('Priority') }}</th>
                                <th scope="col" class="sort">{{ __('Status') }}</th>
                                <th scope="col" class="sort text-end">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($projects as $project)
                                @php
                                    if (empty($_GET['status']) && empty($_GET['priority']) && empty($_GET['due_date'])) {
                                        $tasks = $project->tasks;
                                    } else {
                                        $tasks = $project->taskFilter($_GET['status'], $_GET['priority'], $_GET['due_date']);
                                    }
                                    
                                @endphp

                                @foreach ($tasks as $task)
                                    <tr>
                                        <td> {{ $project->title }}</td>
                                        <td>{{ $task->title }}</td>
                                        <td> {{ \Auth::user()->dateFormat($task->start_date) }}</td>
                                        <td> {{ \Auth::user()->dateFormat($task->due_date) }}</td>
                                        <td> {{ !empty($task->taskUser) ? $task->taskUser->name : '-' }}</td>
                                        <td>
                                            @if ($task->priority == 'low')
                                                <div class="badge bg-success p-2 px-3 rounded"> {{ $task->priority }}</div>
                                            @elseif($task->priority == 'medium')
                                                <div class="badge bg-warning p-2 px-3 rounded"> {{ $task->priority }}</div>
                                            @elseif($task->priority == 'high')
                                                <div class="badge bg-danger p-2 px-3 rounded"> {{ $task->priority }}</div>
                                            @endif
                                        </td>
                                        <td> {{ !empty($task->stages) ? $task->stages->name : '-' }}</td>
                                        <td class="text-end">
                                            @if (\Auth::user()->type == 'company' || \Auth::user()->type == 'employee')
                                            <div class="action-btn bg-warning ms-2">
                                                <a href="#" data-size="lg" data-url="{{ route('project.task.show', $task->id) }}"
                                                    data-bs-toggle="modal" data-bs-target="#exampleModal" title="{{ __('Task Detail') }}"
                                                    class="mx-3 btn btn-sm d-inline-flex align-items-center" data-bs-toggle="tooltip"
                                                    data-bs-whatever="{{__('View Task')}}">
                                                    <i class="ti ti-eye text-white"></i>
                                                </a>
                                            </div>
                                            @endif
                                            @if (\Auth::user()->type == 'company')
                                            <div class="action-btn bg-info ms-2">
                                                <a href="#" data-size="lg" data-url="{{ route('project.task.edit', $task->id) }}"
                                                    data-bs-toggle="modal" data-bs-target="#exampleModal" title="{{ __('Edit Task') }}"
                                                    class="mx-3 btn btn-sm d-inline-flex align-items-center" data-toggle="tooltip" data-bs-whatever="{{__('Edit Task')}}"
                                                    data-original-title="{{ __('Edit') }}">
                                                    <i class="ti ti-edit text-white"></i>
                                                </a>
                                            </div>
                                            <div class="action-btn bg-danger ms-2">
                                                <form method="POST" action="{{ route('project.task.destroy', $task->id) }}">
                                                    @csrf
                                                    <input name="_method" type="hidden" value="DELETE">
                                                    <button type="submit" class="mx-3 btn btn-sm d-inline-flex align-items-center show_confirm" data-toggle="tooltip"
                                                    title='Delete'>
                                                    <span class="text-white"> <i
                                                        class="ti ti-trash"></i></span>
                                                    </button>
                                                </form>
                                            </div>
                                            @endif
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
@endsection
