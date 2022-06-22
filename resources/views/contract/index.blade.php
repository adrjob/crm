@extends('layouts.admin')
@push('script-page')
@endpush
@section('page-title')
    {{__('Contract')}}
@endsection
@section('title')
    <div class="d-inline-block">
        <h5 class="h4 d-inline-block font-weight-400 mb-0 ">{{__('Contract')}}</h5>
    </div>
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{__('Contract')}}</li>
@endsection

@section('action-btn')
    <a href="{{ route('contract.grid') }}" class="btn btn-sm btn-primary btn-icon m-1">
        <i class="ti ti-layout-grid text-white" data-bs-toggle="tooltip" data-bs-original-title="{{ __('Grid View') }}">  </i>
    </a>
    @if(\Auth::user()->type=='company')
        <a href="#" class="btn btn-sm btn-primary btn-icon m-1" data-bs-toggle="modal"
        data-bs-target="#exampleModal" data-url="{{ route('contract.create') }}" data-size="lg"
        data-bs-whatever="{{__('Create New Contract')}}"> <span class="text-white"> 
            <i class="ti ti-plus text-white" data-bs-toggle="tooltip" data-bs-original-title="{{ __('Create') }}"></i></span>
        </a>
    @endif
@endsection
@section('filter')
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
                                <th scope="col">{{__('Subject')}}</th>
                                @if(\Auth::user()->type!='client')
                                    <th scope="col">{{__('Client')}}</th>
                                @endif
                                <th scope="col">{{__('Contract Type')}}</th>
                                <th scope="col">{{__('Contract Value')}}</th>
                                <th scope="col">{{__('Start Date')}}</th>
                                <th scope="col">{{__('End Date')}}</th>
                                <th scope="col">{{__('Description')}}</th>
                                @if(\Auth::user()->type=='company')
                                    <th scope="col" class="text-right">{{__('Action')}}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($contracts as $contract)

                            <tr class="font-style">
                                <td>{{ $contract->subject}}</td>
                                @if(\Auth::user()->type!='client')
                                    <td>{{ !empty($contract->clients)?$contract->clients->name:'' }}</td>
                                @endif
                                <td>{{ !empty($contract->types)?$contract->types->name:'' }}</td>
                                <td>{{ \Auth::user()->priceFormat($contract->value) }}</td>
                                <td>{{  \Auth::user()->dateFormat($contract->start_date )}}</td>
                                <td>{{  \Auth::user()->dateFormat($contract->end_date )}}</td>
                                <td>
                                    <div class="action-btn bg-warning ms-2">
                                        <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-bs-toggle="modal"
                                        data-bs-target="#exampleModal" data-url="{{ route('contract.description',$contract->id) }}" 
                                         data-bs-whatever="{{__('Description')}}"><i class="fa fa-comment text-white" data-bs-toggle="tooltip" data-bs-original-title="{{ __('Description') }}"></i></a>
                                    </div>    
                                </td>
                                @if(\Auth::user()->type=='company')
                                    <td class="action text-right">
                                        <div class="action-btn bg-info ms-2">
                                            <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-bs-toggle="modal"
                                            data-bs-target="#exampleModal" data-size="lg" data-url="{{ route('contract.edit',$contract->id) }}"
                                            data-bs-whatever="{{__('Edit Contract')}}" > <span class="text-white"> <i
                                                    class="ti ti-edit" data-bs-toggle="tooltip" data-bs-original-title="{{ __('Edit') }}"></i></span></a>
                                        </div>

                                        <div class="action-btn bg-danger ms-2">

                                            {!! Form::open(['method' => 'DELETE', 'route' => ['contract.destroy', $contract->id]]) !!}
                                            <a href="#!" class="mx-3 btn btn-sm d-inline-flex align-items-center show_confirm">
                                                <i class="ti ti-trash text-white" data-bs-toggle="tooltip" data-bs-original-title="{{ __('Delete') }}"></i>
                                            </a>
                                            {!! Form::close() !!}

                                
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @empty
                        <div class="text-center">
                            <H3> {{ __('No Contract Found..') }}</H3>
                        </div>
                        @endforelse

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection

