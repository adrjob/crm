{{ Form::open(array('url' => 'coupon','method' =>'post')) }}
<div class="row">
    <div class="form-group col-md-12">
        {{Form::label('name',__('Name'))}}
        {{Form::text('name',null,array('class'=>'form-control font-style','required'=>'required'))}}
    </div>

    <div class="form-group col-md-6">
        {{Form::label('discount',__('Discount'))}}
        {{Form::number('discount',null,array('class'=>'form-control','required'=>'required','step'=>'0.01'))}}
        <span class="small">{{__('Note: Discount in Percentage')}}</span>
    </div>
    <div class="form-group col-md-6">
        {{Form::label('limit',__('Limit'))}}
        {{Form::number('limit',null,array('class'=>'form-control','required'=>'required'))}}
    </div>
    <div class="form-group col-md-12">
        {{Form::label('code',__('Code'),['class'=>'col-form-label'])}}
        <div class="d-flex radio-check">
            <div class="form-check m-1">
                <input type="radio" id="radioButton2" value="manual" name="icon-input" class="form-check-input code" checked="checked">
                <label class="form-check-label" for="manual_code">{{__('Manual')}}</label>
            </div>
            <div class="form-check m-1">
                <input type="radio" id="radioButton3" value="auto" name="icon-input" class="form-check-input code">
                <label class="form-check-label" for="auto_code">{{__('Auto Generate')}}</label>
            </div>
        </div>
    </div>

    {{-- <div class="form-group">
        <div class="row">
            <div class="btn-group btn-group-toggle btn-sm" data-toggle="buttons">
                <label class="btn btn-primary btn-sm active code">
                    <input type="radio" class="icon-input"  name="icon-input" id="radioButton2" value="manual" autocomplete="off" checked> {{__('Manual')}}
                </label>
                <label class="btn btn-primary btn-sm code">
                    <input type="radio" class="icon-input"  name="icon-input" id="radioButton3" value="auto" autocomplete="off"> {{__('Auto Generate')}}
                </label>
            </div>
        </div>
    </div> --}}
    <div class="form-group col-md-12 d-block" id="manual">
        <input class="form-control font-uppercase" name="manualCode" type="text">
    </div>
    <div class="form-group col-md-12 d-none" id="auto">
        <div class="row">
            <div class="col-md-10">
                <input class="form-control" name="autoCode" type="text" id="auto-code">
            </div>
            <div class="col-md-2">
                <a href="#" class="btn btn-sm btn-secondary btn-icon rounded-pill" id="code-generate"><i class="fas fa-history"></i></a>
            </div>
        </div>
    </div>
    <div class="modal-footer pr-0">
        <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
        {{Form::submit(__('Create'),array('class'=>'btn  btn-primary'))}}
    </div>
</div>
{{ Form::close() }}

