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
                    <h2>{{ __('Reset Password') }}</h2>

                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
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

                        <br>
                        
                        <div class="form-group row">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-highlight btn-wide">
                                    {{ __('Send Password Reset Link') }}
                                </button>
                            </div>
                        </div>
                        

                        
                    </form>

                </div>
            </div>

@endsection



