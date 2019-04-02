@extends('layouts.simple')


@section('body_class')
    page-verified
@endsection

@section('block_name')
    verified
@endsection

@section('block_class')
    dark-theme
@endsection

@section('content')

    <div class="col-md-12 col-sm-12"> 
        <div style="padding: 0 5%;">
            <div style="max-width: 680px; margin: auto;" class="text-center">
                <span class="ma-icon icon-check"></span>
                <h2>Email Successfully Verified</h2>
                
                <p>Your account email has been verified successfully, you can start filling your profile with the link below.</p>
                <br>

                <p><a href="{{ route('profile_edit') }}"><button class="btn btn-highlight btn-wide">Complete  your Profile</button></a></p>
            </div>
        </div>
    </div>
@endsection