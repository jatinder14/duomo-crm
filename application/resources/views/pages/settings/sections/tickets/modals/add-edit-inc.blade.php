<div class="row">
    <div class="col-lg-12">
        <!--title-->
        <div class="form-group row">
            <label class="col-12 text-left control-label col-form-label required">{{ cleanLang(__('lang.status_name')) }}*</label>
            <div class="col-12">
                <input type="text" class="form-control form-control-sm" id="ticketstatus_title" name="ticketstatus_title"
                    value="{{ $status->ticketstatus_title ?? '' }}">
            </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="form-group row">
            <div class="col-12">
                <input name="ticketstatus_colors" type="radio" id="radio_default" value="default"
                    {{ $page['default_color'] ?? '' }} {{ runtimePreChecked2('default', $status->ticketstatus_color ?? '') }}
                    class="with-gap radio-col-grey ticketstatus_colors">
                <label for="radio_default"><span class="bg-default settings-tickets-modal-color-select"
                        >&nbsp;</span>
                </label>
            </div>
            <div class="col-12 p-b-5">
                <input name="ticketstatus_colors" type="radio" id="radio_info" value="info"
                    {{ runtimePreChecked2('info', $status->ticketstatus_color ?? '') }}
                    class="with-gap radio-col-grey ticketstatus_colors">
                <label for="radio_info"><span class="bg-info settings-tickets-modal-color-select"
                        >&nbsp;</span>
                </label>
            </div>
            <div class="col-12">
                <input name="ticketstatus_colors" type="radio" id="radio_success" value="success"
                    {{ runtimePreChecked2('success', $status->ticketstatus_color ?? '') }}
                    class="with-gap radio-col-grey ticketstatus_colors">
                <label for="radio_success"><span class="bg-success settings-tickets-modal-color-select"
                        >&nbsp;</span>
                </label>
            </div>
            <div class="col-12">
                <input name="ticketstatus_colors" type="radio" id="radio_danger" value="danger"
                    {{ runtimePreChecked2('danger', $status->ticketstatus_color ?? '') }}
                    class="with-gap radio-col-grey ticketstatus_colors">
                <label for="radio_danger"><span class="bg-danger settings-tickets-modal-color-select"
                        >&nbsp;</span>
                </label>
            </div>
            <div class="col-12">
                <input name="ticketstatus_colors" type="radio" id="radio_warning" value="warning"
                    {{ runtimePreChecked2('warning', $status->ticketstatus_color ?? '') }}
                    class="with-gap radio-col-grey ticketstatus_colors">
                <label for="radio_warning"><span class="bg-warning settings-tickets-modal-color-select"
                        >&nbsp;</span>
                </label>
            </div>
            <div class="col-12">
                <input name="ticketstatus_colors" type="radio" id="radio_primary" value="primary"
                    {{ runtimePreChecked2('primary', $status->ticketstatus_color ?? '') }}
                    class="with-gap radio-col-grey ticketstatus_colors">
                <label for="radio_primary"><span class="bg-primary settings-tickets-modal-color-select"
                        >&nbsp;</span>
                </label>
            </div>
            <div class="col-12">
                <input name="ticketstatus_colors" type="radio" id="radio_lime" value="lime"
                    {{ runtimePreChecked2('lime', $status->ticketstatus_color ?? '') }}
                    class="with-gap radio-col-grey ticketstatus_colors">
                <label for="radio_lime"><span class="bg-lime settings-tickets-modal-color-select"
                        >&nbsp;</span>
                </label>
            </div>
            <div class="col-12">
                <input name="ticketstatus_colors" type="radio" id="radio_brown" value="brown"
                    {{ runtimePreChecked2('brown', $status->ticketstatus_color ?? '') }}
                    class="with-gap radio-col-grey ticketstatus_colors">
                <label for="radio_brown"><span class="bg-brown settings-tickets-modal-color-select"
                        >&nbsp;</span>
                </label>
            </div>

            <!--hidden-->
            <input type="hidden" name="ticketstatus_color" id="ticketstatus_color" value="">

        </div>
    </div>