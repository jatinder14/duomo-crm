<!--number field-->
<div class="form-group row">
    <label class="col-12 text-left control-label col-form-label {{ runtimeWebformRequiredBold($payload['required']) }}">
        {{ $payload['label'] }}{{ runtimeWebformRequiredAsterix($payload['required']) }} @if($payload['tooltip'] != '')
        <span class="align-middle text-default font-16" data-toggle="tooltip" title="{{ $payload['tooltip'] }}"
            data-placement="top"><i class="ti-info-alt"></i></span>
        @endif</label>
    <div class="col-12">
        <input type="number" class="{{ $payload['class'] }}" id="{{ $payload['name'] }}" name="{{ $payload['name'] }}"
            placeholder="{{ $payload['placeholder'] }}" placeholder="">
    </div>
</div>