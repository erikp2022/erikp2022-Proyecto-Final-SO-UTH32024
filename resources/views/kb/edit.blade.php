@extends('dashboard.master')
@inject('permission', 'App\Http\Controllers\PermissionController')
@section('title', __('theme.edit_knowledge_base'))
@section('style')
    
@stop

@section('main-section')
    <!-- Main content -->
    <div class="container-fluid">
        @if($permission->manageKB() == 1)
        <h4 class="page-title">{{ __('theme.edit_knowledge_base') }}
            <a href="{{ route('knowledge-base.index') }}" class="btn btn-primary btn-md pull-right">{{ __('theme.back') }}</a>
        </h4>
        <div class="row">
            <div class="col-md-12">
                <div class="card" id="editKB" data-id="{{ $kb->id }}">
                @include('includes.flash')
                    <!-- form start -->
                    <form role="form" class="" method="POST" action="{{ route('kb.update',$kb->id) }}">
                        {!! csrf_field() !!}
                        <div class="card-body">
                            <div class="form-group">
                                <label for="title">{{ __('theme.department') }}</label>
                                <select id="department" type="text" class="form-control" name="department" required>
                                    <option> {{ __('theme.select_department') }}</option>
                                    @foreach($departments as $department)
                                        <option value="{{ $department->id }}" {{ $kb->department_id == $department->id ? 'selected' : '' }}>{{ $department->title }}</option>
                                    @endforeach
                                </select>

                                @if ($errors->has('department'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('department') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="title">{{ __('theme.title') }}</label>
                                <input id="title" type="text" class="form-control" name="title" value="{{ $kb->title }}" placeholder="{{ __('theme.enter_question') }}">

                                @if ($errors->has('title'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('title') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group pb-1">
                                <label for="title">{{ __('theme.description') }}</label>
                                <div class="pb-1">
                                    <textarea class="textarea my-editor kb-desc" name="content" placeholder="{{ __('theme.place_of_description') }}">{{ clean($kb->content) }}</textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="title">{{ __('theme.status') }}</label><br>

                                <div class="form-check-inline">
                                    <label class="customradio"><span class="radiotextsty">{{ __('theme.published') }}</span>
                                      <input type="radio" checked="checked" name="status" value="1" {{ $kb->status == 1 ? 'checked' :'' }} required>
                                      <span class="checkmark"></span>
                                    </label>        
                                    <label class="customradio"><span class="radiotextsty">{{ __('theme.unpublished') }}</span>
                                      <input type="radio" name="status" value="0" {{ $kb->status == 0 ? 'checked' :'' }} required>
                                      <span class="checkmark"></span>
                                    </label>
                                </div>
                            </div>

                        </div>
                        <!-- /.card-body -->

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary btn-block">{{ __('theme.update') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @else
            <div class="callout callout-warning">
                <h4>{{ __('theme.access_denied') }}</h4>

                <p>{{ __("theme.don't_have_permission") }}</p>
            </div>
        @endif
    </div>

@endsection

@section('js')
    <script src="{{asset('tinymce/tinymce.min.js')}}"></script>
    <script src="{{asset('tinymce/script.js')}}"></script>
@endsection
