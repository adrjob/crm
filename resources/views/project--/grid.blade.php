@extends('layouts.admin')
@php
    $profile=asset(Storage::url('uploads/avatar'));
@endphp
@push('script-page')
@endpush
@section('page-title')
    {{__('Project')}}
@endsection
@section('title')
    <div class="d-inline-block">
        <h5 class="h4 d-inline-block font-weight-400 mb-0">{{__('Project')}}</h5>
    </div>
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{__('Project')}}</li>
    <li class="breadcrumb-item active" aria-current="page">{{__('All Project')}}</li>
@endsection
@section('action-btn')
    <a href="{{ route('project.index') }}" class="btn btn-sm bg-white btn-icon rounded-pill">
        <span class="btn-inner--text text-dark">{{__('List View')}}</span>
    </a>
    @if(\Auth::user()->type=='company')
    <a href="{{ route('project.create') }}" class="btn btn-sm btn-primary btn-icon m-1"
    data-bs-whatever="{{__('Create New Project')}}" data-bs-toggle="tooltip"
    data-bs-original-title="{{__('Create New Project')}}"> <i class="ti ti-plus text-white"></i></a>
    @endif

@endsection
@section('filter')

@endsection
@section('content')
    <div class="row">
        @forelse ($projects as $project)
            @php
                $percentages=0;
                    $total=count($project->tasks);

                    if($total!=0){
                        $percentages= $project->completedTask() / ($total /100);
                    }
            @endphp
            <div class="col-xl-3 col-lg-4 col-sm-6">
                <div class="card hover-shadow-lg">
                    <div class="card-header border-0 pb-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0" data-toggle="tooltip" data-original-title="{{__('Start Date ')}}">{{\Auth::user()->dateFormat($project->start_date)}}</h6>
                            </div>
                            <div class="text-right">
                                <div class="actions">
                                    <h6 class="mb-0" data-toggle="tooltip" data-original-title="{{__('Due Date ')}}">{{\Auth::user()->dateFormat($project->due_date)}}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body text-center">
                        <a href="#" class="avatar rounded-circle avatar-lg hover-translate-y-n3">
                            <div class="progress-circle progress-sm" id="progress-circle-1" data-progress="{{$percentages}}" data-text="{{$percentages}}%" data-color="info"></div>
                        </a>
                        <h5 class="h6 my-4">
                            <a href="{{route('project.show',\Crypt::encrypt($project->id))}}">{{$project->title}}</a>
                        </h5>
                        <div class="avatar-group hover-avatar-ungroup mb-3">
                            @foreach($project->projectUser() as $projectUser)

                                <a href="#" class="avatar rounded-circle avatar-sm">
                                    <img alt="" @if(!empty($users->avatar)) src="{{$profile.'/'.$projectUser->avatar}}" @else  avatar="{{(!empty($projectUser)?$projectUser->name:'')}}" @endif data-original-title="{{(!empty($projectUser)?$projectUser->name:'')}}" data-toggle="tooltip" data-original-title="{{(!empty($projectUser)?$projectUser->name:'')}}" class="">
                                </a>

                            @endforeach
                        </div>
                        <span class="clearfix"></span>
                        @if($project->status=='not_started')
                            <span class="badge badge-pill badge-primary">{{__('Not Started')}}</span>
                        @elseif($project->status=='in_progress')
                            <span class="badge badge-pill badge-success">{{__('In Progress')}}</span>
                        @elseif($project->status=='on_hold')
                            <span class="badge badge-pill badge-info">{{__('On Hold')}}</span>
                        @elseif($project->status=='canceled')
                            <span class="badge badge-pill badge-danger">{{__('Canceled')}}</span>
                        @elseif($project->status=='finished')
                            <span class="badge badge-pill badge-warning">{{__('Finished')}}</span>
                        @endif

                    </div>
                    @if(\Auth::user()->type=='company')
                        <div class="card-footer">
                            <div class="actions d-flex justify-content-between px-4">
                                <a href="{{ route('project.edit',\Crypt::encrypt($project->id)) }}" class="action-item" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
                                    <i class="far fa-edit"></i>
                                </a>

                                <a href="{{route('project.show',\Crypt::encrypt($project->id))}}" class="action-item" data-toggle="tooltip" data-original-title="{{__('View')}}">
                                    <i class="fas fa-eye"></i>
                                </a>

                                <a href="#" class="action-item" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('delete-form-{{$project->id}}').submit();" data-toggle="tooltip" data-original-title="{{__('Delete')}}">
                                    <i class="fas fa-trash-alt"></i>
                                </a>

                            </div>

                            {!! Form::open(['method' => 'DELETE', 'route' => ['project.destroy', $project->id],'id'=>'delete-form-'.$project->id]) !!}
                            {!! Form::close() !!}
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-md-12 text-center">
                <h4>{{__('No data available')}}</h4>
            </div>
        @endforelse
    </div>
@endsection

