@extends('adminlte::auth.auth-page', ['auth_type' => 'login'])

@include('partials.feedback')

@section('auth_body')
    <form action="{{ route('api_profile_password_reset') }}" method="post">
        {{ csrf_field() }}
        
        <input type="hidden" name="token" value="{{ $token }}">

        {{-- Token field --}}

        {{-- Password field --}}
        <div class="input-group mb-3">
            <input type="password" name="password"
                   class="form-control"
                   placeholder="{{ __('adminlte::adminlte.password') }}">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>
            <div class="invalid-feedback" style="display:block;">
                <span class="text-danger">{{ $errors->first('password') }}</span>
            </div>
        </div>

        {{-- Password confirmation field --}}
        <div class="input-group mb-3">
            <input type="password" name="password_confirmation"
                   class="form-control"
                   placeholder="{{ trans('adminlte::adminlte.retype_password') }}">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>            
        </div>

        {{-- Confirm password reset button --}}
        <button type="submit" class="btn btn-block {{ config('adminlte.classes_auth_btn', 'btn-flat btn-primary') }}">
            <span class="fas fa-sync-alt"></span>
            {{ __('adminlte::adminlte.reset_password') }}
        </button>

    </form>
@stop
