@extends('layouts.admin')

@section('title')
    <div class="d-inline-block">
        <h5 class="h4 d-inline-block font-weight-400 mb-0 "> {{__('Product Stock')}}</h5>
    </div>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{__('Report')}}</li>
    <li class="breadcrumb-item active" aria-current="page">{{__('Item Stock Report')}}</li>
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
                            <th>{{__('Date')}}</th>
                            <th>{{__('Item Name')}}</th>
                            <th>{{__('Quantity')}}</th>
                            <th>{{__('Type')}}</th>
                            <th>{{__('Description')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($stocks as $stock)
                            <tr>
                                <td class="font-style">{{$stock->created_at->format('d M Y')}}</td>
                                <td>{{ !empty($stock->item) ? $stock->item->name : '' }}
                                <td class="font-style">{{ $stock->quantity }}</td>
                                <td class="font-style">{{ ucfirst($stock->type) }}</td>
                                <td class="font-style">{{$stock->description}}</td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

    {{-- <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body py-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0" id="myTable">
                            <thead>
                            <tr>
                                <th>{{__('Date')}}</th>
                                <th>{{__('Item Name')}}</th>
                                <th>{{__('Quantity')}}</th>
                                <th>{{__('Type')}}</th>
                                <th>{{__('Description')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach ($stocks as $stock)
                                
                                    <tr>
                                        <td class="font-style">{{$stock->created_at->format('d M Y')}}</td>
                                        <td>{{ !empty($stock->item) ? $stock->item->name : '' }}
                                        <td class="font-style">{{ $stock->quantity }}</td>
                                        <td class="font-style">{{ ucfirst($stock->type) }}</td>
                                        <td class="font-style">{{$stock->description}}</td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
@endsection