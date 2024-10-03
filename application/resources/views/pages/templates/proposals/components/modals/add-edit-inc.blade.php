<!--item-->
<div class="form-group row">
    <label class="col-sm-12 text-left control-label col-form-label">@lang('lang.title')</label>
    <div class="col-sm-12">
        <input type="text" class="form-control form-control-sm" id="proposal_template_title" name="proposal_template_title"
            value="{{ $template->proposal_template_title ?? '' }}">
    </div>
</div>

<!--proposal_template_body-->
<div class="form-group row">
    <label class="col-sm-12 text-left control-label col-form-label">@lang('lang.content')</label>
    <div class="col-sm-12">
        <textarea class="form-control form-control-sm tinymce-textarea-extended" rows="5" name="proposal_template_body"
            id="proposal_template_body">{!! $template->proposal_template_body ?? '' !!}</textarea>
    </div>
</div>