@extends('layout.wrapper') @section('content')

<!--javascript-->
<script src="public/js/core/messages.js?v={{ config('system.versioning') }}"></script>

<!-- main content -->
<div class="container-fluid">
    <!-- .chat-row -->
    <div class="chat-main-box">

        <!-- .chat-left-panel -->
        @include('pages.messages.components.left-panel')

        <!-- .chat-right-panel -->
        @include('pages.messages.components.right-panel')


        <!-- file uplaod -->
        @include('pages.messages.components.file-upload')

    </div>
    <!-- .chat-right-panel -->
</div>
<!-- /.chat-row -->

</div>
<!--main content -->


@endsection