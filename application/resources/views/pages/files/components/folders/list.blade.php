<!--folders list view-->
<div class="folders-list-view">


    <ul>

        @foreach($folders as $folder)
        <li id="folder_{{ $folder->filefolder_id }}" class="ajax-request file-folder-menu-item {{ runtimeFileFoldersActive($folder->filefolder_id, request('filter_folderid')) }}"
            data-url="{{ urlResource('/files').'&source=ext&filter_folderid='.$folder->filefolder_id }}">
            <span>{{ $folder->filefolder_name }}</span></li>
        @endforeach

    </ul>


</div>