@extends('layouts.admin')
@push('script-page')
    <script>
        $(document).ready(function () {
            $('.cp_link').on('click', function () {
                var value = $(this).attr('data-link');
                var $temp = $("<input>");
                $("body").append($temp);
                $temp.val(value).select();
                document.execCommand("copy");
                $temp.remove();
                toastrs('Success', '{{__('Link Copy on Clipboard')}}', 'success')
            });
        });

        $(document).ready(function () {
            $('.iframe_link').on('click', function () {
                var value = $(this).attr('data-link');
                var $temp = $("<input>");
                $("body").append($temp);
                $temp.val(value).select();
                document.execCommand("copy");
                $temp.remove();
                toastrs('Success', '{{__('Link Copy on Clipboard')}}', 'success')
            });
        });

    </script>
@endpush
@section('page-title')
    {{__('Form Builder')}}
@endsection
@section('title')
    <div class="d-inline-block">
        <h5 class="h4 d-inline-block font-weight-400 mb-0">{{__('Form Builder')}}</h5>
    </div>
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{__('PreSale')}}</li>
    <li class="breadcrumb-item active" aria-current="page">{{__('Form Builder')}}</li>
@endsection
@section('action-btn')
    @if(\Auth::user()->type=='company')
    <a href="#" class="btn btn-sm btn-primary btn-icon m-1" data-bs-toggle="modal" data-size="md"
    data-bs-target="#exampleModal" data-url="{{ route('form_builder.create') }}"
    data-bs-whatever="{{__('Create New Form')}}"data-bs-toggle="tooltip" title="Create New Form" 
    data-bs-original-title="{{__('Create New Form')}}"> 
        <i class="ti ti-plus text-white"></i>
    </a>

        {{-- <a href="#" data-url="{{ route('form_builder.create') }}" data-size="md" data-ajax-popup="true" data-title="{{__('Create New Form')}}" class="btn btn-sm btn-white btn-icon-only rounded-circle" data-toggle="tooltip">
            <span class="btn-inner--icon"><i class="ti ti-plus"></i></span>
        </a> --}}
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
                                <th>{{__('Name')}}</th>
                                <th>{{__('Response')}}</th>
                                @if(\Auth::user()->type=='company')
                                    <th class="text-right" width="200px">{{__('Action')}}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($forms as $form)
                            <tr>
                                <td>{{ $form->name }}</td>
                                <td>
                                    {{ $form->response->count() }}
                                </td>

                                    @if(\Auth::user()->type=='company') 
                                        <td class="text-right">
                                            {{-- <a href="#" class="action-item iframe_link" data-link="<iframe src='{{url('/form/'.$form->code)}}' title='{{ $form->name }}'></iframe>" data-toggle="tooltip" data-original-title="{{__('Click to copy iframe link')}}"><i class="fas fa-link"></i></a> --}}
                                            
                                            <div class="action-btn bg-dark ms-2">
                                                <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center iframe_link"  data-link="<iframe src='{{url('/form/'.$form->code)}}' title='{{ $form->name }}'></iframe>"
                                                data-bs-whatever="{{__('Click to copy iframe link')}}" data-bs-toggle="tooltip" 
                                                title="{{ __('Click to copy iframe link') }}" 
                                                data-bs-original-title="{{__('Click to copy iframe link')}}"> <span class="text-white"> <i
                                                        class="ti ti-frame"></i></span></a>
                                            </div>

                                            <div class="action-btn bg-success ms-2">
                                                <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-bs-toggle="modal"
                                                data-bs-target="#exampleModal" data-url="{{ route('form.field.bind',$form->id) }}"
                                                data-bs-whatever="{{__('Convert into Lead Setting')}}" data-bs-toggle="tooltip" 
                                                title="{{ __('Convert into Lead Setting') }}" 
                                                data-bs-original-title="{{__('Convert into Lead Setting')}}"> <span class="text-white"> <i
                                                        class="ti ti-arrows-left-right"></i></span></a>
                                            </div>

                                            <div class="action-btn bg-secondary ms-2">
                                                <a href="{{route('form_builder.show',$form->id)}}" class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                data-bs-whatever="{{__('Form field')}}" data-bs-toggle="tooltip" title="{{ __('Form field') }}" 
                                                data-bs-original-title="{{__('Form field')}}"> <span class="text-white"> <i
                                                        class="ti ti-table"></i></span></a>
                                            </div>
                            
                                            <div class="action-btn bg-primary ms-2">
                                                <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center cp_link" data-link="{{url('/form/'.$form->code)}}" 
                                                    data-bs-original-title="{{__('Click to copy link')}}" data-bs-toggle="tooltip" title="{{ __('Click to copy link') }}" > <span class="text-white"> <i
                                                        class="ti ti-link"></i></span></a>
                                            </div>


                                            <div class="action-btn bg-warning ms-2">
                                                <a href="{{route('form.response',$form->id)}}" class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                data-bs-whatever="{{__('View Response')}}" data-bs-toggle="tooltip" title="{{ __('View Response') }}" 
                                                data-bs-original-title="{{__('View Response')}}"> <span class="text-white"> <i
                                                        class="ti ti-eye"></i></span></a>
                                            </div>

                                            <div class="action-btn bg-info ms-2">
                                                <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-bs-toggle="modal"
                                                data-bs-target="#exampleModal" data-url="{{ route('form_builder.edit',$form->id) }}"
                                                data-bs-whatever="{{__('Edit Form')}}" data-bs-toggle="tooltip" title="{{ __('Edit Form') }}" 
                                                data-bs-original-title="{{__('Edit Form')}}"> <span class="text-white"> <i
                                                        class="ti ti-edit"></i></span></a>
                                            </div>

                                            <div class="action-btn bg-danger ms-2">
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['form_builder.destroy', $form->id]]) !!}
                                                <a href="#!" class="mx-3 btn btn-sm d-inline-flex align-items-center show_confirm">
                                                    <i class="ti ti-trash text-white"></i>
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

