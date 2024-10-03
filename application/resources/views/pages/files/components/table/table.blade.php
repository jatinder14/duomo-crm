@if(request('fileresource_type') == 'project' && request()->filled('fileresource_id') && config('system.settings2_file_folders_status') == 'enabled')
<div class="file-folders enabled">


    <!--folders-->
    @include('pages.files.components.folders.wrapper')

    <!--table wrapper-->
    @include('pages.files.components.table.table-wrapper')

</div>
@else

<!--table wrapper-->
@include('pages.files.components.table.table-wrapper')

@endif