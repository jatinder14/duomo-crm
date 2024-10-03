 <!--item-->
 <div class="form-group row">
     <label class="col-12 text-left control-label col-form-label required">@lang('lang.response_title')</label>
     <div class="col-12">
         <input type="text" class="form-control form-control-sm" id="canned_title" name="canned_title"
             value="{{ $canned->canned_title ?? '' }}">
     </div>
 </div>

 <!--category-->
 @if(!request()->filled('filter_categoryid'))
 <div class="form-group row">
     <label class="col-12 text-left control-label col-form-label required">@lang('lang.category')</label>
     <div class="col-12">
         <select class="select2-basic form-control form-control-sm select2-preselected" id="filter_categoryid"
             name="filter_categoryid" data-preselected="{{ $canned->canned_categoryid ?? -1}}">
             <option></option>
             @foreach($categories as $category)
             <option value="{{ $category->category_id }}">{{ $category->category_name }}</option>
             @endforeach
         </select>
     </div>
 </div>
 @endif

 <!--message-->
 <div class="form-group row">
     <label class="col-12 text-left control-label col-form-label required">@lang('lang.message')</label>
     <div class="col-12">
         <textarea class="form-control form-control-sm tinymce-textarea-canned" rows="10" name="html_canned_message"
             id="html_canned_message">{{ $canned->canned_message ?? '' }}</textarea>
     </div>
 </div>

 <!--item-->
 @if(auth()->user()->role->role_canned == 'yes')
 <div class="form-group form-group-checkbox row">
     <div class="col-12 p-t-5">
         <input type="checkbox" id="canned_visibility" name="canned_visibility" class="filled-in chk-col-light-blue"
         {{ runtimePrechecked($canned->canned_visibility ?? '', 'private') }}>
         <label class="p-l-30" for="canned_visibility">@lang('lang.private')</label>
     </div>
 </div>
 @endif