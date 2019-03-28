@extends('layouts.simple')

@section('body_class')
    page-login
@endsection

@section('block_name')
    login
@endsection

@section('block_class')
    dark-theme
@endsection


@section('content')
            <div class="col-md-12 col-sm-12"> 
                <div style="width: 320px; margin: auto;" class="text-center">
                    <h2>{{ __('Log In') }}</h2>

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group row">
                            <div class="col-md-12">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }} fancy" name="email" value="{{ old('email') }}" required placeholder="E-mail">

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>



                        <div class="form-group row">
                            <div class="col-md-12">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }} fancy" name="password" required placeholder="Password">

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12 text-left">
                                @component('comps.checkbox')
                                    @slot('checked') 
                                        {{ old('remember') ? 'checked' : '' }}
                                    @endslot
                                    @slot('name') remember @endslot
                                    @slot('id') remember @endslot
                                    
                                    {{ __('Remember Me') }}
                                @endcomponent
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-highlight btn-wide">
                                    {{ __('Log In') }}
                                </button>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <a href="{{ route('register') }}">{{ __('Sign Up') }}</a>
                                <br>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}">Forgot your password?</a>
                                @endif
                            </div>
                        </div>

                        
                    </form>

                </div>
            </div>

@endsection



