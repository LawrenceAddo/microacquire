@extends('layouts.simple')

@section('body_class')
    page-signup
@endsection

@section('block_name')
    signup
@endsection

@section('block_class')
    dark-theme
@endsection


@section('content')
            <div class="col-md-12 col-sm-12"> 
                <div style="width: 320px; margin: auto;" class="text-center">
                    <h2>{{ __('Create Account') }}</h2>
                    <p>{{ __('Already have an account?') }} <a href="">{{ __('Log In') }}</a></p>

                    <form method="POST" action="{{ route('register') }}">
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
                            <div class="col-md-12">
                                <input id="password-confirm" type="password" class="form-control fancy FZ " name="password_confirmation" required placeholder="Confirm Password">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <div class="btn-group btn-group-toggle highlight btn-twin-group" data-toggle="buttons">
                                  <label class="btn btn-secondary active">
                                    <input type="radio" name="user_type" id="user_type_seller" autocomplete="off" value="0" checked> {{ __('Startup/Seller') }}
                                  </label>
                                  <label class="btn btn-secondary">
                                    <input type="radio" name="user_type" id="user_type_buyer" value="1" autocomplete="off"> {{ __('Buyer') }}
                                  </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <p>{{ __('By creating an account, you agree to our') }} <a href="#">{{ __('Terms of Service') }}</a> {{ __('and have read and understood the') }} <a href="#">{{ __('Privacy Policy') }}</a></p>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-highlight btn-wide">
                                    {{ __('Sign Up') }}
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>

@endsection
