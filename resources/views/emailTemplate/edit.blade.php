@extends('dashboard.master')
@inject('permission', 'App\Http\Controllers\PermissionController')
@section('title', __('theme.update_email_template'))
@section('style')
    
@stop

@section('main-section')
    <!-- Main content -->
    <div class="container-fluid">
        @if($permission->manageEmailTemplate() == 1)
        <h4 class="page-title">{{ __('theme.email_template') }} #{{ $template->template_type }}
            <a href="{{ route('email-template.index') }}" class="btn btn-primary btn-md pull-right">{{ __('theme.back') }}</a>
        </h4>
        <div class="row" id="eTemp" data-id="{{ $template->id }}">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="page-item">{{ __('theme.email_template') }}</div>
                    </div>
                    @include('includes.flash')
                    <!-- form start -->
                    <form role="form" class="" method="POST" action="{{ route('email-template.update',$template->id) }}">
                        {!! csrf_field() !!}
                        {{ method_field('put')}}
                        <div class="card-body">
                            <div class="form-group">
                                <label for="title">{{ __('theme.title') }}</label>
                                <input id="title" type="text" class="form-control{{ $errors->has('template_subject') ? ' is-invalid' : '' }}" name="template_subject" value="{{ $template->template_subject }}" placeholder="{{ __('theme.enter_template_subject') }}" required>

                                @if ($errors->has('template_subject'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('template_subject') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group pb-1">
                                <label for="title">{{ __('theme.content') }}</label>
                                <div class="pb-1">
                                    <textarea class="textarea my-editor w-100" name="custom_content" placeholder="{{ __('theme.place_of_content') }}" required>{{ clean($template->custom_content ?? $template->default_content) }}</textarea>
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
@stop
