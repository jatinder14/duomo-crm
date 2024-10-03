<!-- Page Title & Bread Crumbs -->
<div class="col-md-12 col-lg-6 align-self-center">
    <!--attached to project-->
    @if(is_numeric($document->doc_project_id))
    <a id="InvoiceTitleAttached" href="{{ _url('projects/'.$document->bill_projectid) }}">
        <h3 class="text-themecolor">{{ $document->project_title }}</h3>
    </a>
    @else
    <!--not attached to project-->
    <h4 class="muted">{{ cleanLang(__('lang.not_attached_to_project')) }}</h4>
    @endif

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