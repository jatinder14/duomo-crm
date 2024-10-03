<!--item-->
<div class="form-group row">
    <label class="col-sm-12 text-left control-label col-form-label">@lang('lang.title')</label>
    <div class="col-sm-12">
        <input type="text" class="form-control form-control-sm" id="contract_template_title" name="contract_template_title"
            value="{{ $template->contract_template_title ?? '' }}">
    </div>
</div>

<!--contract_template_body-->
<div class="form-group row">
    <label class="col-sm-12 text-left control-label col-form-label">@lang('lang.content')</label>
    <div class="col-sm-12">
        <textarea class="form-control form-control-sm tinymce-textarea-extended" rows="5" name="contract_template_body"
            id="contract_template_body">{!! $template->contract_template_body ?? '' !!}</textarea>
    </div>
</div>