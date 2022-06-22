@extends('layouts.invoicepayheader')
@section('page-title')
    {{__('Estimate')}}
@endsection
@section('title')
    <div class="d-inline-block">
        <h5 class="h4 d-inline-block font-weight-400 mb-0 ">{{__('Estimate')}} {{ '('. $estimate->name .')' }}</h5>
    </div>
@endsection

@section('action-btn')
    <a href="{{route('estimate.download.pdf',\Crypt::encrypt($estimate->id))}}" target="_blank" class="btn btn-sm btn-primary btn-icon m-1">
        <span class="btn-inner--icon text-white"><i class="ti ti-printer"></i></span>
        <span class="btn-inner--text text-white">{{__('Print')}}</span>
    </a>
   
@endsection
@section('content')
 
    <div class="row">
        <!-- [ Invoice ] start -->
        <div class="container">
            <div>
                <div class="card" id="printTable">
                    <div class="card-body">
                        <div class="row ">
                            <div class="col-md-8 invoice-contact">
                                <div class="invoice-box row">
                                    <div class="col-sm-12">
                                        <table class="table mt-0 table-responsive estimate-table table-borderless">
                                            <tbody>
                                                <tr>
                                                    <td><a href="index.html"><img class="img-fluid mb-3"
                                                                src="{{ asset('assets/images/logo-dark.png') }}"
                                                                alt="Dashboard-kit Logo"></a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>{{$company_setting['company_name']}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>{{$company_setting['company_address']}} , {{$company_setting['company_city']}} <br> {{$company_setting['company_country']}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>{{$company_setting['company_zipcode']}}</td>
                                                </tr>
                                                

                                            </tbody>

                                          
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="float-end">
                                    {!! DNS2D::getBarcodeHTML(route('pay.estimate',\Illuminate\Support\Facades\Crypt::encrypt($estimate->id)), "QRCODE",2,2) !!}
                               </div>
                            </div>
                        </div>
                        
                        <div class="row invoive-info d-print-inline-flex">
                            @if(!empty($estimate->clientDetail))
                                <div class="col-sm-4 invoice-client-info">
                                    <h6>{{ __('Invoice To :') }}</h6>
                                    <h6 class="m-0">{{!empty($estimate->clientDetail->company_name)?$estimate->clientDetail->company_name:''}}</h6>
                                    <p class="m-0 m-t-10">{{!empty($estimate->clientDetail->address_1)?$estimate->clientDetail->address_1:''}} , {{!empty($estimate->clientDetail->city)?$estimate->clientDetail->city:''}} <br> {{!empty($estimate->clientDetail->state)?$estimate->clientDetail->state:''}}</p>
                                    <p class="m-0">{{!empty($estimate->clientDetail->mobile)?$estimate->clientDetail->mobile:''}}</p>
                                    <p><a class="text-secondary" href="$" target="_top"><span class="__cf_email__"
                                                data-cfemail="6a0e0f07052a0d070b030644090507">{{!empty($estimate->clientDetail->zip_code)?$estimate->clientDetail->zip_code:''}}</span></a>
                                    </p>
                                </div>
                            @endif
                            <div class="col-sm-4">
                                <h6 class="m-b-20">{{ __('Order Details :') }}</h6>
                                <table class="table table-responsive mt-0 invoice-table invoice-order table-borderless">
                                    <tbody>
                                        <tr>
                                            <th>{{ __('Issue Date :') }}</th>
                                            <td>{{\Auth::user()->dateFormat($estimate->issue_date)}}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Expiry Date : ') }}</th>
                                            <td>{{\Auth::user()->dateFormat($estimate->expiry_date)}}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Status : ') }}</th>
                                            <td>
                                                @if($estimate->status == 0)
                                                    <span class="badge bg-primary">{{ __(\App\Models\Estimate::$statues[$estimate->status]) }}</span>
                                                @elseif($estimate->status == 1)
                                                    <span class="badge bg-info">{{ __(\App\Models\Estimate::$statues[$estimate->status]) }}</span>
                                                @elseif($estimate->status == 2)
                                                    <span class="badge bg-secondary">{{ __(\App\Models\Estimate::$statues[$estimate->status]) }}</span>
                                                @elseif($estimate->status == 3)
                                                    <span class="badge bg-danger">{{ __(\App\Models\Estimate::$statues[$estimate->status]) }}</span>
                                                @elseif($estimate->status == 4)
                                                    <span class="badge bg-warning">{{ __(\App\Models\Estimate::$statues[$estimate->status]) }}</span>
                                                @elseif($estimate->status == 5)
                                                    <span class="badge bg-success">{{ __(\App\Models\Estimate::$statues[$estimate->status]) }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Id :') }}</th>
                                            <td>
                                                {{\Auth::user()->estimatenumberFormat($estimate->estimate)}}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-sm-4">
                                <h6 class="m-b-20">{{ __('Estimate No.') }}</h6>
                                <h6 class="text-uppercase text-primary">{{\Auth::user()->estimatenumberFormat($estimate->estimate)}}
                                </h6>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="table-responsive mb-4">
                                    <table class="table estimate-detail-table">
                                        <thead>
                                            <tr class="thead-default">
                                                <th>{{__('Item')}}</th>
                                                <th>{{__('Quantity')}}</th>
                                                <th>{{__('Rate')}}</th>
                                                <th>{{__('Tax')}}</th>
                                                @if($estimate->discount_apply==1)
                                                    <th>{{__('Discount')}}</th>
                                                @endif
                                                <th>{{__('Price')}}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $totalQuantity=0;
                                                $totalRate=0;
                                                $totalAmount=0;
                                                $totalTaxPrice=0;
                                                $totalDiscount=0;
                                                $taxesData=[];
                                            @endphp
                                            @foreach($estimate->items as $item)
                                            @php
                                                if(!empty($item->tax)){
                                                $taxes=\App\Models\Utility::tax($item->tax);
                                                    $totalQuantity+=$item->quantity;
                                                    $totalRate+=$item->price;
                                                    $totalDiscount+=$item->discount;
                
                                                    foreach($taxes as $taxe){
                                                        $taxDataPrice=\App\Models\Utility::taxRate($taxe->rate,$item->price,$item->quantity);
                                                        if (array_key_exists($taxe->name,$taxesData))
                                                        {
                                                            $taxesData[$taxe->name] = $taxesData[$taxe->name]+$taxDataPrice;
                                                        }
                                                        else
                                                        {
                                                            $taxesData[$taxe->name] = $taxDataPrice;
                                                        }
                                                    }
                                                }
                                            @endphp
                                                <tr>
                                                    <td>
                                                        <h6>{{!empty($item->items)?$item->items->name:'--'}}</h6>
                                                        <p>{{$item->description}}</p>
                                                    </td>
                                                    <td>{{$item->quantity}}</td>
                                                    <td>{{\Auth::user()->priceFormat($item->price)}}</td>
                                                    <td>
                                                        @if(!empty($item->tax))
                                                            @foreach($taxes as $tax)
                                                                @php
                                                                    $taxPrice=\Utility::taxRate($tax->rate,$item->price,$item->quantity);
                                                                    $totalTaxPrice+=$taxPrice;
                                                                @endphp
                                                                <a href="#!" class="d-block text-sm text-muted">{{$tax->name .' ('.$tax->rate .'%)'}} &nbsp;&nbsp;{{\Auth::user()->priceFormat($taxPrice)}}</a>
                                                            @endforeach
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    @if($estimate->discount_apply==1)
                                                        <td>{{\Auth::user()->priceFormat($item->discount)}} </td>
                                                    @endif
                                                    <td>{{\Auth::user()->priceFormat(($item->price*$item->quantity))}}</td>
                                                    @php
                                                        $totalQuantity+=$item->quantity;
                                                        $totalRate+=$item->price;
                                                        $totalDiscount+=$item->discount;
                                                        $totalAmount+=($item->price*$item->quantity);
                                                    @endphp
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="invoice-total">
                                    <table class="table invoice-table ">
                                        <tbody>
                                            <tr>
                                                <th>{{ __('Sub Total :') }}</th>
                                                <td>{{\Auth::user()->priceFormat($estimate->getSubTotal())}}</td>
                                            </tr>
                                           
                                                <tr>
                                                    @if($estimate->discount_apply==1)
                                                        <td class="px-0"></td>
                                                    @endif

                                                    <th>{{__('Discount :')}}</th>
                                                    <td>{{\Auth::user()->priceFormat($estimate->getTotalDiscount())}}</td>
                                                </tr>
                                           
                                            @if(!empty($taxesData))
                                                @foreach($taxesData as $taxName => $taxPrice)
                                              
                                                    <tr>
                                                        @if($estimate->discount_apply==1)
                                                            <td class="px-0"></td>
                                                        @endif
                                                        <th>{{$taxName}}</th>
                                                        <td>{{ \Auth::user()->priceFormat($taxPrice) }}</td>
                                                    </tr>
                                                
                                                @endforeach
                                            @endif
                                           
                                                <tr>
                                                    @if($estimate->discount_apply==1)
                                                    <td class="px-0"></td>
                                                @endif

                                                    <th>{{__('Total :')}}</th>
                                                    <td>{{\Auth::user()->priceFormat($estimate->getTotal())}}</td>
                                                </tr>
                                           
                                            <tr>
                                                <td>
                                                    <hr />
                                                    <h5 class="text-primary m-r-10">{{ __('Total Value :') }}</h5>
                                                </td>
                                                <td>
                                                    <hr />
                                                    <h5 class="text-primary">{{\Auth::user()->priceFormat($estimate->getTotal())}}</h5>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ Invoice ] end -->
    </div>
@endsection

