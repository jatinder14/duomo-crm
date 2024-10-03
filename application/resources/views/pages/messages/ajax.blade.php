<!--chat left -->
@if($message->message_creatorid != auth()->user()->id)
<li id="{{ messageUniqueID($message->message_unique_id) }}" class="messages_message">
    <div class="chat-img"><img src="{{ getUsersAvatar($message->avatar_directory, $message->avatar_filename)  }}"
            alt="user" />
    </div>
    <div class="chat-content">
        <h5>
            @if($message->first_name)
            {{  $message->first_name  .' '. $message->last_name }}
            @else
            runtimeUnkownUser()
            @endif
        </h5>
        <div class="box message-content-box {{ 'message_type_'.$message->message_file_type }} bg-light-info">
            <div class="message-content">
                <!--text-->
                @if($message->message_type == 'text')
                <div class="x-text">
                    {!! _clean($message->message_text) !!}
                </div>
                @endif
                <!--file-->
                @if($message->message_type == 'file' && $message->message_file_type == 'file')
                <div class="x-file">
                    <a href="{{ url('storage/files/'.$message->message_file_directory.'/'.$message->message_file_name) }}"
                        download>
                        <i class="sl-icon-paper-clip"></i> <span>{{ $message->message_file_name }}</span>
                    </a>
                </div>
                @endif
                <!--image-->
                @if($message->message_type == 'file' && $message->message_file_type == 'image')
                <div class="x-image">
                    <a href="{{ url('storage/files/'.$message->message_file_directory.'/'.$message->message_file_name) }}"
                        download>
                        <img src="{{ url('storage/files/'.$message->message_file_directory.'/'.$message->message_file_thumb_name) }}"
                            alt="{{ $message->message_file_name }}" />
                    </a>
                </div>
            </div>
            @endif
            <!--meta-->
            <div class="x-meta">
                <!--delete button-->
                @if(auth()->user()->is_admin)
                <span id="message_delete_button_{{ $message->message_id }}"
                    class="text-danger messages_delete_button hidden x-left-side"
                    data-message-id="{{ messageUniqueID($message->message_unique_id) }}" data-progress-bar="hidden"
                    data-ajax-type="DELETE" data-url="{{ url('/messages/'.$message->message_unique_id) }}">
                    <i class="sl-icon-trash"></i>
                </span>
                @endif
                <span class="time">{{ runtimeDateAgo($message->message_created) }}</span>
            </div>
        </div>
    </div>
</li>
@endif

<!--chat right -->
@if($message->message_creatorid == auth()->user()->id)
<li class="reverse" id="{{ messageUniqueID($message->message_unique_id) }}">
    <div class="chat-content my-chat-content">
        <div class="box message-content-box bg-light-inverse {{ 'message_type_'.$message->message_file_type }}">
            <div class="message-content">
                <!--text-->
                @if($message->message_type == 'text')
                <div class="x-text">
                    {!! _clean($message->message_text) !!}
                </div>
                @endif
                <!--file-->
                @if($message->message_type == 'file' && $message->message_file_type == 'file')
                <div class="x-file">
                    <a href="{{ url('storage/files/'.$message->message_file_directory.'/'.$message->message_file_name) }}"
                        download>
                        <i class="sl-icon-paper-clip"></i> <span>{{ $message->message_file_name }}</span>
                    </a>
                </div>
                @endif
                <!--image-->
                @if($message->message_type == 'file' && $message->message_file_type == 'image')
                <div class="x-image">
                    <a href="{{ url('storage/files/'.$message->message_file_directory.'/'.$message->message_file_name) }}"
                        download>
                        <img src="{{ url('storage/files/'.$message->message_file_directory.'/'.$message->message_file_thumb_name) }}"
                            alt="{{ $message->message_file_name }}" />
                    </a>
                </div>
                @endif
            </div>
            <!--meta-->
            <div class="x-meta">
                <!--delete button-->
                <span id="message_delete_button_{{ $message->message_id }}"
                    class="text-danger messages_delete_button hidden x-right-side"
                    data-message-id="{{ messageUniqueID($message->message_unique_id) }}" data-progress-bar="hidden"
                    data-ajax-type="DELETE" data-url="{{ url('/messages/'.$message->message_unique_id) }}">
                    <i class="sl-icon-trash"></i>
                </span>
                <span class="time">{{ runtimeDateAgo($message->message_created) }}</span>
            </div>
        </div>
    </div>
</li>
@endif