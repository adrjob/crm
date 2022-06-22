@extends('layouts.admin')
@php
$profile=asset(Storage::url('uploads/avatar'));
@endphp
@push('script-page')
@endpush
@section('page-title')
{{__('Employee')}}
@endsection
@section('title')
<div class="d-inline-block">
    <h5 class="h4 d-inline-block font-weight-400 mb-0">{{__('Employee')}}</h5>
</div>
@endsection
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
<li class="breadcrumb-item active" aria-current="page">{{__('Staff')}}</li>
<li class="breadcrumb-item active" aria-current="page">{{__('Employee')}}</li>
@endsection
@section('action-btn')
        <a href="#" class="btn btn-sm btn-primary btn-icon m-1" data-bs-toggle="modal"
            data-bs-target="#exampleModal" data-url="{{ route('employee.file.import') }}"
            data-bs-whatever="{{__('Import CSV file')}}"> <span class="text-white">
                <i class="ti ti-file-import"  data-bs-toggle="tooltip"
                data-bs-original-title="{{__('Import item CSV file')}}"></i>
        </a>

       <a href="#" class="btn btn-sm btn-primary btn-icon m-1" data-bs-toggle="modal"
            data-bs-target="#exampleModal" data-url="{{ route('employee.create') }}"
            data-bs-whatever="{{__('Create New Employee')}}" >
                <i class="ti ti-plus text-white" data-bs-toggle="tooltip"
                data-bs-original-title="{{__('Create')}}"></i>
        </a>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="collapse {{isset($_GET['department'])?'show':''}}" id="collapseExample">
            <div class="card card-body">
                {{ Form::open(array('url' => 'employee','method'=>'get')) }}
                <div class="row filter-css">
                    <div class="col-md-2">
                        {{ Form::select('department', $department,isset($_GET['department'])?$_GET['department']:'',
                        array('class' => 'form-control','data-toggle'=>'select')) }}
                    </div>
                    <div class="col-md-2">
                        {{ Form::select('designation', $designation,isset($_GET['designation'])?$_GET['designation']:'',
                        array('class' => 'form-control','data-toggle'=>'select')) }}
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-xs btn-primary btn-icon-only rounded-circle"
                            data-toggle="tooltip" data-title="{{__('Apply')}}"><i class="ti ti-search"></i></button>
                    </div>
                    <div class="col-auto">
                        <a href="{{route('employee.index')}}" data-toggle="tooltip" data-title="{{__('Reset')}}"
                            class="btn btn-xs btn-danger btn-icon-only rounded-circle"><i
                                class="ti ti-trash"></i></a>
                    </div>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>
<div class="row">
    @foreach($employees as $employee)
        <div class="col-lg-3 col-sm-6">
            <div class="card hover-shadow-lg">
                <div class="card-header border-0 pb-0 pt-2 px-3">
                    <div class="row">
                        <div class="col-6 text-right">
                            <span class="badge bg-primary p-2 px-3 rounded">
                                {{\Auth::user()->employeeIdFormat(!empty($employee->employeeDetail)?$employee->employeeDetail->employee_id:'')}}
                            </span>
                        </div>
                        <div class="col-6  text-end">
                            <div class="actions">
                                @if($employee->is_active==1)
                                    <div class="dropdown action-item">
                                        <a href="#" class="action-item " data-bs-toggle="dropdown">
                                            <i class="ti ti-dots-vertical"></i></a>
                                        <div class="dropdown-menu dropdown-menu-right">

                                            <a href="{{route('employee.edit',\Crypt::encrypt($employee->id))}}" class="dropdown-item"
                                            data-bs-whatever="{{__('Edit Employee')}}">
                                            <i class="ti ti-edit">  </i> {{ __('Edit') }}</a>


                                            <a href="{{route('employee.show',\Crypt::encrypt($employee->id))}}" class="dropdown-item"
                                                data-bs-whatever="{{__('View Employee')}}">
                                                <i class="ti ti-eye"></i> {{ __('View') }}</a>


                                            <a href="#" data-url="{{route('employee.reset',\Crypt::encrypt($employee->id))}}" data-bs-toggle="modal"
                                                data-bs-target="#exampleModal"
                                                class="dropdown-item"  data-bs-whatever="{{__('Reset Password')}}">
                                               <i class="ti ti-lock"> </i> {{__('Reset Password')}}
                                            </a>
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['employee.destroy', $employee->id]]) !!}
                                                <a href="#!" class=" show_confirm dropdown-item">
                                                    <i class="ti ti-trash"></i>{{ __('Delete') }}
                                                </a>
                                                {!! Form::close() !!}
                                                
                                        </div>
                                    </div>
                                    @else
                                        <div class="actions d-flex justify-content-between px-4">
                                            <a href="#" data-toggle="tooltip" data-original-title="{{__('Lock')}}" class="mx-3 btn btn-sm d-inline-flex align-items-center">
                                                <i class="fas fa-lock"></i>
                                            </a>
                                        </div>
                                @endif
                            </div>
                        </div>
                    </div>


                </div>
                <div class="card-body text-center client-box">
                    <div class="avatar-parent-child">
                        <img @if(!empty($employee->avatar)) src="{{$profile.'/'.$employee->avatar}}" @else
                        avatar="{{$employee->name}}" @endif class="avatar rounded-circle avatar-lg">
                    </div>
                    <h5 class="h6 mt-4 mb-0">{{$employee->name}}</h5>
                    <a href="#" class="text-sm text-muted mb-3">{{$employee->email}}</a>
                </div>

                <div class="card-footer">
                    <div class="row justify-content-between align-items-center">
                        <div class="col text-center">
                            <span class="d-block h6 mb-0">{{!empty($employee->employeeDetail)?
                                !empty($employee->employeeDetail->departments)?$employee->employeeDetail->departments->name:'-':'-'}}</span>
                            <span class="d-block text-sm text-muted">{{__('Department')}}</span>
                        </div>
                        <div class="col text-center">
                            <span class="d-block h6 mb-0">{{!empty($employee->employeeDetail)?
                                !empty($employee->employeeDetail->designations)?$employee->employeeDetail->designations->name:'-':'-'}}</span>
                            <span class="d-block text-sm text-muted">{{__('Designation')}}</span>
                        </div>
                    </div>

                    <div class="row justify-content-between align-items-center mt-3">
                        <div class="col text-center">
                            <span class="d-block h6 mb-0">{{!empty($employee->employeeDetail) && !empty($employee->employeeDetail->dob)?
                                \Auth::user()->dateFormat($employee->employeeDetail->joining_date):'-'}}</span>
                            <span class="d-block text-sm text-muted">{{__('Date of Joining')}}</span>
                        </div>
                        <div class="col text-center">
                            <span class="d-block h6 mb-0">{{!empty($employee->employeeDetail)?
                                \Auth::user()->priceFormat($employee->employeeDetail->salary):'-'}}</span>
                            <span class="d-block text-sm text-muted">{{__('Salary')}}</span>
                        </div>
                    </div>

                    @if($employee->lastlogin)
                    <div class="row justify-content-between align-items-center mt-3">
                        <div class="col text-center">
                            <span class="d-block h6 mb-0" data-bs-toggle="tooltip" data-bs-original-title="{{__('Last Login')}}">{{$employee->lastlogin}}</span>

                        </div>
                    </div>

                    @else
                    @endif

                </div>
            </div>
        </div>
    @endforeach
    <div class="col-xl-3 col-lg-4 col-sm-6"> 
        <a href="#" class="btn-addnew-project "  data-bs-toggle="modal"
        data-bs-target="#exampleModal" data-url="{{ route('employee.create')}}" data-size="lg" data-bs-whatever="{{__('Create New Employee')}}">
            <div class="bg-primary proj-add-icon">
                <i class="ti ti-plus"></i>
            </div>
            <h6 class="mt-4 mb-2">{{ __('New Employee') }}</h6>
            <p class="text-muted text-center">{{ __('Click here to add new employee') }}</p>
        </a>
    </div>

</div>


@endsection
