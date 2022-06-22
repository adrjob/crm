@extends('layouts.admin')
@section('page-title')
    {{__('Manage Journal Entry')}}
@endsection
@section('title')
    <div class="d-inline-block">
        <h5 class="h4 d-inline-block font-weight-400 mb-0">{{__('Manage Journal Entry')}}</h5>
    </div>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{__('Double Entry')}}</li>
    <li class="breadcrumb-item active" aria-current="page">{{__('Journal Account')}}</li>
@endsection




@section('action-btn')
    @if(\Auth::user()->type=='company')
        <a href="{{ route('journal-entry.create') }}" class="btn btn-sm btn-primary btn-icon m-1" 
            data-bs-whatever="{{__('Create New Journal')}}" > <span class="text-white"> 
                <i class="ti ti-plus text-white" data-bs-toggle="tooltip" data-bs-original-title="{{ __('Create') }}"></i></span></a>

       
    @endif

@endsection
@section('content')
    {{-- <div class="row">
        <div class="col-md-12">
            <div class="card">

                    <div class="table-responsive">
                        <table class="table align-items-center" id="myTable">
                            <thead>
                            <tr>
                                <th> {{__('Journal ID')}}</th>
                                <th> {{__('Date')}}</th>
                                <th> {{__('Amount')}}</th>
                                <th> {{__('Description')}}</th>
                                <th> {{__('Action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($journalEntries as $journalEntry)
                                <tr>
                                    <td class="Id">
                                        <a href="{{ route('journal-entry.show',$journalEntry->id) }}">{{ AUth::user()->journalNumberFormat($journalEntry->journal_id) }}</a>
                                    </td>
                                    <td>{{ Auth::user()->dateFormat($journalEntry->date) }}</td>
                                    <td>
                                        {{ \Auth::user()->priceFormat($journalEntry->totalCredit())}}
                                    </td>
                                    <td>{{!empty($journalEntry->description)?$journalEntry->description:'-'}}</td>
                                    <td>

                                            <a data-title="{{__('Edit Journal')}}" href="{{ route('journal-entry.edit',[$journalEntry->id]) }}" class="action-item" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
                                                <i class="fas fa-pencil-alt"></i>
                                            </a>

                                            <a href="#" class="action-item" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$journalEntry->id}}').submit();">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                            {!! Form::open(['method' => 'DELETE', 'route' => array('journal-entry.destroy', $journalEntry->id),'id'=>'delete-form-'.$journalEntry->id]) !!}
                                            {!! Form::close() !!}

                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

            </div>
        </div>
    </div> --}}

    <div class="col-xl-12">
        <div class="card">
            <div class="card-header card-body table-border-style">
                <h5></h5>
                <div class="table-responsive">
                    <table class="table" id="pc-dt-simple">
                        <thead>
                            <tr>
                                <th> {{__('Journal ID')}}</th>
                                <th> {{__('Date')}}</th>
                                <th> {{__('Amount')}}</th>
                                <th> {{__('Description')}}</th>
                                <th> {{__('Action')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($journalEntries as $journalEntry)
                                <tr>
                                    <td class="Id">
                                        <a href="{{ route('journal-entry.show',$journalEntry->id) }}">{{ AUth::user()->journalNumberFormat($journalEntry->journal_id) }}</a>
                                    </td>
                                    <td>{{ Auth::user()->dateFormat($journalEntry->date) }}</td>
                                    <td>
                                        {{ \Auth::user()->priceFormat($journalEntry->totalCredit())}}
                                    </td>
                                    <td>{{!empty($journalEntry->description)?$journalEntry->description:'-'}}</td>
                                    <td>
                                        <div class="action-btn bg-info ms-2">
                                            <a href="{{ route('journal-entry.edit',[$journalEntry->id]) }}" 
                                                class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                            data-bs-whatever="{{__('Edit Contract')}}" > <span class="text-white"> <i
                                                    class="ti ti-edit" data-bs-toggle="tooltip" data-bs-original-title="{{ __('Edit') }}"></i></span></a>
                                        </div>

                                        <div class="action-btn bg-danger ms-2">
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['journal-entry.destroy', $journalEntry->id]]) !!}
                                                <a href="#!" class="mx-3 btn btn-sm  align-items-center show_confirm ">
                                                    <i class="ti ti-trash text-white" data-bs-toggle="tooltip" data-bs-original-title="{{ __('Delete') }}"></i>
                                                </a>
                                                {!! Form::close() !!}

                                            {{-- <form method="POST" action="{{ route('journal-entry.destroy', $journalEntry->id) }}">
                                                @csrf
                                                <input name="_method" type="hidden" value="DELETE">
                                                <button type="submit" class="mx-3 btn btn-sm d-inline-flex align-items-center show_confirm" data-toggle="tooltip"
                                                title='Delete'>
                                                <span class="text-white"> <i
                                                    class="ti ti-trash"></i></span>
                                                </button>
                                            </form> --}}
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

@endsection
