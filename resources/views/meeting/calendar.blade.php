@extends('layouts.admin')

@push('pre-purpose-script-page')
<script src="{{ asset('assets/js/plugins/main.min.js') }}"></script>
 

    <script type="text/javascript">

        (function () {
            var etitle;
            var etype;
            var etypeclass;
            var calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                themeSystem: 'bootstrap',
                initialDate: '{{ $transdate }}',
                slotDuration: '00:10:00',
                navLinks: true,
                droppable: true,
                selectable: true,
                selectMirror: true,
                editable: true,
                dayMaxEvents: true,
                handleWindowResize: true,
                events:{!! $arrMeeting !!},
            });
            calendar.render();
        })();
    </script>


@endpush
@section('page-title')
    {{__('Meeting')}}
@endsection
@section('title')
    <div class="d-inline-block">
        <h5 class="h4 d-inline-block font-weight-400 mb-0">{{__('Meeting')}}</h5>
    </div>
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{__('HR')}}</li>
    <li class="breadcrumb-item active" aria-current="page">{{__('Meeting')}}</li>
@endsection
@section('action-btn')
    <a href="{{route('meeting.index')}}" class="btn btn-sm btn-primary btn-icon m-1" data-bs-toggle="tooltip" data-bs-placement="top" title="List View"> <span class="text-white"> 
        <i class="ti ti-list text-white"></i></span>
    </a>

    @if(\Auth::user()->type=='company' || \Auth::user()->type=='employee')
        <a href="#" class="btn btn-sm btn-primary btn-icon m-1" data-bs-toggle="modal"
        data-bs-target="#exampleModal" data-url="{{ route('meeting.create') }}"
        data-bs-whatever="{{__('Create New Meeting')}}"
        > <span class="text-white"> 
            <i class="ti ti-plus text-white" data-bs-toggle="tooltip" data-bs-original-title="{{__('Create')}}"></i></span>
        </a>


    @endif

@endsection
@section('content')
<div class="row">
    <!-- [ sample-page ] start -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5>{{ __('Calendar') }}</h5>
            </div>
            <div class="card-body">
                <div id='calendar' class='calendar'></div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        
        <div class="card">
            <div class="card-body">
                <h4 class="mb-4">{{ __('Current Month Meeting') }}</h4>
                <ul class="event-cards list-group list-group-flush mt-3 w-100">
                   
                    @foreach($meeting_current_month as $meeting)
                        <li class="list-group-item card mb-3">
                            <div class="row align-items-center justify-content-between">
                                <div class="col-auto mb-3 mb-sm-0">
                                    <div class="d-flex align-items-center">
                                        <div class="theme-avtar bg-info">
                                            <i class="ti ti-arrow-ramp-right"></i>
                                        </div>
                                        <div class="ms-3">
                                            <h6 class="m-0">{{ $meeting->title }}</h6>
                                            <small class="text-muted">{{ $meeting->date }}</small>
                                        </div>
                                    </div>
                                    
                                </div>
                                
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    <!-- [ sample-page ] end -->
</div>

@endsection

