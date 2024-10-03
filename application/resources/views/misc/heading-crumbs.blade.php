<!--CRUMBS CONTAINER (LEFT)-->
<div class="col-md-12 {{ runtimeCrumbsColumnSize($page['crumbs_col_size'] ?? '') }} align-self-center {{ $page['crumbs_special_class'] ?? '' }}" id="breadcrumbs">
    <h3 class="text-themecolor">{{ $page['heading'] }}</h3>
    <!--crumbs-->
    <ol class="breadcrumb">
        <li class="breadcrumb-item">{{ cleanLang(__('lang.app')) }}</li>
        @if(isset($page['crumbs']))
        @foreach ($page['crumbs'] as $title) 
        <li class="breadcrumb-item @if ($loop->last) active active-bread-crumb @endif">{{ $title ?? '' }}</li>
        @endforeach
        @endif

        <!--filtered results label-->
        @if(request('filtered_results'))
        <li class="m-t-3" id="clear_preset_filter_button_container">
            <div class="btn-group display-inline-block">
                <button type="button" class="btn btn-sm btn-warning dropdown-toggle display-inline-block font-10 m-l-8 p-0 p-l-10 p-r-10" data-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false">
                    @lang('lang.filtered_results')
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item font-13 ajax-request" href="javascript:void(0);" id="clear_preset_filter_button"
                    data-url="{{ urlResource(request('filtered_url')) }}">@lang('lang.clear_filter')</a>
                </div>
            </div>
        </li>
        @endif
    </ol>
    <!--crumbs-->
</div>

<!--include various checkbox actions-->

@if(isset($page['page']) && $page['page'] == 'files')
@include('pages.files.components.actions.checkbox-actions')
@endif

@if(isset($page['page']) && $page['page'] == 'notes')
@include('pages.notes.components.actions.checkbox-actions')
@endif
