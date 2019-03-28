@extends('layouts.simple')


@section('body_class')
    page-verify-email
@endsection

@section('block_name')
    verify
@endsection

@section('block_class')
    dark-theme
@endsection

@section('content')

    <div class="col-md-12 col-sm-12"> 
        <div style="padding: 0 5%;">
            <div style="max-width: 680px; margin: auto;" class="text-center">
                <h2>{{ __('Verify your Email to Proceed') }}</h2>
                
                <p>We just sent an email to the address: {{ $user->email }}<br>
                Please check your email and click on the link provided to verify your address.</p>
                <p>Do you want to <a href="#">Change Email?</a>
                <br>or<br>
                Need help <a href="#">verifying your Email</a></p>
                <br>
                <p><a href="{{ route('verification.resend') }}"><button class="btn btn-highlight btn-wide">Resend Verification Email</button></a></p>
            </div>
        </div>
    </div>
@endsection