<!-- Page Title & Bread Crumbs -->
<div class="col-md-12 col-lg-6 align-self-center">
    <h3 class="text-themecolor">{{ $page['heading'] ?? '' }}
        <!--automation-->
        @if(auth()->user()->is_team)
        <a href="javascript:void(0)"
            class="edit-add-modal-button js-ajax-ux-request reset-target-modal-form {{ projectAutomationVisibility($project->project_automation_status) }}" 
            id="project_automation_icon_{{ $project->project_id }}"
            data-toggle="modal"
            data-target="#commonModal"
            data-url="{{ urlResource('/projects/'.$project->project_id.'/edit-automation?ref=list') }}"
            data-loading-target="commonModalBody" data-modal-title="@lang('lang.project_automation')"
            data-action-url="{{ urlResource('/projects/'.$project->project_id.'/edit-automation?ref=list') }}"
            data-action-method="POST" data-action-ajax-loading-target="commonModalBody">
            <span
                class="sl-icon-energy text-warning p-l-5"
                data-toggle="tooltip"
                title="@lang('lang.project_automation')"></span>
        </a>
        @endif</h3>
    <!--crumbs-->
    <ol class="breadcrumb">
        <li class="breadcrumb-item">{{ cleanLang(__('lang.app')) }}</li>
        @if(isset($page['crumbs']))
        @foreach ($page['crumbs'] as $title)
        <li class="breadcrumb-item @if ($loop->last) active @endif">{{ $title ?? '' }}</li>
        @endforeach
        @endif
    </ol>
    <!--crumbs-->
</div>
<!--Page Title & Bread Crumbs -->