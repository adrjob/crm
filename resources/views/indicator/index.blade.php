@extends('layouts.admin')
@push('css-page')
    <style>
        @import url({{ asset('css/font-awesome.css') }});
    </style>
@endpush
@push('script-page')
    <script src="{{ asset('js/bootstrap-toggle.js') }}"></script>
    <script>
        $('document').ready(function () {
            $('.toggleswitch').bootstrapToggle();
            $("fieldset[id^='demo'] .stars").click(function () {
                alert($(this).val());
                $(this).attr("checked");
            });
        });

        $(document).ready(function () {
            var d_id = $('#department_id').val();
            getDesignation(d_id);
        });

        $(document).on('change', 'select[name=department]', function () {
            var department_id = $(this).val();
            getDesignation(department_id);
        });

        function getDesignation(did) {
            $.ajax({
                url: '{{route('employee.json')}}',
                type: 'POST',
                data: {
                    "department_id": did, "_token": "{{ csrf_token() }}",
                },
                success: function (data) {
                    $('#designation_id').empty();
                    $('#designation_id').append('<option value="">{{__('Select Designation')}}</option>');
                    $.each(data, function (key, value) {
                        $('#designation_id').append('<option value="' + key + '">' + value + '</option>');
                    });
                }
            });
        }
    </script>
@endpush
@section('page-title')
    {{__('Indicator')}}
@endsection
@section('title')
    <div class="d-inline-block">
        <h5 class="h4 d-inline-block font-weight-400 mb-0">{{__('Indicator')}}</h5>
    </div>
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{__('HR')}}</li>
    <li class="breadcrumb-item active" aria-current="page">{{__('Performance')}}</li>
    <li class="breadcrumb-item active" aria-current="page">{{__('Indicator')}}</li>
@endsection
@section('action-btn')
    @if(\Auth::user()->type=='company')
    <a href="#" class="btn btn-sm btn-primary btn-icon m-1" data-bs-toggle="modal"
    data-bs-target="#exampleModal" data-url="{{ route('indicator.create') }}"
    data-bs-whatever="{{__('Create New Indicator')}}">
        <i class="ti ti-plus text-white" data-bs-toggle="tooltip" data-bs-original-title="{{ __('Create') }}"></i>
    </a>
    @endif

@endsection

@section('content')
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header card-body table-border-style">
                <!-- <h5></h5> -->
                <div class="table-responsive">
                    <table class="table" id="pc-dt-simple">
                        <thead>
                            <tr>
                                <th>{{__('Department')}}</th>
                                <th>{{__('Designation')}}</th>
                                <th>{{__('Added By')}}</th>
                                <th>{{__('Overall Rating')}}</th>
                                <th>{{__('Created At')}}</th>
                                @if(\Auth::user()->type=='company')
                                    <th class="text-right">{{__('Action')}}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($indicators as $indicator)
                                @php
                                    if(!empty($indicator->rating)){
                                    $rating = json_decode($indicator->rating,true);
                                    $starsum = array_sum($rating);
                                    $overallrating = $starsum/count($rating);
                                    }
                                    else{
                                        $overallrating = 0;
                                    }
                                @endphp
                                <tr>
                                    <td>{{ !empty($indicator->departments)?$indicator->departments->name:'' }}</td>
                                    <td>{{ !empty($indicator->designations)?$indicator->designations->name:'' }}</td>
                                    <td>{{ !empty($indicator->user)?$indicator->user->name:'' }}</td>
                                    <td>

                                        @for($i=1; $i<=5; $i++)
                                            @if($overallrating < $i)
                                                @if(is_float($overallrating) && (round($overallrating) == $i))
                                                    <i class="text-warning fas fa-star-half-alt"></i>
                                                @else
                                                    <i class="fas fa-star"></i>
                                                @endif
                                            @else
                                                <i class="text-warning fas fa-star"></i>
                                            @endif
                                        @endfor
                                        <span class="theme-text-color">({{number_format($overallrating,1)}})</span>
                                    </td>


                                    <td>{{ \Auth::user()->dateFormat($indicator->created_at) }}</td>
                                    @if(\Auth::user()->type=='company')
                                        <td class="text-right">
                                            <div class="action-btn bg-warning ms-2">
                                                <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-bs-toggle="modal"
                                                data-bs-target="#exampleModal" data-url="{{ route('indicator.show',$indicator->id) }}"
                                                data-bs-whatever="{{__('View Indicator')}}" > <span class="text-white"> <i
                                                        class="ti ti-eye" data-bs-toggle="tooltip" data-bs-original-title="{{ __('View') }}"
                                                        ></i></span></a>
                                            </div>

                                            <div class="action-btn bg-info ms-2">
                                                <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-bs-toggle="modal"
                                                data-bs-target="#exampleModal" data-url="{{ route('indicator.edit',$indicator->id) }}"
                                                data-bs-whatever="{{__('Edit Indicator')}}"> <span class="text-white"> <i
                                                        class="ti ti-edit" data-bs-toggle="tooltip" data-bs-original-title="{{ __('Edit') }}"></i></span></a>
                                            </div>

                                            <div class="action-btn bg-danger ms-2">
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['indicator.destroy', $indicator->id]]) !!}
                                                <a href="#!" class="mx-3 btn btn-sm d-flex align-items-center show_confirm">
                                                    <i class="ti ti-trash text-white" data-bs-toggle="tooltip" data-bs-original-title="{{ __('Delete') }}"></i>
                                                </a>
                                                {!! Form::close() !!}
                                            </div>
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

@endsection

