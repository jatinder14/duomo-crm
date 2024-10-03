@foreach($fields as $field)
@if($field->customfields_show_filter_panel == 'yes')
<div class="filter-block">


    <div class="title">
        {{ $field->customfields_title }}
    </div>

    <!--text-->
    @if($field->customfields_datatype =='text' || $field->customfields_datatype =='paragraph')
    <div class="fields">
        <div class="row">
            <div class="col-md-12">
                <input type="text" class="form-control form-control-sm"
                    id="filter_{{ $field->customfields_name }}" name="filter_{{ $field->customfields_name }}">
            </div>
        </div>
    </div>
    @endif

    <!--number-->
    @if($field->customfields_datatype =='number' || $field->customfields_datatype =='decimal')
    <div class="fields">
        <div class="row">
            <div class="col-md-12">
                <input type="number" class="form-control form-control-sm"
                    id="filter_{{ $field->customfields_name }}" name="filter_{{ $field->customfields_name }}">
            </div>
        </div>
    </div>
    @endif


    <!--date-->
    @if($field->customfields_datatype =='date')
    <div class="fields">
        <div class="row">
            <div class="col-md-12">
                <input type="text" class="form-control form-control-sm pickadate"
                    name="filter_{{ $field->customfields_name }}" autocomplete="off">
                <input class="mysql-date" type="hidden" name="filter_{{ $field->customfields_name }}"
                    id="filter_{{ $field->customfields_name }}">
            </div>
        </div>
    </div>
    @endif

    <!--dropdown-->
    @if($field->customfields_datatype =='dropdown')
    <div class="fields">
        <div class="row">
            <div class="col-md-12">
                <select class="select2-basic-with-search form-control form-control-sm" data-allow-clear="true"
                    id="filter_{{ $field->customfields_name }}" name="filter_{{ $field->customfields_name }}">
                    <option value=""></option>
                    {!! runtimeCustomFieldsJsonLists($field->customfields_datapayload) !!}
                </select>
            </div>
        </div>
    </div>
    @endif


    <!--checkbox-->
    @if($field->customfields_datatype =='checkbox')

    <div class="fields">
        <div class="row">
            <div class="col-md-12">
                <input type="checkbox" id="filter_{{ $field->customfields_name }}" name="filter_{{ $field->customfields_name }}"
                    class="filled-in chk-col-light-blue">
                <label class="p-l-0" for="filter_{{ $field->customfields_name }}"></label>
            </div>
        </div>
    </div>
    @endif

</div>
@endif
@endforeach