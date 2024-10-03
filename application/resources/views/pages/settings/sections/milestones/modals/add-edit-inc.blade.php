<div class="row">
    <div class="col-lg-12">
        <!--title-->
        <div class="form-group row">
            <label
                class="col-12 text-left control-label col-form-label required">{{ cleanLang(__('lang.milestone_name')) }}</label>
            <div class="col-12">
                <input type="text" class="form-control form-control-sm" id="milestonecategory_title"
                    name="milestonecategory_title" value="{{ $milestone->milestonecategory_title ?? '' }}">
            </div>
        </div>
    </div>

    <!--colors-->
    <div class="col-lg-12">
        <div class="form-group row">
            <div class="col-12">
                <input name="milestonecategory_colors" type="radio" id="radio_default" value="default"
                    {{ $page['default_color'] ?? '' }}
                    {{ runtimePreChecked2('default', $milestone->milestonecategory_color ?? '') }}
                    class="with-gap radio-col-grey milestonecategory_colors">
                <label for="radio_default"><span class="bg-default settings-tasks-modal-color-select">&nbsp;</span>
                </label>
            </div>
            <div class="col-12 p-b-5">
                <input name="milestonecategory_colors" type="radio" id="radio_info" value="info"
                    {{ runtimePreChecked2('info', $milestone->milestonecategory_color ?? '') }}
                    class="with-gap radio-col-grey milestonecategory_colors">
                <label for="radio_info"><span class="bg-info settings-tasks-modal-color-select">&nbsp;</span>
                </label>
            </div>
            <div class="col-12">
                <input name="milestonecategory_colors" type="radio" id="radio_success" value="success"
                    {{ runtimePreChecked2('success', $milestone->milestonecategory_color ?? '') }}
                    class="with-gap radio-col-grey milestonecategory_colors">
                <label for="radio_success"><span class="bg-success settings-tasks-modal-color-select">&nbsp;</span>
                </label>
            </div>
            <div class="col-12">
                <input name="milestonecategory_colors" type="radio" id="radio_danger" value="danger"
                    {{ runtimePreChecked2('danger', $milestone->milestonecategory_color ?? '') }}
                    class="with-gap radio-col-grey milestonecategory_colors">
                <label for="radio_danger"><span class="bg-danger settings-tasks-modal-color-select">&nbsp;</span>
                </label>
            </div>
            <div class="col-12">
                <input name="milestonecategory_colors" type="radio" id="radio_warning" value="warning"
                    {{ runtimePreChecked2('warning', $milestone->milestonecategory_color ?? '') }}
                    class="with-gap radio-col-grey milestonecategory_colors">
                <label for="radio_warning"><span class="bg-warning settings-tasks-modal-color-select">&nbsp;</span>
                </label>
            </div>
            <div class="col-12">
                <input name="milestonecategory_colors" type="radio" id="radio_primary" value="primary"
                    {{ runtimePreChecked2('primary', $milestone->milestonecategory_color ?? '') }}
                    class="with-gap radio-col-grey milestonecategory_colors">
                <label for="radio_primary"><span class="bg-primary settings-tasks-modal-color-select">&nbsp;</span>
                </label>
            </div>
            <div class="col-12">
                <input name="milestonecategory_colors" type="radio" id="radio_lime" value="lime"
                    {{ runtimePreChecked2('lime', $milestone->milestonecategory_color ?? '') }}
                    class="with-gap radio-col-grey milestonecategory_colors">
                <label for="radio_lime"><span class="bg-lime settings-tasks-modal-color-select">&nbsp;</span>
                </label>
            </div>
            <div class="col-12">
                <input name="milestonecategory_colors" type="radio" id="radio_brown" value="brown"
                    {{ runtimePreChecked2('brown', $milestone->milestonecategory_color ?? '') }}
                    class="with-gap radio-col-grey milestonecategory_colors">
                <label for="radio_brown"><span class="bg-brown settings-tasks-modal-color-select">&nbsp;</span>
                </label>
            </div>

            <!--hidden-->
            <input type="hidden" name="milestonecategory_color" id="milestonecategory_color" value="{{ $milestone->milestonecategory_color ?? '' }}">

        </div>
    </div>


    <div class="form-group form-group-checkbox row">
        <div class="col-12 p-t-5 p-l-33">
            <input type="checkbox" id="reset_color_on_project_milestone" name="reset_color_on_project_milestone" class="filled-in chk-col-light-blue"
                {{ runtimePrechecked($fooo->bar ?? '') }} checked>
            <label class="p-l-30" for="reset_color_on_project_milestone">@lang('lang.reset_color_on_project_milestone')</label>
        </div>
    </div>
</div>