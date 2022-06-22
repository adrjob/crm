 @extends('layouts.admin')
@php
    $profile=asset(Storage::url('uploads/avatar'));
    $logo = Utility::get_superadmin_logo();
@endphp
@push('script-page')
    <script src="https://js.stripe.com/v3/"></script>
    <script src="https://js.paystack.co/v1/inline.js"></script>
    <script src="https://api.ravepay.co/flwv3-pug/getpaidx/api/flwpbf-inline.js"></script>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>

    <script type="text/javascript">
            @if($invoice->getDue() > 0 && !empty($company_payment_setting) && $company_payment_setting['is_stripe_enabled'] == 'on' && !empty($company_payment_setting['stripe_key']) && !empty($company_payment_setting['stripe_secret']))

        var stripe = Stripe('{{ $company_payment_setting['stripe_key'] }}');
        var elements = stripe.elements();

        // Custom styling can be passed to options when creating an Element.
        var style = {
            base: {
                // Add your base input styles here. For example:
                fontSize: '14px',
                color: '#32325d',
            },
        };

        // Create an instance of the card Element.
        var card = elements.create('card', {style: style});

        // Add an instance of the card Element into the `card-element` <div>.
        card.mount('#card-element');

        // Create a token or display an error when the form is submitted.
        var form = document.getElementById('payment-form');
        form.addEventListener('submit', function (event) {
            event.preventDefault();

            stripe.createToken(card).then(function (result) {
                if (result.error) {
                    $("#card-errors").html(result.error.message);
                    toastrs('Error', result.error.message, 'error');
                } else {
                    // Send the token to your server.
                    stripeTokenHandler(result.token);
                }
            });
        });

        function stripeTokenHandler(token) {
            // Insert the token ID into the form so it gets submitted to the server
            var form = document.getElementById('payment-form');
            var hiddenInput = document.createElement('input');
            hiddenInput.setAttribute('type', 'hidden');
            hiddenInput.setAttribute('name', 'stripeToken');
            hiddenInput.setAttribute('value', token.id);
            form.appendChild(hiddenInput);

            // Submit the form
            form.submit();
        }

        @endif
    </script>

    <script>
        $(document).on("click", ".status_change", function () {
            var invoice_id = $(this).attr('data-invoice');
            var status = $(this).attr('data-id');
            $.ajax({
                url: '{{route('invoice.status.change')}}',
                type: 'GET',
                data: {
                    invoice_id: invoice_id,
                    status: status,
                    "_token": $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    location.reload();
                }
            });
        });

        $(document).on('change', 'select[name=item]', function () {
            var item_id = $(this).val();
            $.ajax({
                url: '{{route('invoice.items')}}',
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': jQuery('#token').val()
                },
                data: {
                    'item_id': item_id,
                },
                cache: false,
                success: function (data) {
                    var invoiceItems = JSON.parse(data);

                    $('.price').val(invoiceItems.sale_price);
                    $('.quantity').val(invoiceItems.quantity);
                    $('.discount').val(0);

                    var taxes = '';
                    var tax = [];
                    if (invoiceItems.taxes != '') {
                        for (var i = 0; i < invoiceItems.taxes.length; i++) {
                            taxes += '<span class="badge badge-primary mr-1 mt-1">' + invoiceItems.taxes[i].name + ' ' + '(' + invoiceItems.taxes[i].rate + '%)' + '</span>';
                        }
                    } else {
                        taxes = '-';
                    }


                    $('.taxId').val(invoiceItems.tax);
                    $('.tax').html(taxes);


                }
            });
        });

        $(document).on('click', '.type', function () {
            var obj = $(this).val();

            if (obj == 'milestone') {
                $('.milestoneTask').removeClass('d-none');
                $('.milestoneTask').addClass('d-block');
                $('.title').removeClass('d-block');
                $('.title').addClass('d-none');

            } else {
                $('.title').removeClass('d-none');
                $('.title').addClass('d-block');
                $('.milestoneTask').removeClass('d-block');
                $('.milestoneTask').addClass('d-none');
            }
        });

        @if(isset($company_payment_setting['paystack_public_key']))
        $(document).on("click", "#pay_with_paystack", function () {
            $('#paystack-payment-form').ajaxForm(function (res) {
                var amount = res.total_price;
                if (res.flag == 1) {
                    var paystack_callback = "{{ url('/invoice/paystack') }}";

                    var handler = PaystackPop.setup({
                        key: '{{ isset($company_payment_setting['paystack_public_key'])?$company_payment_setting['paystack_public_key']:'' }}',
                        email: res.email,
                        amount: res.total_price * 100,
                        currency: res.currency,
                        ref: 'pay_ref_id' + Math.floor((Math.random() * 1000000000) +
                            1
                        ), // generates a pseudo-unique reference. Please replace with a reference you generated. Or remove the line entirely so our API will generate one for you
                        metadata: {
                            custom_fields: [{
                                display_name: "Email",
                                variable_name: "email",
                                value: res.email,
                            }]
                        },

                        callback: function (response) {

                            window.location.href = paystack_callback + '/' + response.reference + '/' + '{{encrypt($invoice->id)}}' + '?amount=' + amount;
                        },
                        onClose: function () {
                            alert('window closed');
                        }
                    });
                    handler.openIframe();
                } else if (res.flag == 2) {
                    toastrs('Error', res.msg, 'msg');
                } else {
                    toastrs('Error', res.message, 'msg');
                }

            }).submit();
        });
        @endif

        @if(isset($company_payment_setting['flutterwave_public_key']))
        //    Flaterwave Payment
        $(document).on("click", "#pay_with_flaterwave", function () {
            $('#flaterwave-payment-form').ajaxForm(function (res) {

                if (res.flag == 1) {
                    var amount = res.total_price;
                    var API_publicKey = '{{isset( $company_payment_setting['flutterwave_public_key'] )? $company_payment_setting['flutterwave_public_key'] :'' }}';
                    var nowTim = "{{ date('d-m-Y-h-i-a') }}";
                    var flutter_callback = "{{ url('/invoice/flaterwave') }}";
                    var x = getpaidSetup({
                        PBFPubKey: API_publicKey,
                        customer_email: '{{Auth::user()->email}}',
                        amount: res.total_price,
                        currency: '{{Utility::getValByName('site_currency')}}',
                        txref: nowTim + '__' + Math.floor((Math.random() * 1000000000)) + 'fluttpay_online-' + '{{ date('Y-m-d') }}' + '?amount=' + amount,
                        meta: [{
                            metaname: "payment_id",
                            metavalue: "id"
                        }],
                        onclose: function () {
                        },
                        callback: function (response) {
                            var txref = response.tx.txRef;
                            if (
                                response.tx.chargeResponseCode == "00" ||
                                response.tx.chargeResponseCode == "0"
                            ) {
                                window.location.href = flutter_callback + '/' + txref + '/' + '{{\Illuminate\Support\Facades\Crypt::encrypt($invoice->id)}}';
                            } else {
                                // redirect to a failure page.
                            }
                            x.close(); // use this to close the modal immediately after payment.
                        }
                    });
                } else if (res.flag == 2) {
                    toastrs('Error', res.msg, 'msg');
                } else {
                    toastrs('Error', data.message, 'msg');
                }

            }).submit();
        });
        @endif

        @if(isset($company_payment_setting['razorpay_public_key']))
        // Razorpay Payment
        $(document).on("click", "#pay_with_razorpay", function () {
            $('#razorpay-payment-form').ajaxForm(function (res) {
                if (res.flag == 1) {
                    var amount = res.total_price;
                    var razorPay_callback = '{{url('/invoice/razorpay')}}';
                    var totalAmount = res.total_price * 100;
                    var coupon_id = res.coupon;
                    var options = {
                        "key": "{{ isset( $company_payment_setting['razorpay_public_key'])? $company_payment_setting['razorpay_public_key']:''  }}", // your Razorpay Key Id
                        "amount": totalAmount,
                        "name": 'Plan',
                        "currency": '{{Utility::getValByName('site_currency')}}',
                        "description": "",
                        "handler": function (response) {
                            window.location.href = razorPay_callback + '/' + response.razorpay_payment_id + '/' + '{{\Illuminate\Support\Facades\Crypt::encrypt($invoice->id)}}' + '?amount=' + amount;
                        },
                        "theme": {
                            "color": "#528FF0"
                        }
                    };
                    var rzp1 = new Razorpay(options);
                    rzp1.open();
                } else if (res.flag == 2) {
                    toastrs('Error', res.msg, 'msg');
                } else {
                    toastrs('Error', data.message, 'msg');
                }

            }).submit();
        });
        @endif

        $('.cp_link').on('click', function () {
            var value = $(this).attr('data-link');
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val(value).select();
            document.execCommand("copy");
            $temp.remove();
            toastrs('Success', '{{__('Link Copy on Clipboard')}}', 'success')
        });
    </script>
@endpush
@section('page-title')
    {{__('Invoice Detail')}}
@endsection
@section('title')
    <div class="d-inline-block">
        <h5 class="h4 d-inline-block font-weight-400 mb-0 ">{{\Auth::user()->invoicenumberFormat($invoice->invoice_id).' '.__('Details')}}</h5>
    </div>

@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Sale')}}</li>
    <li class="breadcrumb-item"><a href="{{route('invoice.index')}}">{{__('Invoice')}}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{\Auth::user()->invoicenumberFormat($invoice->invoice_id)}}</li>
@endsection
@section('action-btn')
<a href="#" class="btn btn-sm btn-primary btn-icon m-1 cp_link" data-link="{{route('pay.invoice',\Illuminate\Support\Facades\Crypt::encrypt($invoice->id))}}" data-toggle="tooltip" data-original-title="{{__('Click to copy invoice link')}}">
    <i class="ti ti-copy"data-bs-toggle="tooltip" data-bs-original-title="{{ __('Copy') }}"></i>
    <span class="btn-inner--text">{{__('Copy')}}</span>
    
</a>
    @if(\Auth::user()->type=='company')
        <a href="#" data-size="lg" data-url="{{route('invoice.create.item',$invoice->id)}}" data-bs-toggle="modal"
            data-bs-target="#exampleModal" data-bs-whatever="{{__('Add Item')}}" class="btn btn-sm btn-primary btn-icon m-1">
            <span class="btn-inner--icon"><i class="ti ti-plus"></i></span>
            <span class="btn-inner--text">{{__('Add Item')}}</span>
        </a>


        @if($invoice->status!=0 && $invoice->status!=5)
            <a href="{{route('invoice.send',$invoice->id)}}" class="btn btn-sm btn-primary btn-icon m-1">
                <span class="btn-inner--icon"><i class="ti ti-send"></i></span>
                <span class="btn-inner--text">{{__('Resend')}}</span>
            </a>
        @else
            @if(!empty($invoice->items))
                <a href="{{route('invoice.send',$invoice->id)}}" class="btn btn-sm btn-primary btn-icon m-1">
                    <span class="btn-inner--icon"><i class="ti ti-send"></i></span>
                    <span class="btn-inner--text">{{__('Send')}}</span>
                </a>
            @endif
        @endif
        <a href="#" data-size="lg" data-url="{{route('invoice.create.receipt',$invoice->id)}}"
            data-bs-toggle="modal"  data-bs-target="#exampleModal" data-bs-whatever="{{__('Add Receipt')}}" class="btn btn-sm btn-primary btn-icon m-1">
            <span class="btn-inner--icon"><i class="ti ti-plus"></i></span>
            <span class="btn-inner--text">{{__('Add Receipt')}}</span>
        </a>

        <a href="{{route('invoice.send',$invoice->id)}}" class="btn btn-sm btn-primary btn-icon m-1">
            <span class="btn-inner--icon"><i class="ti ti-report-money"></i></span>
            <span class="btn-inner--text">{{__('Payment Reminder')}}</span>
        </a>
    @endif

    <a href="{{route('invoice.pdf',\Crypt::encrypt($invoice->id))}}" target="_blank" class="btn btn-sm btn-primary btn-icon m-1">
        <span class="btn-inner--icon"><i class="ti ti-printer"></i></span>
        <span class="btn-inner--text">{{__('Print')}}</span>
    </a>

    @if(\Auth::user()->type == 'client')
        @if($invoice->getDue() > 0 && !empty($company_payment_setting) && ($company_payment_setting['is_stripe_enabled'] == 'on' || $company_payment_setting['is_paypal_enabled'] == 'on' || $company_payment_setting['is_paystack_enabled'] == 'on' || $company_payment_setting['is_flutterwave_enabled'] == 'on' || $company_payment_setting['is_razorpay_enabled'] == 'on' || $company_payment_setting['is_mercado_enabled'] == 'on' || $company_payment_setting['is_paytm_enabled'] == 'on' ||
        $company_payment_setting['is_mollie_enabled']
         == 'on'
         || $company_payment_setting['is_paypal_enabled'] == 'on' || $company_payment_setting['is_skrill_enabled'] == 'on' || $company_payment_setting['is_coingate_enabled'] == 'on' || $company_payment_setting['is_paymentwall_enabled']))
            <a href="#" data-toggle="modal" data-target="#paymentModal" class="btn btn-sm btn-primary btn-icon m-1" type="button">
                <i class="fas fa-coins mr-1"></i> {{__('Pay Now')}}
            </a>
        @endif
    @endif
@endsection
@section('filter')
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
                                        <table class="table mt-0 table-responsive invoice-table table-borderless">
                                            <tbody>
                                                <tr>
                                                    <td><a href="{{ route('invoice.index') }}"><img class="img-fluid mb-3"
                                                                src="{{ asset(Storage::url('uploads/logo/'.$logo)) }}"
                                                                alt="Dashboard-kit Logo"></a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>{{$settings['company_name']}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>{{$settings['company_address']}} , {{$settings['company_city']}} <br> {{$settings['company_country']}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>{{$settings['company_zipcode']}}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4"></div>
                        </div>
                        
                        <div class="row invoive-info d-print-inline-flex">
                            @if(!empty($invoice->clientDetail))
                                <div class="col-sm-4 invoice-client-info">
                                    <h6>{{ __('Invoice To :') }}</h6>
                                    <h6 class="m-0">{{!empty($invoice->clientDetail->company_name)?$invoice->clientDetail->company_name:''}}</h6>
                                    <p class="m-0 m-t-10">{{!empty($invoice->clientDetail->address_1)?$invoice->clientDetail->address_1:''}} , {{!empty($invoice->clientDetail->city)?$invoice->clientDetail->city:''}} <br> {{!empty($invoice->clientDetail->state)?$invoice->clientDetail->state:''}}</p>
                                    <p class="m-0">{{!empty($invoice->clientDetail->mobile)?$invoice->clientDetail->mobile:''}}</p>
                                    <p><a class="text-secondary" href="$" target="_top"><span class="__cf_email__"
                                                data-cfemail="6a0e0f07052a0d070b030644090507">{{!empty($invoice->clientDetail->zip_code)?$invoice->clientDetail->zip_code:''}}</span></a>
                                    </p>
                                </div>
                            @endif
                            <div class="col-sm-4">
                                <h6 class="m-b-20">{{ __('Order Details :') }}</h6>
                                <table class="table table-responsive mt-0 invoice-table invoice-order table-borderless">
                                    <tbody>
                                        <tr>
                                            <th>{{ __('Issue Date :') }}</th>
                                            <td>{{\Auth::user()->dateFormat($invoice->issue_date)}}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Expiry Date : ') }}</th>
                                            <td>{{\Auth::user()->dateFormat($invoice->due_date)}}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Status : ') }}</th>
                                            <td>
                                                @if($invoice->status == 0)
                                                    <span class="badge bg-primary">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                                @elseif($invoice->status == 1)
                                                    <span class="badge bg-info">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                                @elseif($invoice->status == 2)
                                                    <span class="badge bg-secondary">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                                @elseif($invoice->status == 3)
                                                    <span class="badge bg-danger">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                                @elseif($invoice->status == 4)
                                                    <span class="badge bg-warning">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                                @elseif($invoice->status == 5)
                                                    <span class="badge bg-success">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Id :') }}</th>
                                            <td>
                                                {{\Auth::user()->invoicenumberFormat($invoice->invoice_id)}}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-sm-4">
                                <h6 class="m-b-20">{{ __('Invoice No.') }}</h6>
                                <h6 class="text-uppercase text-primary">{{\Auth::user()->invoicenumberFormat($invoice->invoice_id)}}
                                </h6>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="table-responsive mb-4">
                                    <table class="table invoice-detail-table">
                                        <thead>
                                            <tr class="thead-default">
                                                <th>{{__('Item')}}</th>
                                                <th>{{__('Quantity')}}</th>
                                                <th>{{__('Rate')}}</th>
                                                <th>{{__('Tax')}}</th>
                                                <th>{{__('Discount')}}</th>
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
                                            @foreach($invoice->items as $item)
                                            @php
                                                if(!empty($item->tax)){
                                                $taxes=\Utility::tax($item->tax);
                                                $totalQuantity+=$item->quantity;
                                                $totalRate+=$item->price;
                                                $totalDiscount+=$item->discount;

                                                foreach($taxes as $taxe){
                                                    $taxDataPrice=\Utility::taxRate($taxe->rate,$item->price,$item->quantity);
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
                                                        <h6>{{!empty($item->items) ? $item->items->name : '-' }}</h6>
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
                                                    <td>{{\Auth::user()->priceFormat($item->discount)}}</td>
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
                                                <td>{{\Auth::user()->priceFormat($invoice->getSubTotal())}}</td>
                                            </tr>
                                            <tr>
                                                <th>{{__('Discount :')}}</th>
                                                <td>{{\Auth::user()->priceFormat($invoice->getTotalDiscount())}}</td>
                                            </tr>
                                            @if(!empty($taxesData))
                                                @foreach($taxesData as $taxName => $taxPrice)
                                                    <tr>
                                                      
                                                        <th>{{$taxName}}</th>
                                                        <td>{{ \Auth::user()->priceFormat($taxPrice) }}</td>
                                                    </tr>
                                                @endforeach
                                            @endif

                                            <tr>
                                                <th>{{__('Total :')}}</th>
                                                <td>{{\Auth::user()->priceFormat($invoice->getTotal())}}</td>
                                            </tr>
                                            <tr>
                                                <th>{{__('Credit Note :')}}</th>
                                                <td>{{\Auth::user()->priceFormat($invoice->invoiceCreditNote())}}</td>
                                            </tr>
                                            <tr>
                                                <th>{{__('Paid :')}}</th>
                                                <td>{{\Auth::user()->priceFormat(($invoice->getTotal()-$invoice->getDue()-$invoice->invoiceCreditNote()))}}</td>
                                            </tr>
                                            <tr>
                                                <th>{{__('Due :')}}</th>
                                                <td>{{\Auth::user()->priceFormat($invoice->getDue())}}</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <hr />
                                                    <h5 class="text-primary m-r-10">{{ __('Total :') }}</h5>
                                                </td>
                                                <td>
                                                    <hr />
                                                    <h5 class="text-primary">{{\Auth::user()->priceFormat($invoice->getTotal())}}</h5>
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


    <div class="row">
        <div class="col-sm-12">
            <div class="card table-responsive mb-4">
                <div>
                    
                </div>
                <table class="card-body table invoice-detail-table">
                    <thead>
                        <tr class="thead-default">
                            <th>{{__('Transaction ID')}}</th>
                            <th>{{__('Payment Date')}}</th>
                            <th>{{__('Payment Method')}}</th>
                            <th>{{__('Payment Type')}}</th>
                            <th>{{__('Note')}}</th>
                            <th>{{__('Amount')}}</th>
                            @if(\Auth::user()->type=='company')
                            <th > {{ __('Action') }}</th>
                        @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoice->payments as $payment)
                            <tr>
                                <td>{{$payment->transaction}} </td>
                                <td>{{\Auth::user()->dateFormat($payment->date)}} </td>
                                <td>{{!empty($payment->payments)?$payment->payments->name:''}} </td>
                                <td>{{$payment->payment_type}} </td>
                                <td>{{$payment->notes}} </td>
                                <td> {{\Auth::user()->priceFormat(($payment->amount))}}</td>
                                <td>
                                    @if(!empty($payment->receipt))
                                    @php
                                        $x = pathinfo($payment->receipt, PATHINFO_FILENAME);
                                        $extension = pathinfo($payment->receipt, PATHINFO_EXTENSION);
                                        $result = str_replace(array("#", "'", ";"), '', $payment->receipt);
                                        
                                    @endphp
                                    <a  href="{{ route('invoice.receipt' , [$x,"$extension"]) }}"  data-toggle="tooltip" class="btn btn-sm btn-secondary btn-icon rounded-pill">
                                        <i class="ti ti-download"></i>
                                    </a>
                                    @else
                                        -
                                @endif
                                </td>
                                @if(\Auth::user()->type=='company')
                                <div>
                                    <td width="5%">
                                        <div class="action-btn bg-danger ms-2">
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['invoice.payment.delete', $invoice->id,$payment->id]]) !!}
                                            <a href="#!" class="mx-3 btn btn-sm  align-items-center show_confirm ">
                                                <i class="ti ti-trash text-white"></i>
                                            </a>
                                            {!! Form::close() !!}
                                        </div>
                                        
                                    </td>
                                </div>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!--Payment Modal-->
    @if(\Auth::user()->type=='client')
        @if($invoice->getDue() > 0)
            <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="paymentModalLabel">{{ __('Add Payment') }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            @if(!empty($company_payment_setting) && ($company_payment_setting['is_stripe_enabled'] == 'on' || $company_payment_setting['is_paypal_enabled'] == 'on' || $company_payment_setting['is_paystack_enabled'] == 'on' || $company_payment_setting['is_flutterwave_enabled'] == 'on' || $company_payment_setting['is_razorpay_enabled'] == 'on' || $company_payment_setting['is_mercado_enabled'] == 'on' || $company_payment_setting['is_paytm_enabled'] == 'on' || $company_payment_setting['is_mollie_enabled']
                            == 'on' ||
                            $company_payment_setting['is_paypal_enabled'] == 'on' || $company_payment_setting['is_skrill_enabled'] == 'on' || $company_payment_setting['is_coingate_enabled'] == 'on'))
                                <ul class="nav nav-pills  mb-3" role="tablist">
                                    @if($company_payment_setting['is_stripe_enabled'] == 'on' && !empty($company_payment_setting['stripe_key']) && !empty($company_payment_setting['stripe_secret']))
                                        <li class="nav-item mb-2">
                                            <a class="btn btn-outline-primary btn-sm active" data-toggle="tab" href="#stripe-payment" role="tab" aria-controls="stripe" aria-selected="true">{{ __('Stripe') }}</a>
                                        </li>
                                    @endif

                                    @if($company_payment_setting['is_paypal_enabled'] == 'on' && !empty($company_payment_setting['paypal_client_id']) && !empty($company_payment_setting['paypal_secret_key']))
                                        <li class="nav-item mb-2">
                                            <a class="btn btn-outline-primary btn-sm ml-1" data-toggle="tab" href="#paypal-payment" role="tab" aria-controls="paypal" aria-selected="false">{{ __('Paypal') }}</a>
                                        </li>
                                    @endif

                                    @if($company_payment_setting['is_paystack_enabled'] == 'on' && !empty($company_payment_setting['paystack_public_key']) && !empty($company_payment_setting['paystack_secret_key']))
                                        <li class="nav-item mb-2">
                                            <a class="btn btn-outline-primary btn-sm ml-1" data-toggle="tab" href="#paystack-payment" role="tab" aria-controls="paystack" aria-selected="false">{{ __('Paystack') }}</a>
                                        </li>
                                    @endif

                                    @if(isset($company_payment_setting['is_flutterwave_enabled']) && $company_payment_setting['is_flutterwave_enabled'] == 'on')
                                        <li class="nav-item mb-2">
                                            <a class="btn btn-outline-primary btn-sm ml-1" data-toggle="tab" href="#flutterwave-payment" role="tab" aria-controls="flutterwave" aria-selected="false">{{ __('Flutterwave') }}</a>
                                        </li>
                                    @endif

                                    @if(isset($company_payment_setting['is_razorpay_enabled']) && $company_payment_setting['is_razorpay_enabled'] == 'on')
                                        <li class="nav-item mb-2">
                                            <a class="btn btn-outline-primary btn-sm ml-1" data-toggle="tab" href="#razorpay-payment" role="tab" aria-controls="razorpay" aria-selected="false">{{ __('Razorpay') }}</a>
                                        </li>
                                    @endif


                                    @if(isset($company_payment_setting['is_mercado_enabled']) && $company_payment_setting['is_mercado_enabled'] == 'on')
                                        <li class="nav-item mb-2">
                                            <a class="btn btn-outline-primary btn-sm ml-1" data-toggle="tab" href="#mercado-payment" role="tab" aria-controls="mercado" aria-selected="false">{{ __('Mercado') }}</a>
                                        </li>
                                    @endif

                                    @if(isset($company_payment_setting['is_paytm_enabled']) && $company_payment_setting['is_paytm_enabled'] == 'on')
                                        <li class="nav-item mb-2">
                                            <a class="btn btn-outline-primary btn-sm ml-1" data-toggle="tab" href="#paytm-payment" role="tab" aria-controls="paytm" aria-selected="false">{{ __('Paytm') }}</a>
                                        </li>
                                    @endif

                                    @if(isset($company_payment_setting['is_mollie_enabled']) && $company_payment_setting['is_mollie_enabled'] == 'on')
                                        <li class="nav-item mb-2">
                                            <a class="btn btn-outline-primary btn-sm ml-1" data-toggle="tab" href="#mollie-payment" role="tab" aria-controls="mollie" aria-selected="false">{{ __('Mollie') }}</a>
                                        </li>
                                    @endif

                                    @if(isset($company_payment_setting['is_skrill_enabled']) && $company_payment_setting['is_skrill_enabled'] == 'on')
                                        <li class="nav-item mb-2">
                                            <a class="btn btn-outline-primary btn-sm ml-1" data-toggle="tab" href="#skrill-payment" role="tab" aria-controls="skrill" aria-selected="false">{{ __('Skrill') }}</a>
                                        </li>
                                    @endif

                                    @if(isset($company_payment_setting['is_coingate_enabled']) && $company_payment_setting['is_coingate_enabled'] == 'on')
                                        <li class="nav-item mb-2">
                                            <a class="btn btn-outline-primary btn-sm ml-1" data-toggle="tab" href="#coingate-payment" role="tab" aria-controls="coingate" aria-selected="false">{{ __('Coingate') }}</a>
                                        </li>
                                    @endif
                                
                                    @if(isset($company_payment_setting['is_paymentwall_enabled']) && $company_payment_setting['is_paymentwall_enabled'] == 'on')
                                    <li class="nav-item mb-2">
                                        <a class="btn btn-outline-primary btn-sm ml-1" data-toggle="tab" href="#paymentwall-payment" role="tab" aria-controls="paymentwall" aria-selected="false">{{ __('PaymentWall') }}</a>
                                    </li>
                                @endif


                                </ul>
                            @endif
                            <div class="tab-content">
                                @if(!empty($company_payment_setting) && ( $company_payment_setting['is_stripe_enabled'] == 'on' && !empty($company_payment_setting['stripe_key']) && !empty($company_payment_setting['stripe_secret'])))
                                    <div class="tab-pane fade active show" id="stripe-payment" role="tabpanel" aria-labelledby="stripe-payment">
                                        <form method="post" action="{{ route('client.invoice.payment',$invoice->id) }}" class="require-validation" id="payment-form">
                                            @csrf
                                            <div class="row">
                                                <div class="col-sm-8">
                                                    <div class="custom-radio">
                                                        <label class="font-16 font-weight-bold">{{__('Credit / Debit Card')}}</label>
                                                    </div>
                                                    <p class="mb-0 pt-1 text-sm">{{__('Safe money transfer using your bank account. We support Mastercard, Visa, Discover and American express.')}}</p>
                                                </div>

                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="card-name-on">{{__('Name on card')}}</label>
                                                        <input type="text" name="name" id="card-name-on" class="form-control required" placeholder="{{\Auth::user()->name}}">
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div id="card-element">

                                                    </div>
                                                    <div id="card-errors" role="alert"></div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-md-12">
                                                    <br>
                                                    <label for="amount">{{ __('Amount') }}</label>
                                                    <div class="input-group">
                                                        <span class="input-group-prepend"><span class="input-group-text">{{ Utility::getValByName('site_currency') }}</span></span>
                                                        <input class="form-control" required="required" min="0" name="amount" type="number" value="{{$invoice->getDue()}}" min="0" step="0.01" max="{{$invoice->getDue()}}" id="amount">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="error" style="display: none;">
                                                        <div class='alert-danger alert'>{{__('Please correct the errors and try again.')}}</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group mt-3">
                                                <button class="btn btn-sm btn-primary rounded-pill" type="submit">{{ __('Make Payment') }}</button>
                                            </div>
                                        </form>
                                    </div>
                                @endif

                                @if(!empty($company_payment_setting) &&  ($company_payment_setting['is_paypal_enabled'] == 'on' && !empty($company_payment_setting['paypal_client_id']) && !empty($company_payment_setting['paypal_secret_key'])))
                                    <div class="tab-pane fade " id="paypal-payment" role="tabpanel" aria-labelledby="paypal-payment">
                                        <form class="w3-container w3-display-middle w3-card-4 " method="POST" id="payment-form" action="{{ route('client.pay.with.paypal',$invoice->id) }}">
                                            @csrf
                                            <div class="row">
                                                <div class="form-group col-md-12">
                                                    <label for="amount">{{ __('Amount') }}</label>
                                                    <div class="input-group">
                                                        <span class="input-group-prepend"><span class="input-group-text">{{ Utility::getValByName('site_currency') }}</span></span>
                                                        <input class="form-control" required="required" min="0" name="amount" type="number" value="{{$invoice->getDue()}}" min="0" step="0.01" max="{{$invoice->getDue()}}" id="amount">
                                                        @error('amount')
                                                        <span class="invalid-amount" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group mt-3">
                                                <button class="btn btn-sm btn-primary rounded-pill" name="submit" type="submit">{{ __('Make Payment') }}</button>
                                            </div>
                                        </form>
                                    </div>
                                @endif

                                @if(!empty($company_payment_setting) && ($company_payment_setting['is_paystack_enabled'] == 'on' && !empty($company_payment_setting['paystack_public_key']) && !empty($company_payment_setting['paystack_secret_key'])))
                                    <div class="tab-pane fade " id="paystack-payment" role="tabpanel" aria-labelledby="paypal-payment">
                                        <form class="w3-container w3-display-middle w3-card-4" method="POST" id="paystack-payment-form" action="{{ route('invoice.pay.with.paystack') }}">
                                            @csrf
                                            <input type="hidden" name="invoice_id" value="{{\Illuminate\Support\Facades\Crypt::encrypt($invoice->id)}}">

                                            <div class="form-group col-md-12">
                                                <label for="amount">{{ __('Amount') }}</label>
                                                <div class="input-group">
                                                    <span class="input-group-prepend"><span class="input-group-text">{{ Utility::getValByName('site_currency') }}</span></span>
                                                    <input class="form-control" required="required" min="0" name="amount" type="number" value="{{$invoice->getDue()}}" min="0" step="0.01" max="{{$invoice->getDue()}}" id="amount">

                                                </div>
                                            </div>
                                            <div class="form-group mt-3">
                                                <input class="btn btn-sm btn-primary rounded-pill" id="pay_with_paystack" type="button" value="{{ __('Make Payment') }}">
                                            </div>

                                        </form>
                                    </div>
                                @endif

                                @if(!empty($company_payment_setting) &&  $company_payment_setting['is_flutterwave_enabled'] == 'on' && !empty($company_payment_setting['paystack_public_key']) && !empty($company_payment_setting['paystack_secret_key']))
                                    <div class="tab-pane fade " id="flutterwave-payment" role="tabpanel" aria-labelledby="paypal-payment">
                                        <form role="form" action="{{ route('invoice.pay.with.flaterwave') }}" method="post" class="require-validation" id="flaterwave-payment-form">
                                            @csrf
                                            <input type="hidden" name="invoice_id" value="{{\Illuminate\Support\Facades\Crypt::encrypt($invoice->id)}}">

                                            <div class="form-group col-md-12">
                                                <label for="amount">{{ __('Amount') }}</label>
                                                <div class="input-group">
                                                    <span class="input-group-prepend"><span class="input-group-text">{{ Utility::getValByName('site_currency') }}</span></span>
                                                    <input class="form-control" required="required" min="0" name="amount" type="number" value="{{$invoice->getDue()}}" min="0" step="0.01" max="{{$invoice->getDue()}}" id="amount">

                                                </div>
                                            </div>
                                            <div class="form-group mt-3">
                                                <input class="btn btn-sm btn-primary rounded-pill" id="pay_with_flaterwave" type="button" value="{{ __('Make Payment') }}">
                                            </div>

                                        </form>
                                    </div>
                                @endif

                                @if(isset($company_payment_setting['is_razorpay_enabled']) && $company_payment_setting['is_razorpay_enabled'] == 'on')
                                    <div class="tab-pane fade " id="razorpay-payment" role="tabpanel" aria-labelledby="paypal-payment">
                                        <form role="form" action="{{ route('invoice.pay.with.razorpay') }}" method="post" class="require-validation" id="razorpay-payment-form">
                                            @csrf
                                            <input type="hidden" name="invoice_id" value="{{\Illuminate\Support\Facades\Crypt::encrypt($invoice->id)}}">

                                            <div class="form-group col-md-12">
                                                <label for="amount">{{ __('Amount') }}</label>
                                                <div class="input-group">
                                                    <span class="input-group-prepend"><span class="input-group-text">{{ Utility::getValByName('site_currency') }}</span></span>
                                                    <input class="form-control" required="required" min="0" name="amount" type="number" value="{{$invoice->getDue()}}" min="0" step="0.01" max="{{$invoice->getDue()}}" id="amount">

                                                </div>
                                            </div>
                                            <div class="form-group mt-3">
                                                <input class="btn btn-sm btn-primary rounded-pill" id="pay_with_razorpay" type="button" value="{{ __('Make Payment') }}">
                                            </div>

                                        </form>
                                    </div>
                                @endif

                                @if(isset($company_payment_setting['is_mercado_enabled']) && $company_payment_setting['is_mercado_enabled'] == 'on')
                                    <div class="tab-pane fade " id="mercado-payment" role="tabpanel" aria-labelledby="mercado-payment">
                                        <form role="form" action="{{ route('invoice.pay.with.mercado') }}" method="post" class="require-validation" id="mercado-payment-form">
                                            @csrf
                                            <input type="hidden" name="invoice_id" value="{{\Illuminate\Support\Facades\Crypt::encrypt($invoice->id)}}">

                                            <div class="form-group col-md-12">
                                                <label for="amount">{{ __('Amount') }}</label>
                                                <div class="input-group">
                                                    <span class="input-group-prepend"><span class="input-group-text">{{ Utility::getValByName('site_currency') }}</span></span>
                                                    <input class="form-control" required="required" min="0" name="amount" type="number" value="{{$invoice->getDue()}}" min="0" step="0.01" max="{{$invoice->getDue()}}" id="amount">

                                                </div>
                                            </div>
                                            <div class="form-group mt-3">
                                                <input type="submit" id="pay_with_mercado" value="{{__('Make Payment')}}" class="btn btn-sm btn-primary rounded-pill">
                                            </div>

                                        </form>
                                    </div>
                                @endif

                                @if(isset($company_payment_setting['is_paytm_enabled']) && $company_payment_setting['is_paytm_enabled'] == 'on')
                                    <div class="tab-pane fade" id="paytm-payment" role="tabpanel" aria-labelledby="paytm-payment">
                                        <form role="form" action="{{ route('invoice.pay.with.paytm') }}" method="post" class="require-validation" id="paytm-payment-form">
                                            @csrf
                                            <input type="hidden" name="invoice_id" value="{{\Illuminate\Support\Facades\Crypt::encrypt($invoice->id)}}">

                                            <div class="form-group col-md-12">
                                                <label for="amount">{{ __('Amount') }}</label>
                                                <div class="input-group">
                                                    <span class="input-group-prepend"><span class="input-group-text">{{ Utility::getValByName('site_currency') }}</span></span>
                                                    <input class="form-control" required="required" min="0" name="amount" type="number" value="{{$invoice->getDue()}}" min="0" step="0.01" max="{{$invoice->getDue()}}" id="amount">

                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="flaterwave_coupon" class=" text-dark">{{__('Mobile Number')}}</label>
                                                    <input type="text" id="mobile" name="mobile" class="form-control mobile" data-from="mobile" placeholder="{{ __('Enter Mobile Number') }}" required>
                                                </div>
                                            </div>
                                            <div class="form-group mt-3">
                                                <input type="submit" id="pay_with_paytm" value="{{__('Make Payment')}}" class="btn btn-sm btn-primary rounded-pill">
                                            </div>

                                        </form>
                                    </div>
                                @endif

                                @if(isset($company_payment_setting['is_mollie_enabled']) && $company_payment_setting['is_mollie_enabled'] == 'on')
                                    <div class="tab-pane fade " id="mollie-payment" role="tabpanel" aria-labelledby="mollie-payment">
                                        <form role="form" action="{{ route('invoice.pay.with.mollie') }}" method="post" class="require-validation" id="mollie-payment-form">
                                            @csrf
                                            <input type="hidden" name="invoice_id" value="{{\Illuminate\Support\Facades\Crypt::encrypt($invoice->id)}}">

                                            <div class="form-group col-md-12">
                                                <label for="amount">{{ __('Amount') }}</label>
                                                <div class="input-group">
                                                    <span class="input-group-prepend"><span class="input-group-text">{{ Utility::getValByName('site_currency') }}</span></span>
                                                    <input class="form-control" required="required" min="0" name="amount" type="number" value="{{$invoice->getDue()}}" min="0" step="0.01" max="{{$invoice->getDue()}}" id="amount">

                                                </div>
                                            </div>
                                            <div class="form-group mt-3">
                                                <input type="submit" id="pay_with_mollie" value="{{__('Make Payment')}}" class="btn btn-sm btn-primary rounded-pill">
                                            </div>

                                        </form>
                                    </div>
                                @endif

                                @if(isset($company_payment_setting['is_skrill_enabled']) && $company_payment_setting['is_skrill_enabled'] == 'on')
                                    <div class="tab-pane fade " id="skrill-payment" role="tabpanel" aria-labelledby="skrill-payment">
                                        <form role="form" action="{{ route('invoice.pay.with.skrill') }}" method="post" class="require-validation" id="skrill-payment-form">
                                            @csrf
                                            <input type="hidden" name="invoice_id" value="{{\Illuminate\Support\Facades\Crypt::encrypt($invoice->id)}}">

                                            <div class="form-group col-md-12">
                                                <label for="amount">{{ __('Amount') }}</label>
                                                <div class="input-group">
                                                    <span class="input-group-prepend"><span class="input-group-text">{{ Utility::getValByName('site_currency') }}</span></span>
                                                    <input class="form-control" required="required" min="0" name="amount" type="number" value="{{$invoice->getDue()}}" min="0" step="0.01" max="{{$invoice->getDue()}}" id="amount">

                                                </div>
                                            </div>
                                            @php
                                                $skrill_data = [
                                                    'transaction_id' => md5(date('Y-m-d') . strtotime('Y-m-d H:i:s') . 'user_id'),
                                                    'user_id' => 'user_id',
                                                    'amount' => 'amount',
                                                    'currency' => 'currency',
                                                ];
                                                session()->put('skrill_data', $skrill_data);

                                            @endphp
                                            <div class="form-group mt-3">
                                                <input type="submit" id="pay_with_skrill" value="{{__('Make Payment')}}" class="btn btn-sm btn-primary rounded-pill">
                                            </div>

                                        </form>
                                    </div>
                                @endif

                                @if(isset($company_payment_setting['is_coingate_enabled']) && $company_payment_setting['is_coingate_enabled'] == 'on')
                                    <div class="tab-pane fade " id="coingate-payment" role="tabpanel" aria-labelledby="coingate-payment">
                                        <form role="form" action="{{ route('invoice.pay.with.coingate') }}" method="post" class="require-validation" id="coingate-payment-form">
                                            @csrf
                                            <input type="hidden" name="invoice_id" value="{{\Illuminate\Support\Facades\Crypt::encrypt($invoice->id)}}">

                                            <div class="form-group col-md-12">
                                                <label for="amount">{{ __('Amount') }}</label>
                                                <div class="input-group">
                                                    <span class="input-group-prepend"><span class="input-group-text">{{ Utility::getValByName('site_currency') }}</span></span>
                                                    <input class="form-control" required="required" min="0" name="amount" type="number" value="{{$invoice->getDue()}}" min="0" step="0.01" max="{{$invoice->getDue()}}" id="amount">

                                                </div>
                                            </div>
                                            <div class="form-group mt-3">
                                                <input type="submit" id="pay_with_coingate" value="{{__('Make Payment')}}" class="btn btn-sm btn-primary rounded-pill">
                                            </div>

                                        </form>
                                    </div>
                                @endif
                                {{-- {{ dd($company_payment_setting) }} --}}
                                @if(!empty($company_payment_setting) && ($company_payment_setting['is_paymentwall_enabled'] == 'on' && !empty($company_payment_setting['paymentwall_public_key']) && !empty($company_payment_setting['paymentwall_private_key'])))
                                    <div class="tab-pane fade " id="paymentwall-payment" role="tabpanel" aria-labelledby="paymentwall-payment">
                                        <form class="w3-container w3-display-middle w3-card-4" method="POST" id="paymentwall-payment-form" action="{{ route('invoice.paymentwallpayment') }}">
                                            @csrf
                                            <input type="hidden" name="invoice_id" value="{{\Illuminate\Support\Facades\Crypt::encrypt($invoice->id)}}">

                                            <div class="form-group col-md-12">
                                                <label for="amount">{{ __('Amount') }}</label>
                                                <div class="input-group">
                                                    <span class="input-group-prepend"><span class="input-group-text">{{ Utility::getValByName('site_currency') }}</span></span>
                                                    <input class="form-control" required="required" min="0" name="amount" type="number" value="{{$invoice->getDue()}}" min="0" step="0.01" max="{{$invoice->getDue()}}" id="amount">

                                                </div>
                                            </div>
                                            <div class="form-group mt-3">
                                                <input class="btn btn-sm btn-primary rounded-pill" id="pay_with_paymentwall" type="submit" value="{{ __('Make Payment') }}">
                                            </div>

                                        </form>
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endif

@endsection


