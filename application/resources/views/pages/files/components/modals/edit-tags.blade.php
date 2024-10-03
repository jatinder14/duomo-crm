<!--tags-->
<div class="form-group row">
    <label class="col-12 text-left control-label col-form-label">{{ cleanLang(__('lang.tags')) }}</label>
    <div class="col-12">
        <select name="tags" id="tags"
            class="form-control form-control-sm select2-multiple {{ runtimeAllowUserTags() }} select2-hidden-accessible"
            multiple="multiple" tabindex="-1" aria-hidden="true">
            <!--array of selected tags-->
            @foreach($file->tags as $tag)
            @php $selected_tags[] = $tag->tag_title ; @endphp
            @endforeach
            <!--/#array of selected tags-->
            @foreach($tags as $tag)
            <option value="{{ $tag->tag_title }}"
                {{ runtimePreselectedInArray($tag->tag_title ?? '', $selected_tags ?? []) }}>
                {{ $tag->tag_title }}
            </option>
            @endforeach
        </select>
    </div>
</div>