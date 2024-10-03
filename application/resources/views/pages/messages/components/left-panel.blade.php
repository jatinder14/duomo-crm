<div class="chat-left-aside menu-closed">
    <div class="open-panel"><i class="ti-angle-right"></i></div>
    <div class="chat-left-inner" id="chat-left-inner">
        <ul class="chatonline style-none" id="messages-left-menu">
            <!--team-->
            <li id="{{ messagesUniqueID(auth()->user()->unique_id, 'team') }}">
                <a href="javascript:void(0)" class="ajax-request messages-menu-link active" data-type="form"
                    id="messages_team_link"
                    data-form-id="{{ messagesUniqueID(auth()->user()->unique_id, 'team') }}"
                    data-ajax-type="post" data-loading-target="chat-messages-container"
                    data-message-target="team" data-progress-bar="hidden"
                    data-counter-id="messages_counter_team" data-url="{{ url('/messages/feed?action=load') }}">
                    <img src="{{ url('public/images/team.png') }}" alt="user-img" class="img-circle">
                    <span>@lang('lang.team')
                        <small class="text-info messages_user_status text-lc">@lang('lang.all_team_members')
                            <!--counter-->
                            <span class="badge badge-pill badge-danger messages_counter hidden"
                                id="messages_counter_team">
                                <!--dynamic--></span>
                        </small>
                    </span>
                </a>
                <!--(dynamic)-->
                <input type="hidden" name="message_source" value="{{ auth()->user()->unique_id }}">
                <input type="hidden" name="message_target" value="team">
                <input type="hidden" name="message_load" value="initial">
            </li>
            <!-- users list-->
            @foreach($users as $user)
            @if($user->id != auth()->user()->id)
            <li id="{{ messagesUniqueID(auth()->user()->unique_id, $user->unique_id) }}">
                <a href="javascript:void(0)" class="ajax-request messages-menu-link" data-type="form"
                    data-form-id="{{ messagesUniqueID(auth()->user()->unique_id, $user->unique_id) }}"
                    data-ajax-type="post" data-loading-target="chat-messages-container"
                    data-progress-bar="hidden" data-message-target="{{ $user->unique_id }}"
                    data-counter-id="{{ messagesCounterUniqueID($user->unique_id) }}"
                    data-url="{{ url('/messages/feed?action=load') }}">
                    <img src="{{ $user->avatar }}" alt="user-img" class="img-circle">
                    <span>{{ $user->full_name }}
                        <small id="user_status_{{ $user->id }}"
                            class="{{ runtimeMessagesUserStatus($user->is_online, 'label') }} messages_user_status text-lc">
                            <!--status-->
                            <span class="message_status_label" id="user_status_lang_{{ $user->id }}">{{ runtimeMessagesUserStatus($user->is_online, 'lang') }}</span>
                            <!--counter-->
                            <span class="badge badge-pill badge-danger messages_counter hidden"
                                id="{{ messagesCounterUniqueID($user->unique_id) }}">
                                <!--dynamic--></span>
                        </small></span></a>
                <!--(dynamic)-->
                <input type="hidden" name="message_source" value="{{ auth()->user()->unique_id }}">
                <input type="hidden" name="message_target" value="{{ $user->unique_id }}">
                <input type="hidden" name="message_load" value="initial">
            </li>
            @endif
            @endforeach
            <li class="p-20"></li>
        </ul>
    </div>
</div>